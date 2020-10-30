<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhotographCollection
 *
 * @property int $id
 * @property int $user_id
 * @property int $photograph_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Photograph $photograph
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection wherePhotographId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographCollection whereUserId($value)
 * @mixin \Eloquent
 */
class PhotographCollection extends Model
{
    use HasFactory;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this collection belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Photograph this collection belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }
}
