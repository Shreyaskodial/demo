<?php

namespace App\Services;

use GuzzleHttp\Client;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;

class BrevoMailService
{
    protected $apiInstance;

    public function __construct()
    {
        $apiKey = env('BREVO_API_KEY');

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);

        // Guzzle client works with older Sendinblue SDK
        $guzzleClient = new Client();

        $this->apiInstance = new TransactionalEmailsApi($guzzleClient, $config);
    }

    public function sendWelcomeEmail($user)
    {
        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => 'user',
            'sender' => ['name' => 'Your App', 'email' => 'bricklysolution@gmail.com'],
            'to' => [[ 'email' => $user->email, 'name' => $user->name ]],
            'htmlContent' => "<h1>Hello {$user->name}!</h1><p>Thanks for registering on our platform.</p>"
        ]);

        try {
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            dd('Email sent successfully!', ['result' => $result]);
        } catch (\Exception $e) {
            dd('Email sending failed: ' . $e->getMessage());
            
        }
    }
}
