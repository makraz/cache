<?php

declare(strict_types=1);

namespace Healios\Cache\Query;

interface QueryBuilderInterface
{
    public function resource(string $type): self;

    public function with(string $field, string $value): self;

    public function set(mixed $value): void;

    public function get(): mixed;

    public function getQuery(): Query;
}
