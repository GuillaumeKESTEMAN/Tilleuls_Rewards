<?php

declare(strict_types=1);

namespace App\Visitor\MessageNormalizer;

use App\Visitor\MessageNormalizer\Visitors\NameVisitor;
use App\Visitor\MessageNormalizer\Visitors\ScoreVisitor;
use App\Visitor\MessageNormalizer\Visitors\UsernameVisitor;
use App\Visitor\MessageNormalizer\Visitors\WebsiteVisitor;

class MessageNormalizer
{
    private array $visitors = [];

    public function __construct()
    {
        $this->visitors[] = new NameVisitor();
        $this->visitors[] = new UsernameVisitor();
        $this->visitors[] = new ScoreVisitor();
        $this->visitors[] = new WebsiteVisitor();
    }

    public function normalize(string $message, array $params = []): string
    {
        foreach ($this->visitors as $visitor) {
            if ($visitor->accept($message, $params)) {
                $message = $visitor->normalize($message, $params);
            }
        }

        return $message;
    }

}
