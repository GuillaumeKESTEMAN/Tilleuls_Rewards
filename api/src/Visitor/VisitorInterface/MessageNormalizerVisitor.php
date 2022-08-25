<?php

declare(strict_types=1);

namespace App\Visitor\VisitorInterface;

interface MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool;

    public function normalize(string $message, array $params): string;
}
