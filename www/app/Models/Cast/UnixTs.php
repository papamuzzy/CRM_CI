<?php

namespace App\Models\Cast;

use CodeIgniter\DataCaster\Cast\BaseCast;
use InvalidArgumentException;

class UnixTs extends BaseCast {
    public static function get(
        mixed $value,
        array $params = [],
        ?object $helper = null
    ): int {
        if (! is_string($value)) {
            self::invalidTypeValueError($value);
        }

        $decoded = strtotime($value);

        if ($decoded === false) {
            throw new InvalidArgumentException('Cannot decode: ' . $value);
        }

        return $decoded;
    }

    public static function set(
        mixed $value,
        array $params = [],
        ?object $helper = null
    ): string {
        if (! is_int($value)) {
            self::invalidTypeValueError($value);
        }

        return date('Y-m-d H:i:s' , $value);
    }
}