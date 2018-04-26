<?php

namespace Model;

use App\Connection as Connection;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    #initial state : explicitly unset
    protected static $pdoConnection = null;

    protected $table;
    protected $className;

    /**
     *  Initializes Manager Abstract class.
     *
     * @param string $table Table name of current model
     * @param Connection $pdoConnection in case we want the manager class to use
     *                   a different connection. By default, it is created at first call
     *                   if none specified
     */

    public function __construct(string $table, Connection $pdoConnection = null)
    {
        if (null != $pdoConnection) {
            #if the parameter $pdoConnection is set, use this connection,
            static::$pdoConnection = $pdoConnection;
        } elseif (null == static::$pdoConnection) {
            #else create a new connection shared by every child classes
            static::$pdoConnection = (new Connection())->getPdoConnection();
        }
        self::$pdoConnection = (new Connection())->getPdoConnection();
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . $table;
    }

    /**
     * Get all row from database.
     *
     * @param string|null $orderBy optional "order by" parameter for the SQL request
     * @return array
     */

    public function selectAll($orderBy = null): array

    {
        $queryString = 'SELECT * FROM ' . $this->table;
        if (null != $orderBy) {
            $queryString .= ' ORDER BY ' . (static::$pdoConnection)->quote($orderBy);
        }

        return static::$pdoConnection->query(
            $queryString,
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
        $statement = $this::$pdoConnection->prepare("SELECT * FROM " . $this->table . " WHERE id =:id");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
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
        foreach ($data as $key => $value) {
            // prepared request
            $statement = $this::$pdoConnection->prepare("UPDATE $this->table 
                                                                    SET `$key` = '$value' 
                                                                    WHERE `id` = $id");
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
        }
    }

    public function insert(array $data)
    {
        foreach ($data as $key => $value) {
            // prepared request
            $statement = $this::$pdoConnection->prepare("INSERT INTO $this->table 
                                                                     (`$key`) VALUES ('$value')");
            $statement->execute();
        }
    }
}
