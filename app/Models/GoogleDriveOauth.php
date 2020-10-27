<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GoogleDriveOauth
 *
 * @property int $id
 * @property string $auth_code
 * @property string $access_token
 * @property string $refresh_token
 * @property string $scope
 * @property string $token_type
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereAuthCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereTokenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereUserId($value)
 * @property string $token
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereToken($value)
 * @property int $refresh
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleDriveOauth whereRefresh($value)
 */
class GoogleDriveOauth extends Model
{
    use HasFactory;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the User this oauth belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
