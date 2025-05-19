<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EmailApiService
{
    protected $httpClient;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->httpClient = new Client();
        // Ambil API key dan URL dari konfigurasi (misalnya .env)
        $this->apiKey = env('API_EMAIL_KEY');
        $this->apiUrl = env('API_EMAIL_URL');
    }

    public function sendEmail(string $to, string $subject, string $body, string $from, string $fromName): bool
    {
        try {
            $response = $this->httpClient->post($this->apiUrl, [
    'headers' => [
        'Content-Type' => 'application/x-www-form-urlencoded',
    ],
    'form_params' => [
        'api_key' => $this->apiKey,
        'to' => $to,
        'subject' => $subject,
        'body' => $body,
        'from' => $from,
        'from_name' => $fromName,
    ],
]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($responseBody['status']) && $responseBody['status'] === 'success') {
                Log::info('Email sent successfully via API: ' . $responseBody['message'] ?? '');
                return true;
            } else {
                Log::error('Failed to send email via API: Status Code: ' . $statusCode . ', Response: ' . json_encode($responseBody));
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Error sending email via API: ' . $e->getMessage());
            return false;
        }
    }
}