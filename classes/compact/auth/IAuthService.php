<?php
namespace compact\auth;

/**
 * Interface for the Auth Service
 *
 * @author elger
 */
interface IAuthService
{

    /**
     * Returns the logged in user
     *
     * @return \compact\auth\user\UserModel the user model, or null when not logged in
     */
    public abstract function getUser();

    /**
     * Checks if the user is logged in
     *
     * @return boolean true if the user is logged in, false if not
     */
    public abstract function isLoggedIn();

    /**
     * Logs out the currently logged in user
     *
     * @return void
     */
    public function logout();

    /**
     * Signs in the user with supplied credentials
     *
     * @param string $username
     *            The username
     * @param string $password
     *            The plain text password
     *            
     * @return boolean true if the user could succesfully log in, false if not
     */
    public function login($username, $password);
}