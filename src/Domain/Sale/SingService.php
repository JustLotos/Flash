<?php

declare(strict_types=1);

namespace App\Domain\Sale;

class SingService
{
    public function getSign($requestData): string {
        unset($requestData['ik_sign']);
        ksort($requestData, SORT_STRING);
        array_push($requestData, 'WsePzUygiBVW68NU');
        $singString = implode(':', $requestData);
        return base64_decode(md5($singString, true));
    }

    public function checkService($requestData): bool {
        return $requestData['ik_sign'] === $this->getSign($requestData);
    }
}