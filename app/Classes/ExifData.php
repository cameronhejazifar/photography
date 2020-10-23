<?php

namespace App\Classes;

use PHPExif\Exif;
use PHPExif\Reader\Reader;

class ExifData
{
    /**
     * Reads the exif data from a file.
     *
     * @param $filePath
     * @return ExifData
     */
    public static function read($filePath)
    {
        $reader = Reader::factory(Reader::TYPE_NATIVE);
        return new ExifData($reader->read($filePath));
    }

    /** @var Exif */
    private $exif;

    /**
     * ExifData constructor.
     *
     * @param $exif Exif
     */
    private function __construct($exif)
    {
        $this->exif = $exif;
    }

    public function camera()
    {
        if (!$this->exif) {
            return null;
        }
        return $this->exif->getCamera();
    }

    public function focalLength()
    {
        if (!$this->exif) {
            return null;
        }
        return round($this->exif->getFocalLength()) . 'mm';
    }

    public function exposureTime()
    {
        if (!$this->exif) {
            return null;
        }
        return $this->exif->getExposure() . '"';
    }

    public function aperture()
    {
        if (!$this->exif) {
            return null;
        }
        return $this->exif->getAperture();
    }

    public function iso()
    {
        if (!$this->exif) {
            return null;
        }
        return (string)$this->exif->getIso();
    }
}
