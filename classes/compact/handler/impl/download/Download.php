<?php
namespace compact\handler\impl\download;

use compact\filesystem\exceptions\FileNotFoundException;
use compact\filesystem\Mimetype;

class Download
{
    /**
     * @var \SplFileInfo The file to be downloaded
     */
    private $file;

    /**
     *
     * @var string The filename to serve the user when downloading
     */
    private $downloadFilename;

    /**
     * Constructor
     *
     * @param \SplFileInfo $aFile            
     * @param string $aFilename
     *            The filename under which to present the download to the user. Omit to use the real filename.
     */
    public function __construct(\SplFileInfo $aFile, $aFilename = null)
    {
        if (! $aFile->isFile()) {
            throw new FileNotFoundException($aFile);
        }
        $this->file = $aFile;
        if ($aFilename === null) {
            $this->downloadFilename = $aFile->getFilename();
        } else {
            $this->downloadFilename = $aFilename;
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the filename of the downloaded file to be presented to the user
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->downloadFilename;
    }

    /**
     * Returns the mimetype of the download
     * @return string
     */
    public function getMimeType()
    {
        return Mimetype::get()->getType($this->file);
    }
}