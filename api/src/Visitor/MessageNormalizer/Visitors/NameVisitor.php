<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class NameVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%nom%') && \array_key_exists('nom', $params) && \is_string($params['nom']) && '' !== trim($params['nom']);
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%nom%', $params['nom'], $message);
    }
}
