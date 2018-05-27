<?php
declare(strict_types=1);

namespace Plugin\Admin\Enum;

class Roles
{
    public const ADMIN = 'admin';
    public const ROLES = [self::ADMIN];

    /**
     * @param string $role
     * @return bool
     */
    public static function valid(string $role): bool
    {
        return \in_array($role, self::ROLES, true);
    }
}