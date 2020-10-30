<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhotographOtherFile
 *
 * @property int $id
 * @property int $user_id
 * @property int $photograph_id
 * @property string $other_type
 * @property string $filename
 * @property string $filetype
 * @property string $google_drive_file_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Photograph $photograph
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereFiletype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereGoogleDriveFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereOtherType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile wherePhotographId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereUserId($value)
 * @mixin \Eloquent
 * @property string $camera
 * @property string $lens
 * @property string $filter
 * @property string $focal_length
 * @property string $exposure_time
 * @property string $aperture
 * @property string $iso
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereAperture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereCamera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereExposureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereFocalLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographOtherFile whereLens($value)
 */
class PhotographOtherFile extends Model
{
    use HasFactory;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this file belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Photograph this file belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }
}
