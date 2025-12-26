<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

class EmailHelper
{
    /**
     * Send verification code to email
     */
    public static function sendVerificationCode(string $email, string $code, string $name = null): bool
    {
        try {
            $subject = 'Email Verification Code';
            $htmlMessage = self::getVerificationEmailTemplate($code, $name);
            $textMessage = self::getVerificationEmailTextTemplate($code, $name);

            Mail::send([], [], function (Message $mail) use ($email, $subject, $htmlMessage, $textMessage) {
                $mail->to($email)
                     ->subject($subject)
                     ->from(config('mail.from.address'), config('mail.from.name'))
                     ->replyTo(config('mail.from.address'), config('mail.from.name'))
                     ->html($htmlMessage)
                     ->text($textMessage);
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
            $htmlMessage = self::getPasswordResetEmailTemplate($code, $name);
            $textMessage = self::getPasswordResetEmailTextTemplate($code, $name);

            Mail::send([], [], function (Message $mail) use ($email, $subject, $htmlMessage, $textMessage) {
                $mail->to($email)
                     ->subject($subject)
                     ->from(config('mail.from.address'), config('mail.from.name'))
                     ->replyTo(config('mail.from.address'), config('mail.from.name'))
                     ->html($htmlMessage)
                     ->text($textMessage);
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
     * Get verification email template (HTML)
     */
    private static function getVerificationEmailTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? htmlspecialchars($name) : 'there';
        $appName = config('mail.from.name', 'EGK');

        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email Verification Code</title>
</head>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
    <div style='background-color: #f8f9fa; padding: 30px; border-radius: 8px;'>
        <h2 style='color: #2c3e50; margin-top: 0;'>Hello {$greeting}!</h2>

        <p>Thank you for registering with {$appName}!</p>

        <div style='background-color: #ffffff; border: 2px solid #3498db; border-radius: 6px; padding: 20px; text-align: center; margin: 20px 0;'>
            <p style='margin: 0; font-size: 14px; color: #666;'>Your verification code is:</p>
            <p style='margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #3498db; letter-spacing: 5px;'>{$code}</p>
        </div>

        <p style='color: #e74c3c; font-size: 14px;'><strong>This code will expire in 10 minutes.</strong></p>

        <p style='color: #7f8c8d; font-size: 12px; margin-top: 30px; border-top: 1px solid #ecf0f1; padding-top: 20px;'>
            If you didn't request this code, please ignore this email.
        </p>

        <p style='margin-top: 20px;'>
            Best regards,<br>
            <strong>{$appName} Team</strong>
        </p>
    </div>
</body>
</html>
        ";
    }

    /**
     * Get verification email template (Plain Text)
     */
    private static function getVerificationEmailTextTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? "Hello {$name}," : "Hello,";
        $appName = config('mail.from.name', 'EGK');

        return "
{$greeting}

Thank you for registering with {$appName}!

Your verification code is: {$code}

This code will expire in 10 minutes.

If you didn't request this code, please ignore this email.

Best regards,
{$appName} Team
        ";
    }

    /**
     * Get password reset email template (HTML)
     */
    private static function getPasswordResetEmailTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? htmlspecialchars($name) : 'there';
        $appName = config('mail.from.name', 'EGK');

        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset Code</title>
</head>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
    <div style='background-color: #f8f9fa; padding: 30px; border-radius: 8px;'>
        <h2 style='color: #2c3e50; margin-top: 0;'>Hello {$greeting}!</h2>

        <p>You requested to reset your password.</p>

        <div style='background-color: #ffffff; border: 2px solid #e74c3c; border-radius: 6px; padding: 20px; text-align: center; margin: 20px 0;'>
            <p style='margin: 0; font-size: 14px; color: #666;'>Your password reset code is:</p>
            <p style='margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #e74c3c; letter-spacing: 5px;'>{$code}</p>
        </div>

        <p style='color: #e74c3c; font-size: 14px;'><strong>This code will expire in 10 minutes.</strong></p>

        <p style='color: #7f8c8d; font-size: 12px; margin-top: 30px; border-top: 1px solid #ecf0f1; padding-top: 20px;'>
            If you didn't request this, please ignore this email.
        </p>

        <p style='margin-top: 20px;'>
            Best regards,<br>
            <strong>{$appName} Team</strong>
        </p>
    </div>
</body>
</html>
        ";
    }

    /**
     * Get password reset email template (Plain Text)
     */
    private static function getPasswordResetEmailTextTemplate(string $code, ?string $name): string
    {
        $greeting = $name ? "Hello {$name}," : "Hello,";
        $appName = config('mail.from.name', 'EGK');

        return "
{$greeting}

You requested to reset your password.

Your password reset code is: {$code}

This code will expire in 10 minutes.

If you didn't request this, please ignore this email.

Best regards,
{$appName} Team
        ";
    }

    /**
     * Generate a 6-digit verification code
     * In development/staging: returns "111111" for easier testing
     * In production: generates a random 6-digit code between 100000 and 999999
     */
    public static function generateCode(int $length = 6): string
    {
        // In development or staging, return fixed code for easier testing
        $env = config('app.env', 'production');
        if (in_array($env, ['local', 'development', 'dev', 'staging', 'testing'])) {
            return str_repeat('1', $length); // Returns "111111" for 6 digits
        }

        // In production, generate a random number between 100000 and 999999 (ensures 6 digits, no leading zeros)
        $min = pow(10, $length - 1); // 100000
        $max = pow(10, $length) - 1; // 999999
        return (string) random_int($min, $max);
    }
}

