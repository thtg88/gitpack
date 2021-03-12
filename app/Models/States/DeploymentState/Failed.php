<?php

namespace App\Models\States\DeploymentState;

final class Failed extends DeploymentState
{
    public function name(): string
    {
        return 'Failed';
    }
}
