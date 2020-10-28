<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FlickrPost
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $tags
 * @property int $is_public
 * @property int $is_friend
 * @property int $is_family
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Photograph $photograph
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereIsFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereIsFriend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property int $photograph_id
 * @property string $image_path
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost wherePhotographId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereUserId($value)
 * @property string|null $flickr_photo_id
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereFlickrPhotoId($value)
 * @property string|null $location
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrPost whereLocation($value)
 */
class FlickrPost extends Model
{
    use HasFactory;

    const SAFETY_LEVEL_SAFE = 1;
    const SAFETY_LEVEL_MODERATE = 2;
    const SAFETY_LEVEL_RESTRICTED = 3;

    const CONTENT_TYPE_PHOTO = 1;
    const CONTENT_TYPE_SCREENSHOT = 2;
    const CONTENT_TYPE_OTHER = 3;

    const SEARCHABLE_GLOBAL = 1;
    const SEARCHABLE_PRIVATE = 2;

    const LOCATION_ACCURACY_WORLD = 1;
    const LOCATION_ACCURACY_COUNTRY = 3;
    const LOCATION_ACCURACY_REGION = 6;
    const LOCATION_ACCURACY_CITY = 11;
    const LOCATION_ACCURACY_STREET = 16;

    const LOCATION_CONTEXT_NOT_DEFINED = 0;
    const LOCATION_CONTEXT_INDOORS = 1;
    const LOCATION_CONTEXT_OUTDOORS = 2;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this oauth belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Photograph this oauth belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }
}
