<?php
namespace compact\auth\provider;

use compact\repository\pdo\AbstractPDORepository;
use compact\auth\user\UserModel;
use compact\auth\ILoginProvider;

/**
 * Adapter around AbstractPDORepository to provide login functionality
 *
 * @author elger
 */
class PDOAuthProvider implements ILoginProvider
{

    private $db;

    /**
     * Constructor
     * 
     * @param AbstractPDORepository $db
     */
    public function __construct(AbstractPDORepository $db)
    {
        $this->db = $db;
    }

    /**
     * (non-PHPdoc)
     *
     * @see extensions\auth.IAuthProvider::login()
     */
    public function login($aUsername, $aPassword)
    {
        $sc = $this->db->createSearchCriteria();
        $sc->where(UserModel::USERNAME, $aUsername);
        $sc->where(UserModel::PASSWORD, $aPassword);
        
        $result = $this->db->search($sc);
        if ($result instanceof \ArrayObject && $result->count() > 0) {
            /* @var $result \ArrayObject */
            return $result->offsetGet(0);
        }
        
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\auth\ILoginProvider::getUser()
     */
    public function getUser($id)
    {
        return $this->db->read($id);
    }
}