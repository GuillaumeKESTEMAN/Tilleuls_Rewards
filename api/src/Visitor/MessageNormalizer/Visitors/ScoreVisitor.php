<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class ScoreVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%score%') && \array_key_exists('score', $params) && \is_int($params['score']) && $params['score'] >= 0;
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%score%', (string) $params['score'], $message);
    }
}
