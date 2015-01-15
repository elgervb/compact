<?php
/**
 * @author eaboxt
 */
namespace compact;

/**
 * Loads all classes from the classpath, using the set_include_path.
 * Upon creating it also sets the current directory to the classpath
 */
class ClassLoader
{

    /**
     * Creates and registered a new Classloader
     */
    public function __construct()
    {
        $this->register();
        $this->addClassPath(realpath(__DIR__ . '/..'));
    }

    /**
     * Static factory method to create a new Classloader
     *
     * @return \compact\ClassLoader
     */
    public static function create()
    {
        return new ClassLoader();
    }

    /**
     * Add a path to the classpath
     *
     * @param $aPath string
     *            The path to add to the includes
     * @param $aPrepend string
     *            use only when the entry must be included prior to all others
     *            
     * @return \compact\ClassLoader $this for chaining
     */
    public function addClassPath($aPath, $aPrepend = false)
    {
        $path = $aPrepend ? realpath($aPath) . PATH_SEPARATOR . get_include_path() : get_include_path() . PATH_SEPARATOR . realpath($aPath);
        
        set_include_path($path);
        
        return $this;
    }

    /**
     * Registers the classloader using the <code>spl_autoload_register</code> method
     *
     * @return \compact\ClassLoader $this for chaining
     */
    public function register()
    {
        spl_autoload_extensions(".php");
        
        /*
         * First register our own autoload handler. As the default implementation will convert classnames to lowercase ... on Windows this works perfectly, but on *NIX systems this causes seriour problems
         */
        spl_autoload_register(array(
            $this,
            'autoLoad'
        ));
        spl_autoload_register();
    }

    /**
     * The actual autoload handler
     *
     * @param $aClassName String            
     *
     * @return string the path to the included file
     */
    public function autoLoad($aClassName)
    {
        $file = DIRECTORY_SEPARATOR . $this->replaceSlashes($aClassName) . ".php";
        
        // load from stream
        if (function_exists('stream_resolve_include_path')) {
            $resolved = stream_resolve_include_path($file);
            if ($resolved) {
                require $resolved;
                return $resolved;
            }
        }
        
        // check if file exists
        if (is_file($file)) {
            require realpath($file);
            return realpath($file);
        }
        
        // search the include paths separately
        $paths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($paths as $path) {
            $fullpath = $this->replaceSlashes($path . DIRECTORY_SEPARATOR . $file);
            
            if (file_exists($fullpath)) {
                require realpath($fullpath);
                return realpath($fullpath);
            }
        }
    }

    /**
     * Replace slases, making
     *
     * @param unknown $aPath            
     * @return mixed
     */
    private function replaceSlashes($aPath)
    {
        $path = str_replace("\\\\", "\\", $aPath);
        $path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $aPath);
        return str_replace("\\", DIRECTORY_SEPARATOR, $path);
    }
}