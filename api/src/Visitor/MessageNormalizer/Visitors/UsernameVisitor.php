<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class UsernameVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%@joueur%') && array_key_exists('username', $params) && is_string($params['username']) && trim($params['username']) !== '';
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%@joueur%', '@'.str_replace('@', '', $params['username']), $message);
    }
}
