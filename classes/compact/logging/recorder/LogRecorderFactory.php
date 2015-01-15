<?php
namespace compact\logging\recorder;

use compact\logging\recorder\impl\BufferedScreenRecorder;
use compact\Context;
use compact\logging\recorder\impl\LogRecorderTypes;
use compact\logging\recorder\impl\FileRecorder;
use compact\logging\recorder\impl\BufferedFileRecorder;
use compact\logging\recorder\impl\NullRecorder;
use compact\logging\recorder\impl\BufferedHtmlScreenRecorder;
use compact\logging\recorder\impl\ScreenRecorder;

/**
 *
 * @author elgervb
 */
class LogRecorderFactory
{

    /**
     * Creates a new recorder based on the type.
     *
     * @param string $aType
     *            The type of the recorder (use constants of LogRecorderTypes)
     * @param Context $aContext            
     *
     * @return IRecorder The newly created recorder, NEVER null
     * @see LogRecorderTypes
     */
    public function createRecorder($aType, Context $aContext)
    {
        $result = null;
        
        switch ($aType) {
            case LogRecorderTypes::DATABASE:
                throw new \Exception("Database log recorder not implemented");
                break;
            case LogRecorderTypes::FILE:
                require_once 'compact\logging\recorder\impl\FileRecorder.php';
                $result = new FileRecorder($aContext->getLogDir());
                break;
            case LogRecorderTypes::FILE_BUFFERED:
                require_once 'compact\logging\recorder\impl\BufferedFileRecorder.php';
                $result = new BufferedFileRecorder($aContext->getLogDir());
                break;
            case LogRecorderTypes::HTML:
                require_once 'compact\logging\recorder\impl\BufferedHtmlScreenRecorder.php';
                $result = new BufferedHtmlScreenRecorder();
                break;
            case LogRecorderTypes::SCREEN:
                require_once 'compact\logging\recorder\impl\ScreenRecorder.php';
                $result = new ScreenRecorder();
                break;
            case LogRecorderTypes::SCREEN_BUFFERED:
                require_once 'compact\logging\recorder\impl\BufferedScreenRecorder.php';
                $result = new BufferedScreenRecorder();
                break;
            default:
                require_once 'compact\logging\recorder\impl\NullRecorder.php';
                $result = new NullRecorder();
        }
        
        return $result;
    }
}
