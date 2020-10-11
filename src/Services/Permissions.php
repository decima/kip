<?php


namespace App\Services;


class Permissions
{
    const TYPE_EDIT = "edit";
    const TYPE_DELETE = "delete";
    const PERMISSION_RW = "rw";
    const PERMISSION_RO = "ro";
    private $globalPermission;

    /**
     * Permissions constructor.
     * @param $globalPermission
     */
    public function __construct($globalPermission)
    {
        $this->globalPermission = $globalPermission;
    }

    public function isGranted($type)
    {
        switch ($type) {
            case self::TYPE_EDIT:
            case self::TYPE_DELETE:
                return $this->globalPermission !== self::PERMISSION_RO;

        }
        return true;

    }
}