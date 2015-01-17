<?php
namespace compact\auth\impl;

use compact\auth\ILoginProvider;
use compact\auth\IAuthService;

/**
 * <code><pre>
 * <IfModule mod_rewrite.c>
 *   RewriteEngine on
 *   RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]
 * </IfModule>
 * </pre></code>
 */
class HttpAuth implements IAuthService
{

    private $realm;

    /**
     * The user which has been logged in
     *
     * @var UserModel
     */
    private $user;

    /**
     *
     * @var ILoginProvider
     */
    private $provider;

    /**
     * Constructor
     */
    public function __construct(ILoginProvider $provider, $aRealm = "private")
    {
        $this->provider = $aProvider;
        $this->realm = $aRealm;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::getUser()
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::isLoggedIn()
     */
    public function isLoggedIn()
    {
        return $this->user != null;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::login()
     */
    public function login($aUsername, $aPassword)
    {
        return $this->authenticate();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::logout()
     */
    public function logout()
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION']))
            $_SERVER['HTTP_AUTHORIZATION'] = null;
        if (isset($_SERVER['PHP_AUTH_USER']))
            $_SERVER['PHP_AUTH_USER'] = null;
        if (isset($_SERVER['PHP_AUTH_PW']))
            $_SERVER['PHP_AUTH_PW'] = null;
            
            // and send the headers...
        $this->authenticate();
    }

    /**
     * Authenticate the user
     */
    public function authenticate()
    {
        // workaround for PHP in CGI mode with a .htaccess fix
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && strstr($_SERVER['HTTP_AUTHORIZATION'], ":") && ! isset($_SERVER['PHP_AUTH_USER']) && ! isset($_SERVER['PHP_AUTH_USER'])) {
            list ($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }
        
        if (! isset($_SERVER['PHP_AUTH_USER']) || ! isset($_SERVER['PHP_AUTH_PW'])) {
            $this->sendAuthHeaders();
        } else {
            $username = md5($this->forceUtf8($_SERVER['PHP_AUTH_USER']));
            $password = md5($this->forceUtf8($_SERVER['PHP_AUTH_PW']));
            
            $this->user = $this->provider->login($username, $password);
            if ($this->user) {
                // authenticated !!
                return true;
            }
            
            $this->sendAuthHeaders();
        }
    }

    /**
     * Send the require authentication headers
     *
     * @param $realm string
     *            Nachrichtentext
     * @return string
     */
    private function sendAuthHeaders()
    {
        header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
        header('HTTP/1.0 401 Unauthorized');
        
        exit();
    }

    /**
     * Checks for UTF-8
     *
     * RegEx from Martin Dürst
     *
     * @see http://www.w3.org/International/questions/qa-forms-utf-8.html
     *
     * @see http://toscho.de/2009/utf-8-erzwingen/
     * @param
     *            aStr String to check
     * @return boolean
     */
    private function isUtf8($aStr)
    {
        return preg_match("/^( #
		[\x09\x0A\x0D\x20-\x7E] # ASCII
		| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
		)*$/x", $aStr);
    }

    /**
     * Encode a string into UTF-8
     *
     * @param $aStr The
     *            string to encode
     * @param $aInputEnc string
     *            The input encoding
     *            
     * @return string
     */
    private function forceUtf8($aStr, $aInputEnc = 'WINDOWS-1252')
    {
        if ($this->isUtf8($aStr)) {
            return $aStr;
        }
        
        if (strtoupper($aInputEnc) == 'ISO-8859-1') {
            return utf8_encode($aStr);
        }
        
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($aStr, 'UTF-8', $aInputEnc);
        }
        
        if (function_exists('iconv')) {
            return iconv($aInputEnc, 'UTF-8', $aStr);
        }
        return $aStr;
    }
}