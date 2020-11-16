<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

abstract class Factory extends BaseFactory
{
    /**
     * Indicate a soft-deleted model.
     *
     * @return self
     */
    public function softDeleted(): self
    {
        return $this->state([
            'deleted_at' => now()->toDateTimeString(),
        ]);
    }
}
