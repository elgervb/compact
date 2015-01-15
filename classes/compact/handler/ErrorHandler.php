<?php
namespace compact\handler;

/**
 *
 * @author elger
 */
class ErrorHandler
{

    /**
     * Constructor
     *
     * @param integer $aErrorLevel
     *            = -1 (all errors)
     * @param boolean $aIsDisplayErrorsOnScreen
     *            = false
     *            
     * @param boolean $aLogErrors
     *            = true
     */
    public function __construct($aErrorLevel = -1, $aIsDisplayErrorsOnScreen = false, $aLogErrors = true, $aErrorLog = "./error.log")
    {
        set_error_handler(array(
            $this,
            "handle"
        ), $aErrorLevel);
        $this->setDisplayErrors($aIsDisplayErrorsOnScreen);
        $this->setLogErrors($aLogErrors, $aErrorLog);
    }

    /**
     * Handle PHP errors
     *
     * @param int $aErrNr
     *            The error number
     * @param String $aErrStr
     *            The error message
     * @param String $aErrFile
     *            The file the error occured
     * @param String $aErrLine
     *            The line in the file the error occured
     * @param String $aContext
     *            The arguments
     *            
     * @throws ErrorException
     */
    public function handle($aErrNr, $aErrStr, $aErrFile, $aErrLine, $aContext)
    {
        if (! defined('E_STRICT')) {
            define('E_STRICT', 2048);
        }
        if (! defined('E_RECOVERABLE_ERROR')) {
            define('E_RECOVERABLE_ERROR', 4096);
        }
        
        $severity = 1;
        
        switch ($aErrNr) {
            case E_ERROR:
                $print = "Error";
                $severity = E_ERROR;
                break;
            case E_WARNING:
                $print = "Warning";
                $severity = E_WARNING;
                break;
            case E_PARSE:
                $print = "Parse Error";
                $severity = E_PARSE;
                break;
            case E_NOTICE:
                $print = "Notice";
                $severity = E_NOTICE;
                break;
            case E_CORE_ERROR:
                $print = "Core Error";
                $severity = E_CORE_ERROR;
                break;
            case E_CORE_WARNING:
                $print = "Core Warning";
                $severity = E_CORE_WARNING;
                break;
            case E_COMPILE_ERROR:
                $print = "Compile Error";
                $severity = E_COMPILE_ERROR;
                break;
            case E_COMPILE_WARNING:
                $print = "Compile Warning";
                $severity = E_COMPILE_WARNING;
                break;
            case E_USER_ERROR:
                $print = "User Error";
                $severity = E_USER_ERROR;
                break;
            case E_USER_WARNING:
                $print = "User Warning";
                $severity = E_USER_WARNING;
                break;
            case E_USER_NOTICE:
                $print = "User Notice";
                $severity = E_USER_NOTICE;
                break;
            case E_STRICT:
                $print = "Strict Notice";
                $severity = E_STRICT;
                break;
            case E_RECOVERABLE_ERROR:
                $print = "Recoverable Error";
                $severity = E_RECOVERABLE_ERROR;
                break;
            
            default:
                $print = "Unknown error ($aErrNr)";
                break;
        }
        
        throw new \ErrorException($print . ": " . $aErrStr, $aErrNr, $severity, $aErrFile, $aErrLine);
    }

    /**
     * Should we display error on screen?
     *
     * @param boolean $aIsShow            
     */
    private function setDisplayErrors($aIsShow)
    {
        ini_set('display_errors', ($aIsShow) ? 'On' : 'Off');
    }

    /**
     * Sets the level of error reporting
     *
     * @param int $aErrorLevel
     *            = E_ALL
     */
    private function setErrorReporting($aErrorLevel = E_ALL)
    {
        error_reporting($aErrorLevel);
    }

    /**
     * Log errors to an error logfile
     *
     * @param boolean $aIsLogErrors
     *            Should we log error to a logfile?
     * @param string $aErrorLogFile
     *            = null The logfile to log the errors to, only applicable when $aIsLogErrors = true
     */
    private function setLogErrors($aIsLogErrors, $aErrorLogFile = null)
    {
        ini_set('log_errors', ($aIsLogErrors) ? 'On' : 'Off');
        if ($aIsLogErrors) {
            ini_set('error_log', $aErrorLogFile);
        }
    }
}