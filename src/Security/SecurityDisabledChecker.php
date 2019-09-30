<?php


namespace App\Security;



/**
 * Class SecurityDisabled
 * @package App\Security
 * This class is here to check if the security is disabled.
 * To add more security providers, don't forget to add the settings here and in @see services.yaml
 */
class SecurityDisabledChecker
{
const LANGUAGE_EXPRESSION_FUNCTION_NAME="isSecurityDisabled";

    private $ldapAuth = false;
    private $dbAuth = false;

    /**
     * SecurityChecker constructor.
     * @param bool $ldapAuth
     * @param bool $dbAuth
     */
    public function __construct(bool $ldapAuth, bool $dbAuth)
    {
        $this->ldapAuth = $ldapAuth;
        $this->dbAuth   = $dbAuth;
    }

    public function check()
    {
        return !($this->ldapAuth || $this->dbAuth);
    }


}