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
    public static int $contributor_id = 1;

    /**
     * Role name used for a Contributor.
     *
     * @var string
     */
    public static string $contributor_name = 'Contributor';

    /**
     * Role identifier used for an Editor.
     *
     * @var int
     */
    public static int $editor_id = 2;

    /**
     * Role name used for an Editor.
     *
     * @var string
     */
    public static string $editor_name = 'Editor';

    /**
     * Role identifier used for an Admin.
     *
     * @var int
     */
    public static int $admin_id = 3;

    /**
     * Role name used for an Admin.
     *
     * @var string
     */
    public static string $admin_name = 'Admin';

    /**
     * Return an array of available user roles.
     *
     * @return array
     */
    public static function roles(): array
    {
        return [
            static::$contributor_id => static::$contributor_name,
            static::$editor_id => static::$editor_name,
            static::$admin_id => static::$admin_name,
        ];
    }
}
