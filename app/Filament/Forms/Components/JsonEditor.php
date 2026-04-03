<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;

/**
 * Custom JSON Editor component for Filament.
 * Extends the native CodeEditor to provide built-in support for JSON columns.
 */
class JsonEditor extends CodeEditor
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->language(Language::Json);
        $this->columnSpanFull();

        // Validate that the input is valid JSON
        $this->rule(function () {
            return function (string $attribute, mixed $value, \Closure $fail) {
                if (is_string($value) && !blank($value)) {
                    json_decode($value);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail("The {$this->getLabel()} must be a valid JSON string.");
                    }
                }
            };
        });

        // Format array data from database for the UI
        $this->formatStateUsing(function ($state) {
            if (is_string($state)) {
                $decoded = json_decode($state, associative: true);
                return json_last_error() === JSON_ERROR_NONE ? json_encode($decoded, JSON_PRETTY_PRINT) : $state;
            }
            return is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state;
        });

        // Handle saving back as an array
        $this->dehydrateStateUsing(function (?string $state) {
            if (blank($state)) return null;
            $decoded = json_decode($state, associative: true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $state;
        });

        // Keep Livewire state in sync
        $this->afterStateUpdated(function (?string $state, $set, $component) {
            $set($component->getStatePath(), $state ? json_decode($state, associative: true) : null);
        });
    }
}
