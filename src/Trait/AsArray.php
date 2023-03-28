<?php

declare(strict_types=1);

namespace App\Trait;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Trait for outputting class properties as array
 *
 * This trait is able to get all properties of object ( with ability to define exclude )
 * and return them as array. Helpful when trying to get all properties at once as array.
 */
trait AsArray
{
    /**
     * Convert object to array
     *
     * @return array<int|string, int|float|string|bool|null>
     */
    public function toArray(bool $snakeCase = false): array
    {
        $fieldsToExclude = property_exists($this, 'exclude') ? $this->exclude : [];

        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];

        if ($snakeCase) {
            $normalizer = [new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())];
        }

        $serializer = new Serializer($normalizer, $encoder);

        $json = $serializer->serialize(
            $this,
            'json',
        );
        $array = json_decode($json);

        foreach ($fieldsToExclude as $exclude) {
            unset($array->{$exclude});
        }

        // Convert object to array...
        $array = is_object($array) ? (array) $array : $array;

        return (is_array($array)) ? $array : [];
    }
}
