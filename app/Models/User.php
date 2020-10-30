<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $alias
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $biography
 * @property string|null $photograph_checklist
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBiography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhotographChecklist($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProfileIcon[] $profileIcons
 * @property-read int|null $profile_icons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProfilePicture[] $profilePictures
 * @property-read int|null $profile_pictures_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GoogleDriveOauth[] $googleDriveOauth
 * @property-read int|null $google_drive_oauth_count
 * @property int $active
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photograph[] $photographs
 * @property-read int|null $photographs_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhotographEdit[] $photographEdits
 * @property-read int|null $photograph_edits_count
 * @property string|null $google_drive_dir_edits
 * @property string|null $google_drive_dir_raws
 * @property string|null $google_drive_dir_metas
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleDriveDirEdits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleDriveDirMetas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleDriveDirRaws($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlickrOauth[] $flickrOauth
 * @property-read int|null $flickr_oauth_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlickrPost[] $flickrPosts
 * @property-read int|null $flickr_posts_count
 * @property string|null $nixplay_url
 * @property string|null $fineartamerica_url
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFineartamericaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNixplayUrl($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhotographCollection[] $photographCollections
 * @property-read int|null $photograph_collections_count
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    /**
     * Returns all profile icons associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profileIcons()
    {
        return $this->hasMany(ProfileIcon::class);
    }

    /**
     * Returns all profile icons associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profilePictures()
    {
        return $this->hasMany(ProfilePicture::class);
    }

    /**
     * Returns all google drive OAuth records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function googleDriveOauth()
    {
        return $this->hasMany(GoogleDriveOauth::class);
    }

    /**
     * Returns all google drive OAuth records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flickrOauth()
    {
        return $this->hasMany(FlickrOauth::class);
    }

    /**
     * Returns all photographs that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photographs()
    {
        return $this->hasMany(Photograph::class);
    }

    /**
     * Returns all Flickr Posts that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flickrPosts()
    {
        return $this->hasMany(FlickrPost::class);
    }

    /**
     * Returns all photograph collections that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photographCollections()
    {
        return $this->hasMany(PhotographCollection::class)->orderBy('title', 'asc');
    }

    /**
     * Returns all photograph edits that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photographEdits()
    {
        return $this->hasMany(PhotographEdit::class);
    }

    public function profileIconURL()
    {
        $icon = $this->profileIcons()->latest()->first();
        if ($icon) {
            return $icon->imageURL();
        }
        return asset('img/profile-icon.png');
    }

    public function profilePictureURL()
    {
        $picture = $this->profilePictures()->latest()->first();
        if ($picture) {
            return $picture->imageURL();
        }
        return asset('img/profile-picture.png');
    }
}
