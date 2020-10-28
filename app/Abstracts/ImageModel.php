<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Image;
use Storage;

/**
 * Interface ImageModel
 * @package App\Interfaces
 *
 * @property string disk
 * @property string directory
 * @property string filename
 * @property string filetype
 */
abstract class ImageModel extends Model
{
    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * Saves the image to Storage, using the model's 'disk', 'directory', and 'filename'.
     *
     * @param Image $image
     * @param string $filetype
     * @return bool
     */
    public function storeImage(Image $image)
    {
        // Create the directory
        $disk = Storage::disk($this->disk);
        $disk->makeDirectory($this->directory);

        // Save the file
        $path = "{$this->directory}/{$this->filename}";
        return $disk->put($path, $image->encode($this->filetype));
    }

    /**
     * Returns the contents of the image file.
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getImage()
    {
        $disk = Storage::disk($this->disk);
        return $disk->get("{$this->directory}/{$this->filename}");
    }

    /**
     * Returns the path of the image file.
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getImagePath()
    {
        return Storage::path("{$this->disk}/{$this->directory}/{$this->filename}");
    }

    /**
     * Builds the URL to the image that can be accessed from the web.
     *
     * @return string
     */
    public function imageURL()
    {
        $url = Storage::disk($this->disk)->url("{$this->directory}/{$this->filename}");
        return $url;
    }
}
