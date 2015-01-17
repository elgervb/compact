<?php
namespace compact\auth\impl;

use compact\auth\IAuthService;
use compact\auth\ILoginProvider;
use compact\Context;
use compact\auth\user\UserModel;

class SessionAuth implements IAuthService
{

    const SESSION_USER = "SessionUser";

    /**
     *
     * @var ILoginProvider
     */
    private $provider;

    /**
     *
     * @var \compact\http\HttpSession
     */
    private $session;

    /**
     * Constructor
     *
     * @param ILoginProvider $aProvider            
     */
    public function __construct(ILoginProvider $aProvider)
    {
        $this->provider = $aProvider;
        
        $this->session = Context::get()->http()->getSession();
        if (! $this->session->isStarted()) {
            $this->session->start();
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::getUser()
     */
    public function getUser()
    {
        $user = $this->session->get(self::SESSION_USER);
        if ($user && is_object($user)) {
            $id = $user->{UserModel::ID};
            // delegate to the provider, as the user could have been changed
            return $this->provider->getUser($id);
        }
        
        return null;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::isLoggedIn()
     */
    public function isLoggedIn()
    {
        return $this->session->exists(self::SESSION_USER);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::login()
     */
    public function login($username, $password)
    {
        $model = $this->provider->login($username, $password);
        if ($model) {
            $this->session->set(self::SESSION_USER, $model);
        }
        
        return $model;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\IAuthService::logout()
     */
    public function logout()
    {
        $this->session->remove(self::SESSION_USER);
        $this->session->destroy();
    }
}