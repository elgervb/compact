<?php
namespace compact\logging\decorator;

interface IMessageDecorator
{

    /**
     * Decorates a log message
     *
     * @param string $aLogMessage            
     *
     * @return string The log message
     */
    public function decorate($aLogMessage);
}