<?php
namespace compact\filesystem\exceptions;

/**
 *
 * @package filesystem
 * @subpackage exception
 */
class FilesystemException extends \Exception
{

    const ERROR_FILE_NOT_FOUND = 10000;

    const ERROR_WRONG_EXTENSION = 10001;

    const ERROR_FILE_EXISTS = 10002;

    const ERROR_FILE_UNABLE_TO_CREATE = 10003;

    const ERROR_UNKNOWN_REASON = 10004;

    public function __construct($aMessage, $aCode = 0)
    {
        parent::__construct($aMessage, $aCode);
    }
}