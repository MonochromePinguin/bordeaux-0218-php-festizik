<?php
namespace Model;

class StyleManager extends AbstractManager
{
    const TABLE = 'Style';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectStyle()
    {
        return static::$pdoConnection->query("SELECT id, name FROM " . $this->table . " ORDER BY name", \PDO::FETCH_ASSOC)->fetchAll();
    }
}
