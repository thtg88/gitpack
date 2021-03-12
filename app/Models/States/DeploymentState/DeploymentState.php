<?php

namespace App\Models\States\DeploymentState;

use Spatie\ModelStates\State;

abstract class DeploymentState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Started::class)
            ->allowTransition(Started::class, Succeeded::class)
            ->allowTransition(Started::class, Failed::class)
        ;
    }

    abstract public function name(): string;
}
