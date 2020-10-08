<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProfilePicture
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $image_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereImageData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture withoutTrashed()
 */
class ProfilePicture extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this picture belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
