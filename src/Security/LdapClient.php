<?php


namespace App\Security;


use Symfony\Component\Ldap\Ldap;

class LdapClient
{

    private $connectionString;
    private $bindDn;
    private $bindPassword;
    private $baseDn;
    private $userKey;
    private $authDn;

    /**
     * LdapClient constructor.
     * @param string $connectionString
     * @param string $bindDn
     * @param string $bindPassword
     * @param string $baseDn
     * @param string $userKey
     * @param string $authDn
     */
    public function __construct(string $connectionString, string $bindDn, string $bindPassword, string $baseDn, string $userKey, string $authDn)
    {
        $this->connectionString = $connectionString;
        $this->bindDn           = $bindDn;
        $this->bindPassword     = $bindPassword;
        $this->baseDn           = $baseDn;
        $this->userKey          = $userKey;
        $this->authDn           = $authDn;
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

    public function findUser($key)
    {
        $key        = str_replace("*", "\*", $key);
        $connection = $this->connect($this->bindDn, $this->bindPassword);
        $connection->escape($key);
        $res     = $connection->query($this->baseDn, "(&(objectclass=*)(" . $this->userKey . "=$key))");
        $entries = $res->execute();
        dd($entries->toArray());
    }

    public function authenticateUser($username, $password)
    {
        $connectionString = str_replace("{username}", $username, $this->authDn);
        $this->connect($connectionString, $password);
    }


}