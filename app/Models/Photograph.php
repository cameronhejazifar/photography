<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Photograph
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property string $location
 * @property string $description
 * @property string $tags
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereUpdatedAt($value)
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereUserId($value)
 * @property string $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereStatus($value)
 * @property string|null $google_drive_file_id
 * @method static \Illuminate\Database\Eloquent\Builder|Photograph whereGoogleDriveFileId($value)
 */
class Photograph extends Model
{
    use HasFactory;

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

    /**
     * Returns all photograph edits that belong to the user.
     *
     * @param null $scaledSize
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photographEdits($scaledSize = null)
    {
        $query = $this->hasMany(PhotographEdit::class);
        if ($scaledSize) {
            $query = $query->where('scaled_size', $scaledSize);
        }
        return $query;
    }
}
