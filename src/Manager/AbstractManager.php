<?php

namespace App\Manager;

use SleekDB\SleekDB;

/**
 * Class AbstractManager
 * @package App\Manager
 */
abstract class AbstractManager
{
    const DB_DIR = __DIR__ . "/../../db";

    /**
     * @param string $storeName
     * @param string $dataDir
     * @return SleekDB
     * @throws \Exception
     */
    public function getStore(string $storeName, string $dataDir = self::DB_DIR): SleekDB
    {
        return SleekDB::store($storeName, $dataDir);
    }
}