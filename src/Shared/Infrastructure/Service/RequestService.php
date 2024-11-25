<?php

namespace App\Shared\Infrastructure\Service;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

final class RequestService
{
    public static function getField(Request $request, string $fieldName, bool $isRequired = true, bool $isArray = false): mixed
    {
        $requestData = \json_decode($request->getContent(), true);

        if ($isArray) {
            $arrayData = self::arrayFlatten($requestData);

            foreach ($arrayData as $key => $value) {
                if ($fieldName === $key) {
                    return $value;
                }
            }

            if ($isRequired) {
                throw new InvalidArgumentException(\sprintf('The field %s is required!', $fieldName));
            }

            return null;
        }

        if (\array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }

        if ($isRequired) {
            throw new InvalidArgumentException(\sprintf('The field %s is required!', $fieldName));
        }

        return null;
    }

    public static function arrayFlatten(array $array): array
    {
        $return = [];

        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $return = \array_merge($return, self::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}