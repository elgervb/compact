<?php
namespace compact\filesystem\exceptions;

/**
 * Filesystemexception: the name sais it all...
 * ;-)
 *
 * @package filesystem
 * @subpackage exception
 */
class FileNotFoundException extends FilesystemException
{

    /**
     * Creates a new instance of FileNotFoundException
     *
     * @param String $aFileName            
     */
    public function __construct($aFile)
    {
        /* @var $aFile \SplFileInfo */
        parent::__construct("File " . ($aFile instanceof \SplFileInfo ? $aFile->getPathname() : $aFile) . " does not exist.");
    }
}