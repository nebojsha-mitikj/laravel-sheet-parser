<?php

namespace NebboO\LaravelSheetParser\Interfaces;

use Illuminate\Support\Collection;

interface ParserInterface
{
    public function toArray(): array;
    public function toJson(): string;
    public function toCollection(): Collection;
}