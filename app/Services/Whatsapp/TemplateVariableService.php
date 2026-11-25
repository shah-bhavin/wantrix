<?php

namespace App\Services\Whatsapp;

use App\Models\Message;
use Illuminate\Validation\ValidationException;

class TemplateVariableService
{
    public function resolve(Message $message): array
    {
        $message->loadMissing([
            'whatsappTemplate',
            'contact',
        ]);

        $template = $message->whatsappTemplate;

        if (! $template) {
            throw ValidationException::withMessages([
                'whatsapp_template_id' => 'WhatsApp template is missing.',
            ]);
        }

        $components = $template->components ?? [];

        $body = collect($components)->first(
            fn ($component) =>
                strtoupper($component['type'] ?? '') === 'BODY'
        );

        $bodyText = (string) data_get($body, 'text', '');

        preg_match_all('/\{\{\s*(\d+)\s*\}\}/', $bodyText, $matches);

        $indexes = collect($matches[1] ?? [])
            ->map(fn ($index) => (int) $index)
            ->unique()
            ->sort()
            ->values();

        if ($indexes->isEmpty()) {
            return [];
        }

        $maxIndex = (int) $indexes->max();

        // Meta BODY parameters must be supplied in placeholder order.
        $variables = $message->template_variables ?? [];

        if (! is_array($variables)) {
            $variables = [];
        }

        $contact = $message->contact;

        $resolved = [];

        for ($index = 1; $index <= $maxIndex; $index++) {
            $value = $this->getSavedValue($variables, $index);

            if ($value === null || $value === '') {
                $value = $this->getContactFallback($contact, $index);
            }

            if ($value === null || trim((string) $value) === '') {
                throw ValidationException::withMessages([
                    'template_variables' =>
                        "Template variable {{$index}} is missing. Please provide its value.",
                ]);
            }

            $resolved[] = [
                'type' => 'text',
                'text' => (string) $value,
            ];
        }

        return $resolved;
    }

    protected function getSavedValue(array $variables, int $index): mixed
    {
        // Supports both [value1, value2] and ["1" => value1, "2" => value2].
        if (array_key_exists((string) $index, $variables)) {
            return $variables[(string) $index];
        }

        if (array_key_exists($index, $variables)) {
            return $variables[$index];
        }

        return null;
    }

    protected function getContactFallback($contact, int $index): ?string
    {
        if (! $contact) {
            return null;
        }

        return match ($index) {
            1 => $contact->name,
            2 => $contact->phone_number,
            default => null,
        };
    }
}
