<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    /**
     * Send verification code to email
     */
    public static function sendVerificationCode(string $email, string $code, string $name = null): bool
    {
        try {
            $subject = 'Email Verification Code';
            $message = self::getVerificationEmailTemplate($code, $name);

            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });

            Log::info('Verification code sent to email', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send verification code email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send password reset code to email
     */
    public static function sendPasswordResetCode(string $email, string $code, string $name = null): bool
    {
        try {
            $subject = 'Password Reset Code';
            $message = self::getPasswordResetEmailTemplate($code, $name);

            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });

            Log::info('Password reset code sent to email', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send password reset code email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get verification email template
     */
    private static function getVerificationEmailTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? "Hello {$name}," : "Hello,";
        
        return "
{$greeting}

Thank you for registering with EGK!

Your verification code is: {$code}

This code will expire in 10 minutes.

If you didn't request this code, please ignore this email.

Best regards,
EGK Team
        ";
    }

    /**
     * Get password reset email template
     */
    private static function getPasswordResetEmailTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? "Hello {$name}," : "Hello,";
        
        return "
{$greeting}

You requested to reset your password.

Your password reset code is: {$code}

This code will expire in 10 minutes.

If you didn't request this, please ignore this email.

Best regards,
EGK Team
        ";
    }

    /**
     * Generate a 6-digit verification code
     */
    public static function generateCode(int $length = 6): string
    {
        return str_pad((string) rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}

