<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EmailHelper;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\LoginSenderRequest;
use App\Http\Requests\Api\RegisterSenderRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\UpdateSenderRequest;
use App\Http\Requests\Api\VerifyCodeRequest;
use App\Http\Resources\SenderResource;
use App\Repositories\Contracts\SenderDeviceRepositoryInterface;
use App\Repositories\Contracts\SenderRepositoryInterface;
use App\Repositories\Contracts\SenderVerificationCodeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group Sender Authentication
 *
 * APIs for sender authentication and management
 */
class SenderAuthController extends BaseApiController
{
    public function __construct(
        protected SenderRepositoryInterface $senderRepository,
        protected SenderVerificationCodeRepositoryInterface $verificationCodeRepository,
        protected SenderDeviceRepositoryInterface $deviceRepository
    ) {}

    /**
     * Register Sender
     *
     * Register a new sender and send verification code to email.
     *
     * @bodyParam full_name string required Sender full name. Example: Ahmed Osama
     * @bodyParam email string required Sender email. Example: ahmed@example.com
     * @bodyParam phone string required Sender phone. Example: +96170123456
     * @bodyParam password string required Password (min 8 characters). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * @bodyParam type string optional User type (sender or traveler). Defaults to 'sender'. Example: sender
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Registration successful. Verification code sent to your email.",
     *   "data": {
     *     "id": 1,
     *     "email": "ahmed@example.com",
     *     "is_verified": false,
     *     "type": "sender"
     *   }
     * }
     */
    public function register(RegisterSenderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Set default type to 'sender' if not provided
        if (!isset($validated['type']) || empty($validated['type'])) {
            $validated['type'] = 'sender';
        }

        // Validate type value
        if (!in_array($validated['type'], ['sender', 'traveler'])) {
            return $this->error('Type must be either "sender" or "traveler"', 422);
        }

        // Create sender
        $sender = $this->senderRepository->create($validated);

        // Generate verification code
        $code = EmailHelper::generateCode();

        // Create verification code record
        $this->verificationCodeRepository->create([
            'sender_id' => $sender->id,
            'email' => $sender->email,
            'code' => $code,
            'type' => 'email_verification',
        ]);

        // Only send email in production or if code is not the development code
        $env = config('app.env', 'production');
        $isDevelopment = in_array($env, ['local', 'development', 'dev', 'staging', 'testing']);

        if (!$isDevelopment || $code !== '111111') {
            // Send verification code via email
            EmailHelper::sendVerificationCode($sender->email, $code, $sender->full_name);
        }

        $message = $isDevelopment && $code === '111111'
            ? 'Registration successful. Use code 111111 to verify your email.'
            : 'Registration successful. Verification code sent to your email.';

        return $this->created([
            'id' => $sender->id,
            'email' => $sender->email,
            'is_verified' => $sender->is_verified,
            'type' => $sender->type,
        ], $message);
    }

    /**
     * Verify Email Code
     *
     * Verify email with code sent during registration.
     * In development: code "111111" is accepted without database check.
     *
     * @bodyParam email string required Email address. Example: ahmed@example.com
     * @bodyParam code string required 6-digit verification code. Example: 123456
     * @bodyParam type string required Code type. Example: email_verification
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Email verified successfully",
     *   "data": {
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *     "sender": {
     *       "id": 1,
     *       "full_name": "Ahmed Osama",
     *       "email": "ahmed@example.com",
     *       "is_verified": true
     *     }
     *   }
     * }
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Check if it's development environment and code is "111111"
        $env = config('app.env', 'production');
        $isDevelopment = in_array($env, ['local', 'development', 'dev', 'staging', 'testing']);
        $isDevCode = $validated['code'] === '111111';

        $verificationCode = null;
        $sender = null;

        // In development, accept "111111" without database check
        if ($isDevelopment && $isDevCode) {
            // Find sender by email or phone
            if (isset($validated['email'])) {
                $sender = $this->senderRepository->findByEmail($validated['email']);
            } else {
                $sender = $this->senderRepository->findByPhone($validated['phone'] ?? '');
            }

            if (!$sender) {
                return $this->error('Sender not found', 404);
            }
        } else {
            // Normal verification flow - check database
            if (isset($validated['email'])) {
                $verificationCode = $this->verificationCodeRepository->findValidCode(
                    $validated['email'],
                    $validated['code'],
                    $validated['type']
                );
            } else {
                $verificationCode = $this->verificationCodeRepository->findValidCodeByPhone(
                    $validated['phone'],
                    $validated['code'],
                    $validated['type']
                );
            }

            if (!$verificationCode) {
                return $this->error('Invalid or expired verification code', 400);
            }

            // Mark code as used
            $this->verificationCodeRepository->markAsUsed($verificationCode->id);

            // Get sender
            $sender = $verificationCode->sender ?? $this->senderRepository->findByEmail($validated['email'] ?? '');

            if (!$sender) {
                $sender = $this->senderRepository->findByPhone($validated['phone'] ?? '');
            }

            if (!$sender) {
                return $this->error('Sender not found', 404);
            }
        }

        // Verify email if it's email verification
        if ($validated['type'] === 'email_verification') {
            $this->senderRepository->verifyEmail($sender->id);
            $sender->refresh();
        }

        // Generate JWT token
        $token = Auth::guard('sender')->login($sender);

        return $this->success([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'sender' => new SenderResource($sender),
        ], 'Email verified successfully');
    }

    /**
     * Login Sender
     *
     * Login with email or phone and password.
     *
     * @bodyParam email_or_phone string required Email or phone number. Example: ahmed@example.com
     * @bodyParam password string required Password. Example: password123
     * @bodyParam device_id string optional Device ID. Example: device123
     * @bodyParam fcm_token string optional FCM token. Example: fcm_token_here
     * @bodyParam device_type string optional Device type (ios, android, web). Example: android
     * @bodyParam device_name string optional Device name. Example: Samsung Galaxy
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Login successful",
     *   "data": {
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *     "sender": {
     *       "id": 1,
     *       "full_name": "Ahmed Osama",
     *       "email": "ahmed@example.com"
     *     }
     *   }
     * }
     */
    public function login(LoginSenderRequest $request): JsonResponse
    {
        $credentials = $request->only('email_or_phone', 'password');
        $identifier = $credentials['email_or_phone'];

        // Find sender by email or phone
        $sender = $this->senderRepository->findByEmailOrPhone($identifier);

        if (!$sender) {
            return $this->error('Invalid credentials', 401);
        }

        // Check if sender is blocked or banned
        if ($sender->isBlockedOrBanned()) {
            return $this->error('Your account has been ' . $sender->status, 403);
        }

        // Verify password
        if (!Hash::check($credentials['password'], $sender->password)) {
            return $this->error('Invalid credentials', 401);
        }

        // Generate JWT token
        $token = Auth::guard('sender')->login($sender);

        // Save device information if provided
        if ($request->has('device_id')) {
            $this->deviceRepository->createOrUpdate([
                'sender_id' => $sender->id,
                'device_id' => $request->device_id,
                'fcm_token' => $request->fcm,
                'device_type' => $request->device_type,
                'device_name' => $request->device_name,
            ]);
        }

        return $this->success([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'sender' => new SenderResource($sender),
        ], 'Login successful');
    }

    /**
     * Get Authenticated Sender
     *
     * Get current authenticated sender data.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Sender data retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "full_name": "Ahmed Osama",
     *     "email": "ahmed@example.com",
     *     "phone": "+96170123456",
     *     "avatar": "http://example.com/storage/avatars/avatar.jpg"
     *   }
     * }
     */
    public function me(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();
        return $this->success(new SenderResource($sender), 'Sender data retrieved successfully');
    }

    /**
     * Update Sender Data
     *
     * Update authenticated sender information.
     *
     * @bodyParam full_name string optional Full name. Example: Ahmed Osama
     * @bodyParam email string optional Email. Example: ahmed@example.com
     * @bodyParam phone string optional Phone. Example: +96170123456
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Sender updated successfully",
     *   "data": {
     *     "id": 1,
     *     "full_name": "Ahmed Osama",
     *     "email": "ahmed@example.com"
     *   }
     * }
     */
    public function update(UpdateSenderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $passwordValidation = $request->validate([
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (isset($passwordValidation['password'])) {
            $validated['password'] = $passwordValidation['password'];
        }

        $sender = Auth::guard('sender')->user();
        $updatedSender = $this->senderRepository->update($sender->id, $validated);

        return $this->success(new SenderResource($updatedSender), 'Sender updated successfully');
    }

    /**
     * Upload Avatar
     *
     * Upload sender avatar image.
     *
     * @bodyParam avatar file required Avatar image file (max 5MB). Example: (binary)
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Avatar uploaded successfully",
     *   "data": {
     *     "id": 1,
     *     "avatar": "http://example.com/storage/avatars/avatar.jpg"
     *   }
     * }
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $sender = Auth::guard('sender')->user();

        // Ensure avatars directory exists
        if (!Storage::disk('public')->exists('avatars')) {
            Storage::disk('public')->makeDirectory('avatars');
        }

        // Delete old avatar if exists
        if ($sender->avatar) {
            $oldPath = str_replace('storage/', '', $sender->avatar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Upload new avatar
        $avatarFile = $request->file('avatar');
        if (!$avatarFile || !$avatarFile->isValid()) {
            return $this->error('Invalid file uploaded', 400);
        }

        $avatarPath = $avatarFile->store('avatars', 'public');

        // Store just the path relative to storage/app/public
        $updatedSender = $this->senderRepository->updateAvatar($sender->id, $avatarPath);

        return $this->success(new SenderResource($updatedSender), 'Avatar uploaded successfully');
    }

    /**
     * Forget Password
     *
     * Send password reset code to email.
     *
     * @bodyParam email string required Email address. Example: ahmed@example.com
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Password reset code sent to your email",
     *   "data": null
     * }
     */
    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $sender = $this->senderRepository->findByEmail($request->email);

        // Generate reset code
        $code = EmailHelper::generateCode();

        // Invalidate old codes
        $this->verificationCodeRepository->invalidateOldCodes($sender->id, $sender->email, 'password_reset');

        // Create new verification code
        $this->verificationCodeRepository->create([
            'sender_id' => $sender->id,
            'email' => $sender->email,
            'code' => $code,
            'type' => 'password_reset',
        ]);

        // Send code via email
        EmailHelper::sendPasswordResetCode($sender->email, $code, $sender->full_name);

        return $this->success(null, 'Password reset code sent to your email');
    }

    /**
     * Reset Password
     *
     * Reset password using verification code.
     *
     * @bodyParam email string required Email address. Example: ahmed@example.com
     * @bodyParam code string required 6-digit verification code. Example: 123456
     * @bodyParam password string required New password (min 8 characters). Example: newpassword123
     * @bodyParam password_confirmation string required Password confirmation. Example: newpassword123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Password reset successfully",
     *   "data": null
     * }
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Find valid verification code
        $verificationCode = $this->verificationCodeRepository->findValidCode(
            $validated['email'],
            $validated['code'],
            'password_reset'
        );

        if (!$verificationCode) {
            return $this->error('Invalid or expired verification code', 400);
        }

        // Mark code as used
        $this->verificationCodeRepository->markAsUsed($verificationCode->id);

        // Update password
        $sender = $verificationCode->sender ?? $this->senderRepository->findByEmail($validated['email']);
        $this->senderRepository->update($sender->id, ['password' => $validated['password']]);

        return $this->success(null, 'Password reset successfully');
    }

    /**
     * Logout
     *
     * Logout authenticated sender and invalidate token.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logout successful",
     *   "data": null
     * }
     */
    public function logout(): JsonResponse
    {
        Auth::guard('sender')->logout();
        return $this->success(null, 'Logout successful');
    }

    /**
     * Switch User Type
     *
     * Switch between sender and traveler type. This will update the user's type and invalidate the current token.
     * User will need to login again after switching.
     *
     * @bodyParam type string required The type to switch to (sender or traveler). Example: traveler
     *
     * @response 200 {
     *   "success": true,
     *   "message": "User type switched successfully. Please login again.",
     *   "data": {
     *     "type": "traveler"
     *   }
     * }
     *
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "type": ["The type field is required."]
     *   }
     * }
     */
    public function switchType(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:sender,traveler'],
        ]);

        $sender = Auth::guard('sender')->user();

        // Check if type is already set to the requested type
        if ($sender->type === $validated['type']) {
            return $this->error('User is already of type ' . $validated['type'], 400);
        }

        // Update user type
        $this->senderRepository->update($sender->id, ['type' => $validated['type']]);

        // Invalidate current token by logging out
        Auth::guard('sender')->logout();

        return $this->success([
            'type' => $validated['type'],
        ], 'User type switched successfully. Please login again.');
    }
}

