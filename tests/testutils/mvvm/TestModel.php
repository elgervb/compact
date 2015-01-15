<?php
namespace testutils\mvvm;

use compact\mvvm\impl\Model;
use compact\utils\Random;

/**
 * @author eaboxt
 */
class TestModel extends Model
{
    const ID = "id";
    const GUID = "guid";
    const NUMBER = "nr";
    const FIELD1 = "field1";
    const FIELD2 = "field2";
    
    /**
     * Generate random data for this modal
     * @param IModel $model
     */
    public static function randomData($model){
        $model->set(self::NUMBER, Random::nummeric(2, 25000));
        $model->set(self::FIELD1, Random::alphaNum(15));
        $model->set(self::FIELD2, Random::alphaNum(8));
    }
}