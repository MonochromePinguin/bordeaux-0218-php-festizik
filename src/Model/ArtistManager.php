<?php

namespace Model;

class ArtistManager extends AbstractManager
{
    const TABLE = 'Artist';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        Artist::initStatics();
        parent::__construct(self::TABLE);
    }

    public function selectNameId()
    {
        return static::$pdoConnection->query("SELECT id, name, id_style FROM " . $this->table . " ORDER BY name", \PDO::FETCH_ASSOC)->fetchAll();
    }


}
