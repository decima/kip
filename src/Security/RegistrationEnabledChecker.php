<?php


namespace App\Security;


class RegistrationEnabledChecker
{
    private $isEnabled = true;
    private $dbAuth = true;

    /**
     * RegistrationEnabledChecker constructor.
     * @param bool $isEnabled
     * @param bool $authDbEnabled
     */
    public function __construct(bool $isEnabled, bool $dbAuth)
    {
        $this->isEnabled = $isEnabled;
        $this->dbAuth    = $dbAuth;
    }


    public function check()
    {
        return $this->isEnabled && $this->dbAuth;
    }

}