<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\GitRemoteRepository\VerifyShaAction;
use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Deployment;
use App\Models\States\DeploymentState\Failed;
use App\Models\States\DeploymentState\Started;
use App\Models\States\DeploymentState\Succeeded;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\ModelStates\Exceptions\TransitionNotFound;

class DeploymentController extends Controller
{
    public function failCurrent(Request $request, App $app)
    {
        $request->validate(['sha' => 'required|string|sha']);

        $sha = $request->get('sha');

        $deployment = Deployment::where([
            'app_id' => $app->id,
            'sha' => $sha,
            'state' => Started::$name,
        ])->firstOrFail();

        $this->verfiyShaOrFail($app, $sha);

        try {
            $deployment->state->transitionTo(Failed::class);
        } catch (TransitionNotFound $e) {
            abort(404);
        }

        return response()->json(['success' => true]);
    }

    public function start(Request $request, App $app)
    {
        $request->validate([
            'email' => 'required|string|email',
            'sha' => 'required|string|sha',
        ]);

        $input = $request->only('email', 'sha');

        $user = User::where('email', $input['email'])->firstOrFail();

        // check there isn't an existing deployment ongoing
        $deployment = Deployment::firstWhere([
            'app_id' => $app->id,
            'state' => Started::$name,
        ]);
        if ($deployment !== null) {
            abort(403, 'Please wait for the previous deployment to finish.');
        }

        $this->verfiyShaOrFail($app, $input['sha']);

        Deployment::create([
            'app_id' => $app->id,
            'sha' => $input['sha'],
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function succeedCurrent(Request $request, App $app)
    {
        $request->validate(['sha' => 'required|string|sha']);

        $sha = $request->get('sha');

        $deployment = Deployment::where([
            'app_id' => $app->id,
            'sha' => $sha,
            'state' => Started::$name,
        ])->firstOrFail();

        $this->verfiyShaOrFail($app, $sha);

        try {
            $deployment->state->transitionTo(Succeeded::class);
        } catch (TransitionNotFound $e) {
            abort(404);
        }

        return response()->json(['success' => true]);
    }

    private function verfiyShaOrFail(App $app, string $sha): string
    {
        $action = new VerifyShaAction($app, $sha);

        $is_valid_sha = $action();

        if (! $is_valid_sha) {
            abort(404);
        }

        return $sha;
    }
}
