<?php
namespace compact\logging\recorder;

/**
 * Interface for recording a message to a specific medium, like: database, file, screen
 */
interface IRecorder
{

    /**
     * Flushes the output buffer
     */
    public function flush();

    /**
     * Returns the log level threshold.
     * Eg. when the threshold is warning, then everything below that level will not be logged.
     *
     * @return int The loggin theshold
     *        
     * @see Logger constants
     */
    public function getLogThreshold();

    /**
     * Denotes if this recorder is a screen recorder
     *
     * @return boolean true if this is a screen recorder, false if not
     */
    public function isScreenRecorder();

    /**
     * Records the log message
     *
     * @param String $aMessage
     *            The log message to record
     * @param int $aLogLevel
     *            The loglevel the current log entry is using
     */
    public function record($aMessage, $aLogLevel);
}