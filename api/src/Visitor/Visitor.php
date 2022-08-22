<?php

declare(strict_types=1);

namespace App\Visitor;

interface Visitor
{
    public function normalizeLotMessage(string $message, ?string $name = null, ?string $userhandle = null, ?int $score = null): string;
    public function normalizeTweetReplyMessage(string $message, ?string $name = null, ?string $userhandle = null, ?string $gameLink = null): string;
}
