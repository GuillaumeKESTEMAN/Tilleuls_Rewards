<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class UserhandleVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%@joueur%') && array_key_exists('joueur', $params) && is_string($params['joueur']) && trim($params['joueur']) !== '';
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%@joueur%', '@'.str_replace('@', '', $params['joueur']), $message);
    }
}
