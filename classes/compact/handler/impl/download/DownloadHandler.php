<?php
namespace compact\handler\impl\download;

use compact\handler\IHander;
use compact\handler\impl\download\Download;
use compact\handler\impl\download\FileDownloader;
use compact\Context;

class DownloadHandler implements IHander
{
    
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        return $object instanceof Download;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \compact\handler\IHander::handle()
     *
     * @param $object Download            
     */
    public function handle($object)
    {
        $d = new FileDownloader(Context::get()->http()->getResponse());
        $download = $object->getContent();
        if ($download instanceof \SplFileInfo){
            $d->serveFile($download, $object->getFileName(), $object->getMimeType());
        }
        else {
            $d->serve($download, $object->getFileName(), $object->getMimeType());
        }
    }
}