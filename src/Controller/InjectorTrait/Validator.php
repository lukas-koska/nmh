<?php

declare(strict_types=1);

namespace App\Controller\InjectorTrait;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait Validator
{
    protected DenormalizerInterface $denormalizer;

    protected ValidatorInterface $validator;

    #[Required]
    public function setDenormalizerInterface(DenormalizerInterface $denormalizerInterface): void
    {
        $this->denormalizer = $denormalizerInterface;
    }

    #[Required]
    public function setValidatorInterface(ValidatorInterface $validatorInterface): void
    {
        $this->validator = $validatorInterface;
    }
}
