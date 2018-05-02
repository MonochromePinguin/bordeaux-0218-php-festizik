<?php
namespace Model;

/**
 *
 */
class DayManager extends AbstractManager
{
    const TABLE = 'Day';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Insert ONE row into the DB
     * @param array $values an associative array storing the values for the new row
     * @return bool the result of PDOPreparedStatement::execute()
     */
    public function insert(array $values): bool
    {
        $query = static::$pdoConnection->prepare("INSERT INTO $this->table ( date ) VALUES ( :date )");
        $query->bindValue(':date', $values['date'], \PDO::PARAM_STR);
        return $query->execute();
    }

    /**
     * @param string $rawDate a date in the standard (HTML, SQL) numeric format (Year-month-day)
     * @return int the id of the day
     */
    public function getIdOfDate(string $rawDate): int
    {
        $query = static::$pdoConnection->prepare("SELECT id FROM $this->table WHERE date = :date");
        $query->bindValue(':date', $rawDate, \PDO::PARAM_STR);
        $query->setFetchMode(\PDO::FETCH_COLUMN, 0);
        $query->execute();

        return $query->fetch();
    }
}
