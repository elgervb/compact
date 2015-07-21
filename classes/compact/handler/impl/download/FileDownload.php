<?php
namespace compact\handler\impl\download;

use compact\filesystem\exceptions\FileNotFoundException;
use compact\filesystem\Mimetype;

class FileDownload extends Download
{
    /**
     * @var \SplFileInfo The file to be downloaded
     */
    private $file;

    /**
     * Constructor
     *
     * @param \SplFileInfo $aFile            
     * @param string $aFilename
     *            The filename under which to present the download to the user. Omit to use the real filename.
     *   @param string $mimetype the mimetype to present to the user
     */
    public function __construct(\SplFileInfo $file, $filename = null, $mimeType = null)
    {
        if ($filename === null){
            $filename = $file->getFilename();
        }
        if ($mimetype === null){
            $mimeType = Mimetype::get()->getType($this->file);
        }
        
        // empty string for content, as content will be loaded from the file @see getContent()
    	parent::__construct($this->file, $filename, $filename, $mimeType);
    }
   
}