<?php
namespace App\Models;

use Config\Database;
use App\Models\Model;
use PDO;
use DateTime;
use App\Models\CalendarStatus;

class Calendar extends Model
{
    protected $fillable = [
        "name", "start_date", "end_date", "status"
    ];

    const PLANNING = 1;

    const DOING = 2;

    const COMPLETE = 3;
    
    /**
     * Constructor function
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Find all Calendar
     *
     * @return Array Calendar
     */
    public static function getAll()
    {
        try {
            $pdo = Database::getInstance();
            $sql = "SELECT events.*, event_status.color FROM events ";
            $sql .= " join event_status on events.status = event_status.id";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $error) {
            echo $sql."---".$error->getMessage();
        }
    }

    /**
     * Find by id function
     *
     * @param Number $id Calendar id
     *
     * @return Calendar
     */
    public static function find($id)
    {
        try {
            $pdo = Database::getInstance();
            $sql = "SELECT * FROM events where id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
            $event = $statement->fetch(PDO::FETCH_OBJ);
            return $event;
        } catch (PDOException $error) {
            echo $sql."---".$error->getMessage();
        }
    }

    /**
     * Update Calendar function
     *
     * @param Number $id         Calendar id
     * @param Array  $attributes Attributes
     *
     * @return void
     */
    public static function update($id, $attributes = null)
    {
        try {
            $pdo = Database::getInstance();
            $param = [];
            $self = new static;
            foreach ($self->fillable as $key) {
                $param[] = $key ."= :".$key;
            }
            $attributes["id"] = $id;
            $sql = "UPDATE events SET " . implode(",", $param)." WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute($attributes);
        } catch (PDOException $error) {
            echo $sql."---".$error->getMessage();
        }
    }

    /**
     * Delete Calendar function
     *
     * @param Number $id Calendar id
     *
     * @return Void
     */
    public static function destroy($id)
    {
        try {
            $pdo = Database::getInstance();
            $sql = "DELETE FROM events WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
        } catch (PDOException $error) {
            echo $sql."---".$error->getMessage();
        }
    }

    /**
     * Create Calendar function
     *
     * @param Array $attributes Atributes
     *
     * @return Calendar
     */
    public static function create($attributes)
    {
        try {
            $self = new static;
            $pdo = Database::getInstance();
            $param = [];
            foreach ($self->fillable as $key) {
                $param[] = ":".$key;
            }
            $sql = "INSERT INTO events (".implode(",", $self->fillable).") values (".implode(",", $param).")";
            $statement = $pdo->prepare($sql);
            $statement->execute($attributes);
            $id = $pdo->lastInsertId();
            return $id;
        } catch (PDOException $error) {
            throw new Exception($error->getMessage());
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    /**
     * Validate data
     *
     * @return void
     */
    public function validationData()
    {
        $now = new DateTime();
        $start = date_create($this->start_date);
        $end = date_create($this->end_date);
        if ($now > $end) {
            $this->status = self::COMPLETE;
        } elseif ($now < $start) {
            $this->status = self::PLANNING;
        } else {
            $this->status = self::DOING;
        }
    }

    /**
     * Fill all atribute
     *
     * @param array $attributes Atributes value
     *
     * @return void
     */
    public function fill($attributes = [])
    {
        foreach ($this->fillable as $key) {
            $this->{$key} = empty($attributes[$key]) ? null : $attributes[$key];
        }
        $this->validationData();
    }

    /**
     * Save object function
     *
     * @return Calendar
     */
    public function save()
    {
        $data = [];
        foreach ($this->fillable as $key) {
            $data[$key] = $this->{$key};
        }
        if (empty($this->id)) {
            $id = self::create($data);
            $this->id = $id;
        } else {
            self::update($this->id, $data);
        }
        return $this;
    }

    /**
     * Delete function
     *
     * @return void
     */
    public function delete()
    {
        self::destroy($this->id);
    }

    /**
     * Get all data function
     *
     * @return Array
     */
    public function all()
    {
        return self::getAll();
    }
}
