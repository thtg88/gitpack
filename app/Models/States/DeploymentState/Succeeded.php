<?php

namespace App\Models\States\DeploymentState;

final class Succeeded extends DeploymentState
{
    public function name(): string
    {
        return 'Succeeded';
    }
}
