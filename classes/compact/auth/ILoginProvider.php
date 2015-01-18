<?php
namespace compact\auth;

interface ILoginProvider
{

    /**
     * Returns the logged in user
     *
     * @param int $id The ID of the user
     *
     * @return \compact\auth\user\UserModel the user model, or null when not logged in
     */
    public function getUser($id);

    /**
     * Signs in the user with supplied credentials
     *
     * @param string $username
     *            The username
     * @param string $password
     *            The plain text password
     *            
     * @return UserModel|false The usermodel when successfully logged in or false when the user could nog be found
     */
    public function login($username, $password);
}
