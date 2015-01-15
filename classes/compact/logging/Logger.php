<?php
namespace compact\logging;

use compact\logging\recorder\IRecorder;
use compact\logging\recorder\impl\BufferedScreenRecorder;

/**
 * A logger, use an adapter to redirect the logging;
 *
 * <p>When creating the Logger using the get() method, default adapter is <em>BufferedScreenRecorder</em> and loglevel FINEST.</p>
 *
 * <p> Use one of: </p>
 *
 * <ul>
 * <li> FileRecorder: recorder to output all messages directly to a file </li>
 * <li> BufferedFileRecorder: recorder to output all messages to a file on destruction of the object</li>
 * <li> ScreenRecorder: recorder to output all messages directly to screen </li>
 * <li> BufferedScreenRecorder: recorder to output all messages to screen on destruction of the object</li>
 * <li> NullRecorder: implementation of the null object to discard all logging</li>
 * </ul>
 *
 * or use the LogRecorderFactory
 *
 * <p> or create some more of your own </p>
 */
class Logger
{

    /**
     * Log levels
     */
    const NONE = 0;

    const ERROR = 1;

    const WARNING = 2;

    const NORMAL = 3;

    const FINE = 4;

    const FINEST = 5;

    const DEBUG = 6;

    const ALL = 7;

    protected $currentLogLevel = self::NONE;

    /**
     * Holds the instance of Logger
     *
     * @var Logger
     */
    protected static $instance;

    /**
     *
     * @var IRecorder
     */
    private $recorder;

    /**
     * Creates a new instance of logger
     *
     * @param IRecorder $aRecorder            
     * @param int $aLogLevel
     *            default Logger::NORMAL
     */
    public function __construct(IRecorder $aRecorder, $aLogLevel = Logger::NORMAL)
    {
        self::$instance = $this;
        
        $this->recorder = $aRecorder;
        $this->currentLogLevel = $aLogLevel;
    }

    /**
     * Flushes the recorder
     */
    public function flush()
    {
        if ($this->currentLogLevel !== Logger::NONE) {
            $this->recorder->flush();
        }
    }

    /**
     * Returns a logger, if a logger has not yet been created, this will create a
     * new logger with loglevel NORMAL and a BufferedScreenRecorder
     *
     * @return Logger
     */
    public static function get()
    {
        if (self::$instance == null) {
            self::$instance = new Logger(new BufferedScreenRecorder(), self::NORMAL);
        }
        
        return self::$instance;
    }

    /**
     * Returns the current loglevel
     *
     * @return int
     */
    public function getLoglevel()
    {
        return $this->currentLogLevel;
    }

    /**
     *
     * @see IRecorder::isScreenRecorder()
     */
    public function isScreenRecorder()
    {
        return $this->recorder->isScreenRecorder();
    }

    /**
     * Logs a message at a specific loglevel
     *
     * @param $aLogLevel int            
     * @param $aMessage String            
     *
     * @throws RuntimeException when loglevel is not one of the Logger constants
     */
    public function log($aLogLevel, $aMessage)
    {
        assert('!empty($aMessage) && "The message cannot be empty"');
        assert('is_int($aLogLevel)');
        
        if ($aLogLevel < self::NONE || $aLogLevel > self::ALL) {
            throw new \RuntimeException("Unable to log. Unknown loglevel (" . $aLogLevel . ").");
        }
        
        switch ($aLogLevel) {
            case self::DEBUG:
                $aMessage = 'DEBUG ' . $aMessage;
                break;
            case self::ERROR:
                $aMessage = 'ERROR ' . $aMessage;
                break;
            case self::WARNING:
                $aMessage = 'WARNING ' . $aMessage;
                break;
            case self::NORMAL:
                $aMessage = 'NORMAL ' . $aMessage;
                break;
            case self::FINE:
                $aMessage = 'FINE ' . $aMessage;
                break;
            case self::FINEST:
                $aMessage = 'FINEST ' . $aMessage;
                break;
            case self::ALL:
                $aMessage = 'ALL ' . $aMessage;
                break;
        }
        
        if ($aLogLevel != Logger::NONE && $aLogLevel <= $this->currentLogLevel) {
            $this->recorder->record($aMessage, $aLogLevel);
        }
    }

    /**
     * Logs with log level finest + sql
     *
     * @param $aMessage String
     *            The log message
     */
    public function logAll($aMessage)
    {
        $this->log(Logger::ALL, $aMessage);
    }

    /**
     * Logs with log level finest
     *
     * @param $aMessage String
     *            The log message
     */
    public function logFinest($aMessage)
    {
        $this->log(Logger::FINEST, $aMessage);
    }

    /**
     * Logs with log level fine
     *
     * @param $aMessage String
     *            The log message
     */
    public function logFine($aMessage)
    {
        $this->log(Logger::FINE, $aMessage);
    }

    /**
     * Logs with log level normal
     *
     * @param $aMessage String
     *            The log message
     */
    public function logNormal($aMessage)
    {
        $this->log(Logger::NORMAL, $aMessage);
    }

    /**
     * Logs with log level debug
     *
     * @param $aMessage String
     *            The log message
     */
    public function logDebug($aMessage)
    {
        $this->log(Logger::DEBUG, $aMessage);
    }

    /**
     * Logs with log level error
     *
     * @param $aMessage String
     *            The log message
     */
    public function logError($aMessage)
    {
        $this->log(Logger::ERROR, $aMessage);
    }

    /**
     * Logs with log level warning
     *
     * @param $aMessage String
     *            The log message
     */
    public function logWarning($aMessage)
    {
        $this->log(Logger::WARNING, $aMessage);
    }

    /**
     * Sets the loglevel.
     * Use the static members of logger
     *
     * @param $aLoglevel int            
     */
    public function setLoglevel($aLoglevel)
    {
        $this->currentLogLevel = $aLoglevel;
    }

    /**
     * Checks if the supplied loglevel is a valid one
     *
     * @param $aLogLevel int            
     *
     * @return boolean
     */
    public static function isValidLogLevel($aLogLevel)
    {
        return $aLogLevel == Logger::DEBUG || $aLogLevel == Logger::ERROR || $aLogLevel == Logger::FINE || $aLogLevel == Logger::FINEST || $aLogLevel == Logger::ALL || $aLogLevel == Logger::NONE || $aLogLevel == Logger::NORMAL || $aLogLevel == Logger::WARNING;
    }

    /**
     * Returns the loglevel as a string
     *
     * @param int $aLogLevel
     *            The log level
     *            
     * @return string
     */
    public static function getAsString($aLogLevel)
    {
        switch ($aLogLevel) {
            case 7:
                return "ALL";
            case 6:
                return "DEBUG";
            case 5:
                return "FINEST";
            case 4:
                return "FINE";
            case 3:
                return "NORMAL";
            case 2:
                return "WARNING";
            case 1:
                return "ERROR";
            default:
                return "NONE";
        }
    }

    /**
     * Returns the loglevel as an integer
     *
     * @param string $aLogLevel            
     *
     * @return int
     */
    public static function getAsInteger($aLogLevel)
    {
        switch ($aLogLevel) {
            case "ALL":
                return Logger::ALL;
            case "FINEST":
                return Logger::FINEST;
            case "FINE":
                return Logger::FINE;
            case "NORMAL":
                return Logger::NORMAL;
            case "DEBUG":
                return Logger::DEBUG;
            case "WARNING":
                return Logger::WARNING;
            case "ERROR":
                return Logger::ERROR;
            case "NONE":
                return Logger::NONE;
            default:
                return - 1;
        }
    }
}