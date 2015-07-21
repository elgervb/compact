<?php
namespace compact\handler\impl\download;


/**
 * Serve a string for download
 * 
 * @author eaboxt
 */
class Download
{
    const DOWNLOAD_MIME_TYPE = 'application/octet-stream';
    /**
     * The content to serve for download
     * @var string
     */
    private $content;
    
    /**
     * The mime type of the download
     * @var String
     */
    private $mimeType;

    /**
     *
     * @var string The filename to serve the user when downloading
     */
    private $downloadFilename;

    /**
     * Constructor
     *
     * @param \SplFileInfo $aFile            
     * @param string $aFilename
     *            The filename under which to present the download to the user.
     * @param string $mimeType The mimetype to present to the user
     */
    public function __construct( $content, $filename, $mimeType)
    {
        $this->content = $content;
        $this->downloadFilename = $filename;
        $this->mimeType = $mimeType;
    }

    /**
     * return the content for download
     * 
     * @return string|object|\SplFileInfo
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the filename of the downloaded file to be presented to the user
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->downloadFilename;
    }

    /**
     * Returns the mimetype of the download
     * 
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
}