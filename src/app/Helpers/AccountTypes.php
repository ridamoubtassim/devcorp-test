<?php


namespace App\Helpers;

class AccountTypes
{
    /**
     * Admin role NO
     * @const int
     */
    const TYPE_ADMIN = 1;

    /**
     * Team role NO
     * @const int
     */
    const TYPE_TEAM = 2;

    /**
     * Get Account types
     *
     * @return string[] List of account types and their label
     */
    public static function getAccountTypes(): array
    {
        return [
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_TEAM => 'Team',
        ];
    }

    /**
     * Get Account type keys
     * @return array
     */
    public static function getAccountTypeKeys(): array
    {
        return array_keys(self::getAccountTypes());
    }

    /**
     * Get Abilities
     *
     * @return array
     */
    public static function getAbilities(): array
    {
        return [
            'manage-users'
        ];
    }

    /**
     * Check if account type has the given role.
     *
     * @param int $accountType
     * @param string $role
     * @return bool  true if the account type has the given role false otherwise.
     */
    public static function hasRole(int $accountType, string $role): bool
    {
        $allowedRoles = [
            self::TYPE_ADMIN => [
                // Admin
                'manage-users',
            ],
            self::TYPE_TEAM => [
                // Team
            ],
        ];
        return in_array($role, $allowedRoles[$accountType] ?? []);
    }
}
