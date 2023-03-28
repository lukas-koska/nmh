<?php

declare(strict_types=1);

namespace App\Controller\InjectorTrait;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait CustomJsonResponse
{

    #[Required]
    public function returnJsonReponse(bool $success, ?array $data, ?string $message, ?array $additionData = []): void
    {
        // This method should return json response in case if success or errors
    }
}
