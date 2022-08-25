<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class ScoreVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%score%') && array_key_exists('score', $params) && is_string($params['score']) && trim($params['score']) !== '';
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%score%', $params['score'], $message);
    }
}
