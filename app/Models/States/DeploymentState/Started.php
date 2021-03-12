<?php

namespace App\Models\States\DeploymentState;

final class Started extends DeploymentState
{
    public function name(): string
    {
        return 'Started';
    }
}
