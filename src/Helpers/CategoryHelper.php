<?php

namespace App\Helpers;

use App\Entity\User;

class CategoryHelper
{
    const BABY_CATEGORY = 'BABY';
    const U6_CATEGORY = 'U6';
    const U8_CATEGORY = 'U8';
    const U10_CATEGORY = 'U10';
    const U12_CATEGORY = 'U12';
    const U14_CATEGORY = 'U14';

    public static function getCategoriesFromRoles(array $roles): array
    {
        $categories = [];
        foreach ($roles as $role) {
            switch ($role) {
                case User::ROLE_SECRETARY_BABY:
                    $categories[] = self::BABY_CATEGORY;
                    break;
                case User::ROLE_SECRETARY_U6:
                    $categories[] = self::U6_CATEGORY;
                    break;
                case User::ROLE_SECRETARY_U8:
                    $categories[] = self::U8_CATEGORY;
                    break;
                case User::ROLE_SECRETARY_U10:
                    $categories[] = self::U10_CATEGORY;
                    break;
                case User::ROLE_SECRETARY_U12:
                    $categories[] = self::U12_CATEGORY;
                    break;
                case User::ROLE_SECRETARY_U14:
                    $categories[] = self::U14_CATEGORY;
                    break;
                default:
                    // Do nothing, retrieve all users
                    break;
            }
        }
        return $categories;
    }
}