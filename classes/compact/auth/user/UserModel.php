<?php
namespace compact\auth\user;

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
     * Some room for additional context. This could be anything from a serialized object to an empty value
     */
    const CONTEXT = "context";
    /**
     * Denotes if the user is active or not
     */
    const IS_ACTIVE = "isactive";
}
