<?php


namespace App\Security;


use App\Entity\User;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LdapClient
{

    private $connectionString;
    private $bindDn;
    private $bindPassword;
    private $baseDn;
    private $userKey;

    /**
     * LdapClient constructor.
     * @param string $connectionString
     * @param string $bindDn
     * @param string $bindPassword
     * @param string $baseDn
     * @param string $userKey
     * @param string $authDn
     */
    public function __construct(string $connectionString, string $bindDn, string $bindPassword, string $baseDn, string $userKey)
    {
        $this->connectionString = $connectionString;
        $this->bindDn           = $bindDn;
        $this->bindPassword     = $bindPassword;
        $this->baseDn           = $baseDn;
        $this->userKey          = $userKey;
    }


    private function createConnection()
    {
        return Ldap::create('ext_ldap', [
            'connection_string' => $this->connectionString,
        ]);
    }

    private function connect($authDn, $password): Ldap
    {
        $connection = $this->createConnection();
        $connection->bind($authDn, $password);
        return $connection;
    }

    public function findUser($key): ?User
    {
        $key        = str_replace("*", "\*", $key);
        $connection = $this->connect($this->bindDn, $this->bindPassword);
        $connection->escape($key);
        $res     = $connection->query($this->baseDn, "(&(objectclass=*)(" . $this->userKey . "=$key))");
        $entries = $res->execute();

        $items = $entries->toArray();
        if (count($items) !== 1) {
            $exception = new UsernameNotFoundException();
            $exception->setUsername($key);
            throw $exception;
        }
        $userEntry = $items[0];
        $user      = new User();
        $user->setUsername($userEntry->getAttribute($this->userKey)[0]);
        $user->setUserDn($userEntry->getDn());
        return $user;
    }

    public function authenticateUser($username, $password): bool
    {
        try {
            $this->connect($username, $password);
            return true;
        } catch (ConnectionException $connectionException) {
            return false;
        }
    }
}