<?php
namespace compact\mvvm\impl;

use compact\Context;
use compact\mvvm\IView;
use compact\utils\NullObject;

/**
 * View with a template
 *
 * @author elger
 */
class TemplateView implements IView
{

    private $vars = array();

    /**
     *
     * @var string The full path to a template file
     */
    private $template;

    /**
     * Constructor
     *
     * @param string $aTemplate
     *            The file path to the template
     * @param array $aVars
     *            = null Initial variables
     */
    public function __construct($aTemplate, array $aVars = null)
    {
        $this->template = $this->convertTemplate($aTemplate);
        
        if ($aVars != null) {
            foreach ($aVars as $key => $value) {
                $this->{$key} = $value;
            }
        }
        $this->{'siteurl'} = Context::siteUrl();
        $this->{'islocal'} = Context::get()->isLocal();
    }

    /**
     * Overridden magic method __call, do nothing when calling a non-existing method
     *
     * @param
     *            string aMethodName The method name
     * @param $aArguments array            
     */
    public function __call($aMethodName, array $aArguments)
    {
        return null;
    }

    /**
     * Magic method to get a variable from a view
     *
     * @param $aKey string            
     *
     * @return mixed the value, or null when not set
     */
    public function __get($aKey)
    {
        if (isset($this->vars[$aKey])) {
            return $this->vars[$aKey];
        }
        
        return new NullObject();
    }

    /**
     * Magic method to set a variable to a view
     *
     * @param $aKey string            
     * @param $aValue mixed            
     */
    public function __set($aKey, $aValue)
    {
        $this->vars[$aKey] = $aValue;
    }

    /**
     * Converts a template into a path Checks if the $aTemplate is already a directory, or else will check if the view exists in the view directory.
     *
     * @param $aTemplate string
     *            The file path to the template
     *            
     * @return string The absolute path to the template
     *        
     * @throws \Exception when the template path does not exist
     */
    protected function convertTemplate($aTemplate)
    {
        $template = $aTemplate;
        if (! is_file($template)) {
            $template = Context::get()->basePath() . "/app/view/" . $template;
            if (! is_file($template)) {
                throw new \Exception("Template " . $aTemplate . " does not exist.");
            }
        }
        
        return $template;
    }

    /**
     * Returns the template for subclasses
     *
     * @return string the connected template
     */
    protected function getTemplate()
    {
        return $this->template;
    }

    /**
     * Renders a template and return the output
     *
     * @param $aTemplate string
     *            The path to the template file
     * @return string
     */
    protected function renderTemplate($aTemplate)
    {
        if (! is_file($aTemplate)) {
            throw new \Exception("Template " . $aTemplate . " does not exist.");
        }
        
        ob_start('mb_output_handler');
        include $aTemplate;
        return ob_get_clean();
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\mvc\IView::render()
     */
    public function render()
    {
        return $this->renderTemplate($this->template);
    }

    public function __toString()
    {
        return $this->render();
    }
}