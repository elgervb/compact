<?php
namespace compact\auth\user;

/**
 * Model for the user
 *
 * @author elger
 */
class UserModel
{

    /**
     * The (generated) id of the user
     */
    const ID = "id";

    /**
     * The unique GUID of the user
     */
    const GUID = "guid";

    /**
     * The username
     */
    const USERNAME = "username";

    /**
     * The encrypted password of the user
     */
    const PASSWORD = "password";

    /**
     * Some room for additional context.
     * This could be anything from a serialized object to an empty value
     */
    const CONTEXT = "context";

    /**
     * Denotes if the user is active or not
     */
    const ACTIVE = "active";

    /**
     * The IP address from which the user was registered
     * 
     * @var unknown
     */
    CONST IP = "ip";

    /**
     * The full name of the user
     * 
     * @var unknown
     */
    const FULL_NAME = "full_name";

    /**
     * The email address of the user
     */
    const EMAIL = "email";

    /**
     * The timestamp of when the user was added
     */
    const TIMESTAMP = "timestamp";

    /**
     * Is the user an admin?
     */
    const ADMIN = "admin";

    /**
     * The activation GUID when the user is not yet active
     */
    const ACTIVATION = "activation";

    /**
     * Encrypt the password using sha1
     */
    public function encryptPassword()
    {
        $this->{UserModel::PASSWORD} = sha1($this->{UserModel::PASSWORD});
    }
}
