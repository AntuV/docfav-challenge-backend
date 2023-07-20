<?php

namespace App\Controllers;

use JsonSerializable;

class BaseController
{
    /**
     * @param array|JsonSerializable $data
     * @param array $httpHeaders
     * @return string
     */
    protected function sendOutput($data, array $httpHeaders = []): string
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            header('Content-Type: application/json');
            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }
            echo json_encode($data);
            exit;
        }
        return json_encode($data);
    }

    protected function validateRequiredFields($requiredFields, $data): ?string
    {
        $error = false;
        $errorFields = [];
        foreach ($requiredFields as $requiredField) {
            if (!isset($data[$requiredField]) || !strlen($data[$requiredField])) {
                $error = true;
                $errorFields[] = $requiredField;
            }
        }
        if ($error) {
            return $this->sendOutput(['message' => 'Missing required fields: ' . implode(', ', $errorFields)], ['HTTP/1.1 400 Bad Request']);
        }
        return null;
    }

    public function __call($name, $arguments)
    {
        $this->sendOutput(['message' => 'Forbidden'], ['HTTP/1.1 404 Not Found']);
    }
}
