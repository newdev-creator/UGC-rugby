<?php

namespace App\Helpers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConnectedUserByRoles extends AbstractController
{
    public function connectedUser(): array
    {
        // Array for store roles of connected user
        $rolesUser = [];
        $connectedUser = $this->getUser();
        // for each role of connected user, add it to the array
        foreach ($connectedUser->getRoles() as $role) {
            $rolesUser[] = $role;
        }
        return $rolesUser;
    }
}