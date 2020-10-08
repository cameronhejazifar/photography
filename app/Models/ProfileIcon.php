<?php

namespace App\Models;

use App\Abstracts\ImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProfileIcon
 *
 * @property int $id
 * @property int $user_id
 * @property string $image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileIcon whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfileIcon withoutTrashed()
 */
class ProfileIcon extends ImageModel
{
    use HasFactory, SoftDeletes;

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
