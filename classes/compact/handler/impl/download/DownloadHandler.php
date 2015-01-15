<?php
namespace compact\handler\impl\download;

use compact\handler\IHander;
use compact\handler\impl\download\Download;
use compact\handler\impl\download\FileDownload;
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
        $d = new FileDownload(Context::get()->http()->getResponse());
        $d->serveFile($object->getFile(), $object->getFileName(), $object->getMimeType());
    }
}