<?php
namespace compact\filesystem;

use compact\filesystem\exceptions\FilesystemException;
use compact\logging\Logger;
use compact\filesystem\exceptions\FileNotFoundException;

/**
 * Filesystem class
 *
 * Helper class to create files and directories
 *
 * @package filesystem
 */
class Filesystem
{

    /**
     * Clears the cache
     */
    public static function clearstatcache()
    {
        clearstatcache();
    }

    /**
     * Copy file to new location.
     *
     * @param $aOriginal SplFileInfo
     *            The original file
     * @param $aTarget SplFileInfo
     *            The target file
     * @param $aIsoverwrite boolean
     *            = false overwrite when target already exist?
     *            
     * @throws FilesystemException When original file or directory does not exists or when destination already exists and overwrite is false
     *        
     * @return SplFileInfo
     */
    public static function copyFile(\SplFileInfo $aOriginal,\SplFileInfo $aTarget, $aIsoverwrite = false)
    {
        $target = $aTarget;
        
        /**
         * if $newPath is a directory, append the current filename to it.
         */
        if ($aTarget->isDir()) {
            $target = new \SplFileInfo($aTarget . $aOriginal->getFilename());
        } elseif ($aTarget->isFile()) {
            /* check if new path already exists */
            if ($aIsoverwrite == false) {
                throw new FilesystemException("Cannot copy file. File " . $aTarget . " already exists.", FilesystemException::ERROR_FILE_EXISTS);
            }
        }
        
        /**
         * If overwrite == true and new path exists,
         * first delete, then copy file.
         */
        if ($target->isFile() && $aIsoverwrite == true) {
            @unlink($target);
        }
        
        if (@copy($aOriginal, $target)) {
            Logger::get()->logFinest("Copied file from " . $aOriginal . " to " . $target);
            
            return $target;
        } else {
            throw new FilesystemException("ERROR: Could not copy file.", FilesystemException::ERROR_UNKNOWN_REASON);
        }
    }

    /**
     * Create a new empty file
     *
     * @param $aPath SplFileInfo
     *            The path to the file to be created
     * @param $isOverwrite boolean
     *            Should the file be overwritten when the file already exists?
     * @param $aChmod int
     *            Set the permissions of the file
     *            
     * @return \SplFileInfo
     *
     * @throws FilesystemException on error
     */
    public static function createFile(\SplFileInfo $aPath, $isOverwrite = false, $aChmod = 0777)
    {
        if ($isOverwrite === false) {
            if ($aPath->isFile()) {
                throw new FilesystemException("File already exists " . $aPath->getPathname(), FilesystemException::ERROR_FILE_EXISTS);
            }
        }
        
        $fp = fopen($aPath->getPathname(), 'w');
        
        if (! $fp) {
            throw new FilesystemException("Could not create new file " . $aPath->getPathname(), FilesystemException::ERROR_FILE_UNABLE_TO_CREATE);
        }
        
        fclose($fp);
        
        chmod($aPath, $aChmod);
        
        Logger::get()->logFinest("Created new file " . $aPath->getPathname());
        return $aPath;
    }

    public static function createDir(\SplFileInfo $aPath, $aChmod = 0777)
    {
        if ($aPath->isFile()) {
            throw new FilesystemException("Could not create new directory " . $aPath->getPathname() . ' is a filename.', FilesystemException::ERROR_DIR_UNABLE_TO_CREATE);
        }
        
        if ($aPath->isDir()) {
            throw new FilesystemException("Could not create new directory " . $aPath->getPathname() . ' already exists.', FilesystemException::ERROR_DIR_EXISTS);
        }
        
        if (! @mkdir($aPath->getPathname(), $aChmod)) {
            throw new FilesystemException("Could not create new directory " . $aPath->getPathname(), FilesystemException::ERROR_UNKNOWN_REASON);
        }
    }

    /**
     * Creates a temporary file with a unique name.
     * The file is automatically removed
     *
     * @return SplFileInfo
     */
    public static function createTempFile()
    {
        return new \SplFileInfo(tempnam(sys_get_temp_dir(), 'php_'));
    }

    /**
     * Delete file from filesystem
     *
     * @param $aPath SplFileInfo
     *            @throw TFilesystemException when file could not be deleted
     *            @throw TFileNotFoundException when file could not be found
     *            
     * @return boolean
     */
    public static function deleteFile(\SplFileInfo $aPath)
    {
        /**
         * Check if file is a file
         */
        if (! $aPath->isFile()) {
            throw new FileNotFoundException($aPath);
        } elseif (! $aPath->isFile()) {
            throw new FilesystemException("Could not delete file, path is a directory.");
        }
        
        if (@unlink($aPath)) {
            Logger::get()->logFinest("Delete file " . $aPath->getPathname());
            clearstatcache();
            return true;
        } else {
            throw new FilesystemException("Could not delete file. Reason unknown.");
        }
    }

    /**
     * Returns the extension of the file
     *
     * @return string the extension
     */
    public static function getExtension(\SplFileInfo $aFileInfo)
    {
        return pathinfo($aFileInfo, PATHINFO_EXTENSION);
    }

    /**
     * Factory method to create a new SplFileInfo object
     *
     * @param $path string            
     *
     * @return SplFileInfo
     */
    public static function getFile($aPath)
    {
        return new \SplFileInfo($aPath);
    }

    /**
     * Checks if the file is located on the network (UNC)
     *
     * @param \SplFileInfo $aPath            
     * @return boolean
     */
    public static function isUNCpath($aPath)
    {
        if ($aPath instanceof \SplFileInfo) {
            $aPath = $aPath->getPathname();
        }
        return preg_match('/^\\\\/', $aPath);
    }

    /**
     * Moves or renames a file
     *
     * @param $newPath SplFileInfo            
     * @param
     *            [$overwrite=FALSE] Overwrite?
     * @return SplFileInfo
     *
     * @throws TFilesystemException when file already exists and overwrite is false
     */
    public static function moveFile(\SplFileInfo $aOldPath,\SplFileInfo $aNewPath, $aIsOverwrite = false)
    {
        $newPath = $aNewPath;
        /**
         * if $newPath is a directory, append the current filename to it.
         */
        if ($newPath->isDir()) {
            $newPath = new \SplFileInfo($aNewPath . DIRECTORY_SEPARATOR . $aOldPath->getFilename());
        } elseif ($newPath->isFile()) {
            /**
             * Check if new path already exists and overwrite is false
             */
            if ($aIsOverwrite == false) {
                throw new FilesystemException("Cannot move file. " . $newPath->getPathname() . " already exists.", FilesystemException::ERROR_FILE_EXISTS);
            }
        }
        
        /**
         * If overwrite == TRUE
         * and new path exists,
         * then first delete original file and then rename it.
         */
        if ($newPath->isFile() && $aIsOverwrite == true) {
            unset($newPath);
        }
        
        if (@rename($aOldPath, $newPath)) {
            Logger::get()->logFine("Moved file from " . $aOldPath . " to: " . $newPath);
            
            clearstatcache();
            return $newPath;
        } else {
            throw new FilesystemException("Could not move file. Reason unknown.");
        }
    }

    public static function renameFilename(\SplFileInfo $aFile, $aNewFilename, $aIsOverwrite = false)
    {
        $newFile = new \SplFileInfo($aFile->getPath() . DIRECTORY_SEPARATOR . $aNewFilename);
        return Filesystem::moveFile($aFile, $newFile, $aIsOverwrite);
    }
}