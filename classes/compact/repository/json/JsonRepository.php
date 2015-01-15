<?php
namespace compact\repository\json;

use compact\repository\file\FileRepository;
use compact\repository\IModelConfiguration;
use compact\utils\JsonUtils;
use compact\logging\Logger;

/**
 * Model repository stored in a JSON file
 *
 * @author eaboxt
 */
class JsonRepository extends FileRepository
{

    /**
     *
     * @param $aModelConfiguration IModelConfiguration            
     */
    public function __construct(IModelConfiguration $aModelConfiguration, \SplFileInfo $aFile)
    {
        parent::__construct($aModelConfiguration, $aFile);
    }
    
    /*
     * (non-PHPdoc) @see \compact\repository\file\FileRepository::serialize()
     */
    protected function serialize(\ArrayObject $aObject)
    {
        $writer = $this->getWriter();
        $writer->open();
        $writer->writeLine('['); // begin array notation
        
        foreach ($aObject as $key => $model) {
            $result = $writer->writeLine(JsonUtils::encode($model) . ',');
        }
        
        $writer->writeLine(']'); // end array notation
        $writer->close();
        
        Logger::get()->logFine('Serializing repository for ' . get_class($this->getModelConfiguration()) . ', models: ' . $aObject->count());
        
        return false != $result;
    }
    
    /*
     * (non-PHPdoc) @see \compact\repository\file\FileRepository::unserialize()
     */
    protected function unserialize()
    {
        $reader = $this->getReader();
        $size = 0;
        // Read whole file
        $reader->open();
        $modelString = "";
        
        while (! $reader->eof()) {
            $modelString .= $reader->readLine() . "\n";
        }
        $reader->close();
        $result = JsonUtils::decode($modelString);
        
        if ($result->count() > 0) {
            
            Logger::get()->logFine('Deserializing repository for ' . get_class($this->getModelConfiguration()) . '. Bytes: ' . $size . ', models: ' . $result->count());
        }
        
        return $result;
    }
}
