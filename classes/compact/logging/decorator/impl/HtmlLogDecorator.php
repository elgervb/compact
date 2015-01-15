<?php
namespace compact\logging\decorator\impl;

use compact\logging\decorator\IMessageDecorator;

class HtmlLogDecorator implements IMessageDecorator
{

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\decorator\impl.LogDecorator::decorate()
     */
    public function decorate($aLogMessage)
    {
        $logMessage = htmlentities($aLogMessage);
        return '<p style="padding:0;margin:0;background-color:' . $this->getBackgroundColor($logMessage) . '">' . date("Y-m-d H:i:s") . " " . $logMessage . "</p>";
    }

    /**
     * Returns the background color based on the message
     *
     * @param String $aMessage            
     *
     * @return string the hex background color
     */
    private function getBackgroundColor($aMessage)
    {
        $parts = explode(" ", $aMessage);
        if (count($parts) <= 0) {
            return "transparent";
        }
        $logLevel = $parts[0];
        
        switch ($logLevel) {
            case "ALL":
            case "FINEST":
                return "#EFEFFF";
            case "WARNING":
                return "#FFE9CF";
            case "ERROR":
                return "#FFCFCF";
            default:
                return "#EFEFEF";
        }
    }
}