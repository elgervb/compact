<?php
namespace compact\handler\impl\download;

use compact\http\HttpResponse;
use compact\logging\Logger;
use compact\filesystem\Mimetype;
use compact\io\reader\StreamReader;
use compact\handler\IHander;

/**
 * Serve a file for download
 *
 * @author elger
 */
class FileDownloader
{

    /**
     *
     * @var HttpResponse
     */
    private $response;

    private $isResume = true;

    /**
     * Creates a new FileDownload
     *
     * @param $aResponse HttpResponse
     *            The http response
     */
    public function __construct(HttpResponse $aResponse)
    {
        $this->response = $aResponse;
    }

    /**
     * Check if http_range is sent by browser (or download manager)
     *
     * TODO shouldn't this be in HttpRequest? Yes => move...
     *
     * @return string The http range
     */
    private function getHttpRange()
    {
        $range = '';
        if ($this->isResume && isset($_SERVER['HTTP_RANGE'])) {
            list ($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            
            if ($size_unit == 'bytes') {
                // multiple ranges could be specified at the same time, but for simplicity only serve the first range
                // http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                list ($range, $extra_ranges) = explode(',', $range_orig, 2);
            } else {
                Logger::get()->logWarning(__METHOD__ . " line " . __LINE__ . " unsupported unit size: " . $size_unit);
            }
        }
        
        return $range;
    }

    /**
     * Serve a file for download
     *
     * @param $aFile SplFileInfo
     *            The file to download
     * @param $aUserFileName string
     *            [optional] the filename presented to the user
     * @param $aMimeType string
     *            [optional] the mimetype of the download, null to let the system determine the mime-type
     * @param $aReadBuffer int
     *            [optional] = 1024000
     */
    public function serveFile(\SplFileInfo $aFile, $aUserFileName = null, $aMimeType = 'application/octet-stream', $aReadBuffer = 1024000)
    {
        assert('is_int($aReadBuffer)');
        $userFileName = $aUserFileName == null ? $aFile->getFilename() : $aUserFileName;
        
        $mimeType = $aMimeType;
        if ($mimeType == null) {
            $mimeType = Mimetype::get()->getType($aFile);
            assert('$aMimeType !== null //');
        }
        
        $reader = new StreamReader($aFile->getPathname());
        $reader->open();
        $content = "";
        while (! $reader->eof()) {
            $content .= $reader->read($aReadBuffer);
        }
        $reader->close();
        
        $this->serve($content, $userFileName, $mimeType);
    }

    /**
     * Serve text for download
     *
     * @param $aContent string            
     */
    public function serve($aContent, $aUserFileName, $aMimeType = 'application/octet-stream')
    {
        $size = strlen($aContent);
        
        if ($size <= 0) {
            Logger::get()->logWarning("FileDownload: serve " . $size . " bytes for download. Exiting...");
            return;
        }
        
        Logger::get()->logFinest("FileDownload: serve " . $size . " bytes for download.");
        $range = $this->getHttpRange();
        
        // figure out download piece from range (if set)
        if (strstr($range, "-")) {
            list ($seek_start, $seek_end) = explode('-', $range, 2);
        } else {
            $seek_start = 0;
        }
        
        // set start and end based on range (if set), else set defaults
        // also check for invalid ranges.
        $seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)), ($size - 1));
        $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);
        
        // add headers if resumable
        if ($this->isResume) {
            // Only send partial content header if downloading a piece of the file (IE workaround)
            if ($seek_start > 0 || $seek_end < ($size - 1)) {
                $this->response->setStatusCode(206);
            }
            
            $this->response->setHeader('Accept-Ranges', 'bytes');
            $this->response->setHeader('Content-Range', 'bytes ' . $seek_start . '-' . $seek_end . '/' . $size);
        }
        
        // headers for IE Bugs
        $this->response->setHeader("Cache-Control", "cache, must-revalidate");
        $this->response->setHeader("Pragma", "public");
        
        $this->response->setContentType($aMimeType);
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $aUserFileName . '"');
        $this->response->setHeader('Content-Length', ($seek_end - $seek_start + 1));
        // always close connection. Else, if the user cancels the download, the next page will not be displayed correctly
        $this->response->setHeader('Connection', 'close');
        
        // flush the response
        $this->response->flush();
        
        // buffered output
        $index = 0;
        $buffer = 4096;
        while ($size > 0) {
            $read = substr($aContent, $index, $buffer);
            $index += $buffer;
            $size -= $buffer;
            $this->response->getWriter()->write($read);
            $this->response->flush();
        }
    }

    /**
     * Set the download as resumable or not to support download managers for large files
     *
     * @param $aBool boolean            
     */
    public function setIsResumable($aBool)
    {
        $this->isResume = $aBool;
    }
}