<?php

declare(strict_types=1);

namespace App\Visitor;

class MessageNormalizer implements Visitor
{
    private function normalizeMessageForNameAndUserhandle(string $message, ?string $name = null, ?string $userhandle = null): string
    {
        switch(!null) {
            case $name:
                $message = str_replace('%nom%', $name, $message);
            case $userhandle:
                $message = str_replace('%@joueur%', '@'.str_replace('@', '', $userhandle), $message);
            default:
                return $message;
        }
    }

    public function normalizeLotMessage(string $message, ?string $name = null, ?string $userhandle = null, ?int $score = null): string
    {
        $message = $this->normalizeMessageForNameAndUserhandle($message, $name, $userhandle);

        return str_replace('%score%', null !== $score ? (string) $score : '0', $message);
    }

    public function normalizeTweetReplyMessage(string $message, ?string $name = null, ?string $userhandle = null, ?string $gameLink = null): string
    {
        $message = $this->normalizeMessageForNameAndUserhandle($message, $name, $userhandle);

        return str_replace('%site_web%', $gameLink ?? 'aucun lien trouvé', $message);
    }
}
