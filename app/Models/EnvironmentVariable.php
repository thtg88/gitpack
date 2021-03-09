<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentVariable extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'name',
        'value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'app_id' => 'integer',
    ];

    // RELATIONSHIPS

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
