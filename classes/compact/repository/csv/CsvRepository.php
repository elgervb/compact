<?php
namespace compact\repository\csv;

use compact\repository\file\FileRepository;
use compact\repository\IModelConfiguration;
use compact\logging\Logger;
use compact\mvvm\impl\Model;

/**
 * Model repository stored in a CSV file
 *
 * @author eaboxt
 */
class CsvRepository extends FileRepository
{

    const CSV_DELIMITER = ";";
    /*
     * (non-PHPdoc) @see \compact\repository\file\FileRepository::serialize()
     */
    protected function serialize(\ArrayObject $aObject)
    {
        if ($aObject->count() < 1) {
            return true; // serialize ok, no models to store
        }
        
        $fields = $this->getModelConfiguration()->getFieldNames($aObject->offsetGet(0));
        
        $writer = $this->getWriter();
        $writer->open();
        foreach ($aObject as $key => $model) {
            $modelString = "";
            foreach ($fields as $field) {
                $modelString .= $model->get($field) . self::CSV_DELIMITER;
            }
            // remove the last delimiter
            $modelString = substr($modelString, 0, strlen($modelString)-1);
            $result = $writer->writeLine($modelString);
        }
        
        $writer->close();
        
        Logger::get()->logFine('Serializing repository for ' . get_class($this->getModelConfiguration()) . ', models: ' . $aObject->count());
        
        return false != $result;
    }
    
    /*
     * (non-PHPdoc) @see \compact\repository\file\FileRepository::unserialize()
     */
    protected function unserialize()
    {
        $result = new \ArrayObject();
        $reader = $this->getReader();
        $fields = null;
        $size = 0;
        $modelString = "";
        
        // Read line by line
        $reader->open();
        while (! $reader->eof()) {
            $line = $reader->readLine();
            
            if ($line) {
                $parts = explode(self::CSV_DELIMITER, $line);
                $model = $this->getModelConfiguration()->createModel();
                
                // get the model fields when fetching the first model
                if ($fields === null) {
                   $fields = $this->getModelConfiguration()->getFieldNames($model);
                   // as this is an associative array, we should also create indexes...
                   $i=0;
                   foreach($fields as $field){
                        $fields[$i] = $field;
                        $i++;
                   }
                }
                
                for ($i = 0; $i < count($parts); $i ++) {
                    $field = $fields[$i];
                    $model->set($field, $parts[$i]);
                }
                
                $result->append($model);
            }
        }
        $reader->close();
        
        if ($result->count() > 0) {
            
            Logger::get()->logFine('Deserializing repository for ' . get_class($this->getModelConfiguration()) . ' models: ' . $result->count());
        }
        
        return $result;
    }
}
