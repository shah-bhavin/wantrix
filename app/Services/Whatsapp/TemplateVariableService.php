<?php

namespace App\Services\Whatsapp;

use App\Models\Message;

class TemplateVariableService
{
    public function resolve(
        Message $message
    ): array {

        $message->loadMissing(
            'whatsappTemplate'
        );

        $template = $message->whatsappTemplate;

        if (! $template) {
            return [];
        }

        $components = $template->components ?? [];

        $body = collect($components)
            ->firstWhere('type', 'BODY');

        if (! $body) {
            return [];
        }

        $text = $body['text'] ?? '';

        preg_match_all(
            '/\{\{(\d+)\}\}/',
            $text,
            $matches
        );

        $count = count($matches[1]);

        return $this->buildVariables(
            $message,
            $count
        );
    }

    protected function buildVariables(
        Message $message,
        int $count
    ): array {

        $variables = [];

        for ($i = 1; $i <= $count; $i++) {

            $variables[] = match ($i) {

                1 => $message->contact->name,

                2 => $message->contact->phone_number,

                default => '',
            };
        }

        return $variables;
    }
}
