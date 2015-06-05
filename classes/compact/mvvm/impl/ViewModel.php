<?php
namespace compact\mvvm\impl;

use compact\Context;
use compact\mvvm\IView;
use compact\mvvm\impl\TemplateView;

/**
 * ViewModel with a template
 *
 *
 * Expressions:
 * - {#foo=="asdf"}{/foo} // check if foo is equal to string asdf
 * - {#foo==bar}{/foo} // check if foo is equal to var bar
 * - {#foo<"2"}{/foo} // check if foo is less then 2
 * - {#foo>"2"}{/foo} // check if foo is larger then 2
 *
 * Note: that would mean that the following expression is alway true: {#foo==foo}{/foo}
 *
 * @author elger
 */
class ViewModel extends TemplateView
{

    /**
     * Constructor
     *
     * @param string $aTemplate
     *            The file path to the template
     * @param array $aVars
     *            = null Initial variables
     * @throws FileNotFoundException when the template path does not exist
     */
    public function __construct($aTemplate, array $aVars = null)
    {
        parent::__construct($aTemplate, $aVars);
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
        $contents = file_get_contents($aTemplate);
        
        $contents = $this->checkExpressions($contents);
        $contents = $this->checkBlocks($contents);
        $contents = $this->replaceVars($contents);
        
        ob_start('mb_output_handler');
        echo $contents;
        return ob_get_clean();
    }

    /**
     * Replace expressions
     *
     * @param string $aContent            
     * @throws \Exception
     */
    private function checkExpressions($aContent)
    {
        $viewmodel = $this;
        
        // if blocks
        $content = preg_replace_callback("/{#([a-z0-9]+)(==|<|>)(.*)}(.*){\/(\g1)}/Usi", function ($match) use($viewmodel)
        {
            // echo '<pre>';print_r($match);print_r($viewmodel);echo '</pre>';
            
            $left = trim(preg_match("/[\'\"]/", $match[1]) ? substr($match[1], 1, $match[1] - 1) : $viewmodel->{$match[1]});
            $operator = trim($match[2]);
            $right = trim(preg_match("/[\'\"]/", $match[3]) ? substr($match[3], 1, $match[3] - 1) : $viewmodel->{$match[3]});
            
            $result = trim($match[4]);
            switch ($operator) {
                case '==':
                    if ($left == $right) {
                        return $result;
                    }
                    break;
                case '<':
                    if ($left < $right) {
                        return $result;
                    }
                    break;
                case '>':
                    if ($left > $right) {
                        return $result;
                    }
                    break;
                default:
                    throw new \Exception("Operator " + $operator + " in expression not supported");
            }
            return "";
        }, $aContent);
        
        return $content;
    }

    private function checkBlocks($aContent)
    {
        $viewmodel = $this;
        
        // if blocks eg. {#islocal}prod stuff...{/islocal} {#.*}.*{/.*}
        $content = preg_replace_callback("/{#([a-zA-Z0-9]+)}(.*){\/\g1}/Usi", function ($match) use($viewmodel)
        {
            // echo '<pre>';print_r($match);print_r($viewmodel);echo '</pre>';
            
            if ($viewmodel->{$match[1]} != "") {
                return trim($match[2]);
            }
            return "";
        }, $aContent);
        
        // NOT blocks eg. {!islocal}prod stuff...{/islocal}
        $content = preg_replace_callback("/{!([a-zA-Z0-9]+)}(.*){\/\g1}/Usi", function ($match) use($viewmodel)
        {
            // echo '<pre>';print_r($match);print_r($viewmodel);echo '</pre>';
            
            if ($viewmodel->{$match[1]} == "") {
                return trim($match[2]);
            }
            return "";
        }, $content);
        
        return $content;
    }

    /**
     * Replace all variables
     *
     * @param string $aContent            
     *
     * @return string the new content
     */
    private function replaceVars($aContent)
    {
        $viewmodel = $this;
        return preg_replace_callback("/{([a-z0-9]+)}/Ui", function ($match) use($viewmodel)
        {
            // echo '<pre>';print_r($viewmodel);echo'</pre>';
            $var = $match[1];
            if (strstr($var, ".")) { // is object?
                $parts = explode(".", $var);
                $obj = $viewmodel->{$parts[0]};
                if (! is_object($obj)) {
                    return "";
                } else 
                    if (preg_match("/.*\(\)/i", $var)) { // method call
                        $method = preg_replace("/\(\)/", "", $parts[1]);
                        $result = $obj->{$method}();
                    } else {
                        $result = $viewmodel->{$parts[count($parts) - 1]};
                    }
                
                return $result;
            } else {
                // just a normal variable
                return $viewmodel->{$var};
            }
        }, $aContent);
    }
}