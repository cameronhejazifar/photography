<?php

namespace App\Models;

use App\Abstracts\ImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhotographEdit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $photograph_id
 * @property string $scaled_size
 * @property string $disk
 * @property string $directory
 * @property string $filename
 * @property string $filetype
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereDirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereFiletype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit wherePhotographId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereScaledSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereUserId($value)
 * @property-read \App\Models\Photograph $photograph
 * @property-read \App\Models\User $user
 * @property int $original_width
 * @property int $original_height
 * @property int $scaled_width
 * @property int $scaled_height
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereOriginalHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereOriginalWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereScaledHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographEdit whereScaledWidth($value)
 */
class PhotographEdit extends ImageModel
{
    use HasFactory;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Get the User this edit belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Photograph this edit belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }
}
