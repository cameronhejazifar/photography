<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProfileIcon
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $image_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereImageData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon withoutTrashed()
 */
class ProfileIcon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this icon belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
