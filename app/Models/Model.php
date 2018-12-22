<?php

namespace App\Models;

use Config\Database;

abstract class Model
{
    protected $pdo;

    /**
     * Constructor function
     *
     * @param Database $pdo Database
     *
     * @return void
     */
    protected function __construct(Database $pdo)
    {
        $this->pdo = $pdo;
    }
}
