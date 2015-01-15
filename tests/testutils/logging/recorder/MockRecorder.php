<?php
namespace testutils\logging\recorder;

use compact\logging\Logger;
use compact\logging\recorder\IRecorder;

class MockRecorder implements IRecorder
{

    private $messages;

    public function __construct()
    {
        $this->messages = new \ArrayObject();
    }

    public function flush()
    {
        $result = "";
        foreach ($this->messages as $message) {
            $result .= $message;
        }
        return $result;
    }

    public function countMessages()
    {
        return $this->messages->count();
    }

    public function getLogThreshold()
    {
        return Logger::ALL;
    }

    public function isScreenRecorder()
    {
        return false;
    }

    public function record($aMessage, $aLogLevel)
    {
        $this->messages->append($aMessage);
    }
}