<?php

namespace App\Models;

use Config\Database;
use App\Models\Model;
use PDO;

class CalendarStatus extends Model
{

    /**
     * Find by id function
     *
     * @param Number $id Status ID
     *
     * @return CalendarStatus
     */
    public static function find($id)
    {
        try {
            $pdo = Database::getInstance();
            $sql = "SELECT * FROM event_status where id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
            $status = $statement->fetch(PDO::FETCH_OBJ);
            return $status;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
}
