<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Traits;

use Illuminate\Support\Collection;

trait HasTransforms
{
    abstract public function toArray(): array;

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

}