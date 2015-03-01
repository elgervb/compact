<?php
namespace compact\utils
{

    use compact\mvvm\impl\Model;
    use compact\filesystem\Filesystem;
    use compact\mvvm\IModel;
    use compact\Context;
    use compact\http\HttpRequest;
    use compact\repository\IModelConfiguration;

    class ModelUtils
    {

        /**
         * Converts a SplFileInfo to a Model
         *
         * @param \SplFileInfo $aFile            
         *
         * @return Model
         */
        public static function fileToModel(\SplFileInfo $aFile)
        {
            $model = new Model();
            $model->{'filename'} = $aFile->getFilename();
            $model->{'extension'} = Filesystem::getExtension($aFile);
            $model->{'last_modified'} = $aFile->getMTime();
            $model->{'size'} = $aFile->getSize();
            $model->{'type'} = $aFile->getType();
            $model->{'path'} = $aFile->getPathname();
            return $model;
        }

        /**
         * Fills in the post values of a model
         *
         * @param
         *            IModelConfiguration The model configuration
         * @param
         *            IModel [optional] The model to be filled in. If omitted, then the configuration will be used to create a new model
         *            
         * @return IModel The model filled in with the POST fields or the same model when no post occured
         */
        public static function getPost(IModelConfiguration $aConfiguration, IModel $aModel = null)
        {
            $model = $aModel;
            if ($model === null) {
                $model = $aConfiguration->createModel();
            }
            
            $fields = $aConfiguration->getFieldNames($model);
            $request = Context::get()->http()->getRequest();
            
            foreach ($fields as $field) {
                self::getPostForField($request, $model, $field, null);
            }
            
            return $model;
        }

        public static function getPostForMultipleModels(IModelConfiguration $aConfiguration, IModel $aModel, $aFieldNameToCheck)
        {
            $fields = $aConfiguration->getFieldNames($aModel);
            $request = Context::get()->http()->getRequest();
            
            $result = new \ArrayObject();
            $count = $request->countPost($aFieldNameToCheck);
            
            // create models
            for ($i = 0; $i < $count; $i ++) {
                $result->append($aConfiguration->createModel());
            }
            // fill in models
            for ($i = 0; $i < $count; $i ++) {
                $model = $result->offsetGet($i);
                
                foreach ($fields as $field) {
                    self::getPostForField($request, $model, $field, $i);
                }
            }
            
            return $result;
        }

        /**
         *
         * @param HttpRequest $aRequest            
         * @param IModel $aModel            
         * @param string $aFieldName            
         * @param int $aIndex            
         */
        private static function getPostForField(HttpRequest $aRequest, IModel $aModel, $aFieldName, $aIndex = null)
        {
            $isCheckBox = $aRequest->hasPost("chk" . $aFieldName);
            $postField = $isCheckBox ? "chk" . $aFieldName : $aFieldName;
            
            if ($aRequest->hasMultiplePostForKey($postField)) {
                if ($isCheckBox) {
                    if ($aRequest->hasPost($aFieldName)) {
                        $value = $aRequest->getPost($postField, $aIndex);
                        if ($value != "off") {
                            $aModel->{$aFieldName} = $value;
                        }
                    }
                } else {
                    if ($aRequest->hasPost($aFieldName)) {
                        $aModel->{$aFieldName} = $aRequest->getPost($postField, $aIndex);
                    }
                }
            } else {
                if ($isCheckBox) {
                    if ($aRequest->hasPost($aFieldName)) {
                        $value = $aRequest->getPost($postField);
                        if ($value != "off") {
                            $aModel->{$aFieldName} = $value;
                        }
                    }
                } else {
                    if ($aRequest->hasPost($aFieldName)) {
                        $aModel->{$aFieldName} = $aRequest->getPost($postField);
                    }
                }
            }
        }

        /**
         * Fills in the post values of a model's fields.
         * The model does not depend on a IModelConfiguration. Any model can be passed, but the model fields will depend on the contants of the model provided
         *
         * @param
         *            IModel The model to be filled in
         *            
         * @return IModel The model filled in with the POST fields or the same model when no post occured
         *        
         * @see IModelConfiguration
         */
        public static function getPostForSimpleModel(IModel $aModel)
        {
            $request = Context::get()->http()->getRequest();
            if (! $request->hasPost()) {
                return $aModel;
            }
            
            $fields = ModelUtils::getFields($aModel);
            foreach ($fields as $field) {
                self::getPostForField($request, $aModel, $field);
            }
            
            return $aModel;
        }

        /**
         * Fills the post fields in a model, regardless of its fields or configuration
         *
         * @param $aModel IModel            
         *
         * @return IModel
         */
        public static function getPostForDynamicModel(IModel $aModel)
        {
            $request = Context::get()->http()->getRequest();
            if (! $request->hasPost()) {
                return $aModel;
            }
            
            foreach ($_POST as $field => $value) {
                self::getPostForField($request, $aModel, $field);
            }
            
            return $aModel;
        }

        /**
         * Return all fields from a model defined as constants
         *
         * @param $aModel IModel            
         *
         * @return array
         */
        public static function getFields(IModel $aModel)
        {
            $reflector = new \ReflectionClass($aModel);
            return $reflector->getConstants();
        }

        /**
         * Checks if a model contains any data
         *
         * @param $aModel IModel            
         * @param $aFields array            
         *
         * @return boolean
         */
        public static function isEmpty(IModel $aModel, array $aFields = null)
        {
            if ($aFields === null) {
                $aFields = self::getFields($aModel);
            }
            
            foreach ($aFields as $field) {
                if (! $aModel->isEmpty($field)) {
                    return false;
                }
            }
            
            return true;
        }

        /**
         * Merges the second model into the first model
         *
         * @param $aM1 IModel            
         * @param $aM2 IModel            
         *
         * @return IModel the first model, merged
         *        
         * @throws MergeException on error
         */
        public static function mergeInto(IModel $aM1, IModel $aM2, array $aFields = null)
        {
            if ($aFields === null) {
                $aFields = self::getFields($aM2);
            }
            if (ModelUtils::isEmpty($aM2, $aFields))
                throw new MergeException("Could not merge models");
            
            if ($aFields === null)
                $fields = ModelUtils::getFields($aM2);
            else
                $fields = $aFields;
            
            foreach ($fields as $field) {
                $aM1->set($field, $aM2->get($field));
            }
            
            return $aM1;
        }
    }

    class MergeException extends \Exception
    {
        /*
         * (non-PHPdoc) @see Exception::__construct()
         */
        public function __construct($message = null, $code = null, $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }
}