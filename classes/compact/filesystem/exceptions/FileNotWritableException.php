<?php
namespace compact\filesystem;

use compact\filesystem\exceptions\FilesystemException;

class FileNotWritableException extends FilesystemException
{

    /**
     * Creates a new TFileNotWritableException
     *
     * @param string $aPath            
     */
    public function __construct($aPath)
    {
        parent::__construct('File is not writable ' . $aPath, 0);
    }
}