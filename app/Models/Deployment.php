<?php

namespace App\Models;

use App\Models\States\DeploymentState\DeploymentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

class Deployment extends Model
{
    use HasFactory;
    use HasStates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'sha',
        'state',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'app_id' => 'integer',
        'state' => DeploymentState::class,
        'user_id' => 'integer',
    ];

    // RELATIONSHIPS

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journal_entries(): MorphMany
    {
        return $this->morphMany(JournalEntry::class, 'target');
    }
}
