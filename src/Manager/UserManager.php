<?php

namespace App\Manager;

use SleekDB\SleekDB;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager extends AbstractManager
{
    const DB_TYPE = 'user';

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Exception
     */
    public function createUser(string $email, string $password): array
    {
        $userDB = $this->getStore(self::DB_TYPE);

        $user = $userDB->insert(['email' => $email, 'password' => $password]);

        if (false === $user) {
            throw new \Exception('Error during user creation');
        }

        return $user;
    }

    /**
     * @param string $email
     * @param string|null $password
     * @return array|null
     * @throws \Exception
     */
    public function getUser(string $email, string $password = null): ?array
    {
        $userDB = $this->getStore(self::DB_TYPE);

        $stmt = $userDB->where( 'email', '=', $email );

        if ($password) {
            $stmt->where('password', '=', $password);
        }

        $user = $stmt->fetch();

        if (!empty($user) && !empty($user[0]))
        {
            return $user[0];
        }

        return null;
    }
}