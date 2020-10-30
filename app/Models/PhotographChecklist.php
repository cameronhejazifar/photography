<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhotographChecklist
 *
 * @property-read \App\Models\Photograph $photograph
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $photograph_id
 * @property string $instruction
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereInstruction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist wherePhotographId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereUserId($value)
 * @property int $completed
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereCompleted($value)
 * @property int $sequence_number
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographChecklist whereSequenceNumber($value)
 */
class PhotographChecklist extends Model
{
    use HasFactory;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this checklist belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Photograph this checklist belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }
}
