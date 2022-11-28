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
     * Role identifier used for an Editor.
     *
     * @var int
     */
    public static int $editor_id = 2;

    /**
     * Role identifier used for an Admin.
     *
     * @var int
     */
    public static int $admin_id = 3;

    /**
     * Return an array of available user roles.
     *
     * @return array
     */
    public static function roles()
    {
        return [
            static::$contributor_id => trans('canvas::app.contributor'),
            static::$editor_id => trans('canvas::app.editor'),
            static::$admin_id => trans('canvas::app.admin'),
        ];
    }
}
