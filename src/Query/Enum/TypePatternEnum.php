<?php

declare(strict_types=1);

namespace Healios\Cache\Query\Enum;

use Error;

enum TypePatternEnum: string
{
    case STUDY = 'studies:{clientId}:{studyId}';

    public static function fromName(string $name): self
    {
        $name = \strtoupper($name);

        try {
        $value = \constant("self::$name");
        } catch (Error) {
            throw new \ValueError(sprintf(
                '"%s" is not a valid name value for enum %s',
                $name,
                self::class
            ));
        }

        return $value;
    }
}
