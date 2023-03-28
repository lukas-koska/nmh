<?php

declare(strict_types=1);

namespace App\Controller\InjectorTrait;

trait CustomJsonResponse
{

    public function returnJsonReponse(bool $success, ?array $data, ?string $message, ?array $additionData = []): void
    {
        // This method should return json response in case if success or errors
    }
}
