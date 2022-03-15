<?php

declare(strict_types=1);

namespace Canvas\Traits;

trait HasRole
{
    /**
     * Role identifier used for a Contributor.
     *
     * @var int
     */
    public static $contributor = 1;

    /**
     * Role identifier used for an Editor.
     *
     * @var int
     */
    public static $editor = 2;

    /**
     * Role identifier used for an Admin.
     *
     * @var int
     */
    public static $admin = 3;

    /**
     * Return an array of available user roles.
     *
     * @return array
     */
    public static function availableRoles(): array
    {
        return [
            self::$contributor => 'Contributor',
            self::$editor => 'Editor',
            self::$admin => 'Admin',
        ];
    }
}
