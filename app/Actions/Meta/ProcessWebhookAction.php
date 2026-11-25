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
            fn ($component) => strtoupper($component['type'] ?? '') === 'BODY'
        );

        $bodyText = $body['text'] ?? '';

        preg_match_all('/\{\{(\d+)\}\}/', $bodyText, $matches);

        $placeholderNumbers = array_values(array_unique(
            array_map('intval', $matches[1] ?? [])
        ));

        sort($placeholderNumbers);

        if ($placeholderNumbers === []) {
            return [];
        }

        $saved = $message->template_variables ?? [];

        if (! is_array($saved)) {
            $saved = [];
        }

        $variables = [];

        foreach ($placeholderNumbers as $number) {
            $value = $this->savedValue($saved, $number);

            if ($value === null) {
                $value = match ($number) {
                    1 => $message->contact?->name,
                    2 => $message->contact?->phone_number,
                    default => null,
                };
            }

            if ($value === null || trim((string) $value) === '') {
                throw ValidationException::withMessages([
                    'template_variables' => "Template variable {{$number}} is missing.",
                ]);
            }

            $variables[] = (string) $value;
        }

        return $variables;
    }

    protected function savedValue(array $saved, int $number): mixed
    {
        // Supports both [value1, value2] and ["1" => value1] formats.
        if (array_key_exists((string) $number, $saved)) {
            return $saved[(string) $number];
        }

        if (array_key_exists($number - 1, $saved)) {
            return $saved[$number - 1];
        }

        return null;
    }
}
