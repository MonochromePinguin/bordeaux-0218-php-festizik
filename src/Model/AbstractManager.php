<?php

namespace Model;

use App\Connection;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
 //TODO : One connection to fetch them all
    protected $pdoConnection;

    protected $table;
    protected $className;

    /**
     *  Initializes Manager Abstract class.
     *
     * @param string $table Table name of current model
     */
//TODO : add as parameter the PDO connection, so we create only one connection
# per page load ...
// My little heart is bleeding because my colleagues fear I keep the 2nd
# parameter «$className» to the __constructor(c$table, $className),
# to have totally decoupled class names and table names ...
# As if it were hard work! I'm soooo downbeated!
    public function __construct(string $table)
    {
        $connexion = new Connection();
        $this->pdoConnection = $connexion->getPdoConnection();
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . $table;
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
// TODO : ADD THE "ORDER BY" as an optional parameter
    public function selectAll() : array
    {
        return $this->pdoConnection->query(
            'SELECT * FROM ' . $this->table,
            \PDO::FETCH_CLASS,
            $this->className
        )->fetchAll();
    }

    /**
     * Get one row from database by ID.
     *
     * @param  int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdoConnection->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * DELETE on row in dataase by ID
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        //TODO : Implements SQL DELETE request
    }

    /**
     * @param int   $id   Id of the row to update
     * @param array $data $data to update
     */
    public function update(int $id, array $data)
    {
        //TODO : Implements SQL UPDATE request
    }
}
