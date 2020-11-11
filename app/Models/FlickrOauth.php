<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FlickrOauth
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $flickr_nsid Flickr User ID
 * @property string|null $flickr_name
 * @property string|null $flickr_username
 * @property string|null $request_token
 * @property string|null $request_token_secret
 * @property string|null $request_token_verifier
 * @property string|null $access_token
 * @property string|null $access_token_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereAccessTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereFlickrName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereFlickrNsid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereFlickrUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereRequestToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereRequestTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereRequestTokenVerifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlickrOauth whereUserId($value)
 * @mixin \Eloquent
 */
class FlickrOauth extends Model
{
    use HasFactory;

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
     * Returns all Flickr Posts that belong to the OAuth.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flickrPosts()
    {
        return $this->hasMany(FlickrPost::class);
    }
}
