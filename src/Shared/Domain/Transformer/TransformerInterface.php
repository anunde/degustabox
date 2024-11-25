<?php

namespace App\Shared\Domain\Transformer;

interface TransformerInterface
{
    public function transform($item): array;
}