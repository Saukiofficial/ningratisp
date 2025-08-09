<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;

class WahaApi
{
    private $baseUrl, $apiKey, $pathUrl, $action;

    public function __construct($baseUrl = null, $apiKey = null)
    {
        $this->baseUrl = $baseUrl ?? env('WAHA_URL');
        $this->apiKey = $apiKey ?? env('WAHA_API_KEY');
    }

    public function request($data = null, $method = 'get')
    {
        $url = $this->baseUrl . $this->getPathUrl();

        if (!empty($data['chatId'])) {
            $data['chatId'] = $data['chatId'] . '@c.us';
        }
        $data['session'] = 'default';

        try {
            $driver = Http::withHeaders(['X-Api-Key' => $this->apiKey]);
            $error = false;

            switch (strtolower($method)) {
                case 'delete':
                    $driver = $driver->delete($url, $data);
                    break;
                case 'put':
                    $driver = $driver->put($url, $data);
                    break;
                case 'patch':
                    $driver = $driver->patch($url, $data);
                    break;
                case 'post':
                    $driver = $driver->post($url, $data);
                    break;

                default:
                    $driver = $driver->get($url, $data);
                    break;
            }

            if ($driver->status() >= 300) {
                throw new Exception($driver->reason());
            }

            $response = $driver->json();
        } catch (Exception $e) {
            $error = true;
            $response = [
                'detail' => 'Exception Request',
                'error' => 500,
                'message' => $e->getMessage(),
            ];
        }

        if (empty($response) || is_string($response) || (!empty($response['error']) && $response['error'] != 500)) {
            $error = true;
            $response = [
                'detail' => 'Empty Response',
                'error' => 400,
                'message' => $response['error']
            ];
        }

        return $response;
    }

    public function setPathUrl(string $path): void
    {
        $this->pathUrl = $path;
    }

    public function getPathUrl(): string
    {
        return $this->pathUrl;
    }

    public function isConnected(): bool
    {
        $this->setPathUrl('/api/sessions');
        $response = $this->request();

        return !empty($response) && isset($response[0]['name']);
    }

    public function sendMessage(string $chatId, string $text)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendText');
        $data = [
            'chatId' => $chatId,
            'text' => $text
        ];

        return $this->request($data, 'post');
    }

    public function sendImage(string $chatId, string $filePath, string $caption = '')
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendImage');
        $data = [
            'chatId' => $chatId,
            'file' => $filePath,
            'caption' => $caption
        ];

        return $this->request($data, 'post');
    }

    public function sendDocument(string $chatId, string $filePath, string $fileName = '')
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendDocument');
        $data = [
            'chatId' => $chatId,
            'file' => $filePath,
            'fileName' => $fileName
        ];

        return $this->request($data, 'post');
    }

    public function sendVideo(string $chatId, string $filePath, string $caption = '')
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendVideo');
        $data = [
            'chatId' => $chatId,
            'file' => $filePath,
            'caption' => $caption
        ];

        return $this->request($data, 'post');
    }

    public function sendContact(string $chatId, string $contactName, string $contactPhone)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendContact');
        $data = [
            'chatId' => $chatId,
            'contactName' => $contactName,
            'contactPhone' => $contactPhone
        ];

        return $this->request($data, 'post');
    }

    public function sendLocation(string $chatId, float $latitude, float $longitude)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/sendLocation');
        $data = [
            'chatId' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        return $this->request($data, 'post');
    }

    public function createGroup(string $groupName, array $participants)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/createGroup');
        $data = [
            'groupName' => $groupName,
            'participants' => $participants
        ];

        return $this->request($data, 'post');
    }

    public function getGroupMembers(string $groupId)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/getGroupMembers');
        $data = [
            'groupId' => $groupId
        ];

        return $this->request($data, 'get');
    }

    public function leaveGroup(string $groupId)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/api/leaveGroup');
        $data = [
            'groupId' => $groupId
        ];

        return $this->request($data, 'post');
    }
}
