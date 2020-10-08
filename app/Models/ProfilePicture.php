<?php

namespace App\Models;

use App\Abstracts\ImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\ProfilePicture
 *
 * @property int $id
 * @property int $user_id
 * @property string $image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProfilePicture withoutTrashed()
 * @property string $disk
 * @property string $directory
 * @property string $filename
 * @property string $filetype
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereDirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfilePicture whereFiletype($value)
 */
class ProfilePicture extends ImageModel
{
    use HasFactory;

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
