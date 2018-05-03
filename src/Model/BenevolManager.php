<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace Model;

/**
 *
 */
class BenevolManager extends AbstractManager
{
    const TABLE = 'Volunteers';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

     public function selectNameId()
    {
        return static::$pdoConnection->query("SELECT * FROM $this->table", \PDO::FETCH_ASSOC)->fetchAll();
    }
     /**
     * INSERT one row in dataase
     *
     * @param Array $data
     */
    public function insertBenevol(array $data)
    {
        $statement = $this::$pdoConnection->prepare("INSERT INTO $this->table (name, surname, phone, disponibility_start, disponibility_end) VALUES (:name, :surname, :phone, :dispoStart, :dispoEnd)");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':surname', $data['surname'], \PDO::PARAM_STR);
        $statement->bindValue(':phone', $data['phone'], \PDO::PARAM_STR);
        $statement->bindValue(':dispoStart', $data['dispoStart']);
        $statement->bindValue(':dispoEnd', $data['dispoEnd']);
        $statement->execute();
        }

    public function deleteBenevol($id)
    {
        $statement = $this::$pdoConnection->prepare("DELETE FROM $this->table WHERE id = :id");
        $statement->bindValue(':id', $id);
        $statement->execute();
    }



}
