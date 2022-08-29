<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer\Visitors;

use App\Visitor\VisitorInterface\MessageNormalizerVisitor;

class WebsiteVisitor implements MessageNormalizerVisitor
{
    public function accept(string $message, array $params = []): bool
    {
        return str_contains($message, '%site_web%') && \array_key_exists('site_web', $params) && \is_string($params['site_web']) && '' !== trim($params['site_web']);
    }

    public function normalize(string $message, array $params): string
    {
        return str_replace('%site_web%', $params['site_web'], $message);
    }
}
