<?php

class ApiClient
{
    private string $baseUrl;
    private string $token;

    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token   = $token;
    }

    /**
     * Execute an HTTP request to the JoomShopping API.
     *
     * @param string     $method GET|POST|PATCH|DELETE
     * @param string     $path   e.g. /manufacturers or /manufacturers/5
     * @param array|null $body   JSON body for POST/PATCH
     * @return array Decoded response data
     * @throws RuntimeException on HTTP errors
     */
    public function request(string $method, string $path, ?array $body = null): array
    {
        $url = $this->baseUrl . $path;

        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body ?? []));
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body ?? []));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            // GET is the default
        }

        $response   = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new RuntimeException("cURL error: {$curlError}");
        }

        // 204 No Content — successful delete
        if ($httpCode === 204) {
            return ['deleted' => true];
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            $message = $decoded['errors'][0]['title'] ?? $decoded['message'] ?? $response;
            throw new RuntimeException("API error {$httpCode}: {$message}");
        }

        return $decoded ?? [];
    }
}
