<?php

namespace App\Classes;

use App\Models\GoogleDriveOauth;
use Auth;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

/**
 * Class GoogleDrive
 * @package App\Classes
 *
 */
class GoogleDrive
{

    /** @var GoogleDriveOauth */
    private $oauth;

    /** @var Google_Client */
    private $client;

    /** @var Google_Service_Drive */
    private $google;

    public function __construct()
    {
        $this->oauth = Auth::user()->googleDriveOauth()->latest()->first();
        if ($this->isAuthed()) {
            $this->client = app()->make(Google_Client::class);
            $this->client->setAccessToken(json_decode($this->oauth->token, true));
            $this->google = new Google_Service_Drive($this->client);
        }
    }

    public function isAuthed()
    {
        return $this->oauth && !$this->oauth->expires_at->isPast();
    }

    private function escape($q)
    {
        $q = str_replace("\\", "\\\\", $q);
        $q = str_replace("'", "\\'", $q);
        return $q;
    }

    /**
     * Creates the directory structure of the given path in Google Drive.
     *
     * @param $path
     * @return string - the file-id of the deepest directory
     */
    public function mkdirs($path)
    {
        // Convert the full path to an array
        $pathComponents = explode('/', trim($path, '/'));

        // Start from root
        $parentID = 'root';
        $index = 0;

        // Recursively iterate through directory structure, creating directories if they don't exist
        while ($index < count($pathComponents)) {
            $pathComponents[$index] = trim($pathComponents[$index]);
            if (strlen($pathComponents[$index]) > 0) {
                $parentID = $this->mkdir($parentID, $pathComponents[$index]);
            }
            $index++;
        }

        return $parentID;
    }

    /**
     * Creates an individual directory under the given parent directory.
     *
     * @param $parentID
     * @param $dirName
     * @return string - the file-id of the created directory
     */
    public function mkdir($parentID, $dirName)
    {
        // Check if the directory exists
        $files = $this->google->files->listFiles([
            'q' => "'{$this->escape($parentID)}' in parents and name = '{$this->escape($dirName)}' and trashed = false",
            'spaces' => 'drive',
            'pageToken' => null,
            'fields' => 'nextPageToken, files(id, name)',
        ]);

        // Return the directory ID if it was found
        if ($files->count() > 0) {
            $dir = $files->current();
            return $dir->getId();
        }

        // Directory doesn't exist... Create it
        $dir = new Google_Service_Drive_DriveFile();
        $dir->setName($dirName);
        $dir->setMimeType('application/vnd.google-apps.folder');
        $dir->setParents([$parentID]);
        return $this->google->files->create($dir)->getId();
    }

    /**
     * Uploads a file from a path on disk to the designated directory in Google Drive.
     *
     * @param $directoryID
     * @param $name
     * @param $mimeType
     * @param $pathToFile
     * @return string - the file-id of the uploaded file
     */
    public function upload($directoryID, $name, $mimeType, $pathToFile)
    {
        // Delete the file if it already exists
        $files = $this->google->files->listFiles([
            'q' => "'{$this->escape($directoryID)}' in parents and name = '{$this->escape($name)}' and trashed = false",
            'spaces' => 'drive',
            'pageToken' => null,
            'fields' => 'nextPageToken, files(id, name)',
        ]);
        /** @var Google_Service_Drive_DriveFile $file */
        foreach ($files as $file) {
            $result = $this->google->files->delete($file->getId());
        }

        // Upload the new file
        $meta = new Google_Service_Drive_DriveFile([
            'name' => $name,
            'parents' => [$directoryID],
        ]);
        $content = file_get_contents($pathToFile);
        $file = $this->google->files->create($meta, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);
        return $file->id;
    }

    /**
     * Gets the file contents from a file on Google Drive.
     *
     * @param $fileID
     * @return mixed
     */
    public function download($fileID)
    {
        $file = $this->google->files->get($fileID, ['alt' => 'media']);
        return $file->getBody()->getContents();
    }
}
