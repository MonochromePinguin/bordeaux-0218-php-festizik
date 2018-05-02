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
        return static::$pdoConnection->query("SELECT Artist.id, Artist.name AS AN, Style.name AS SN FROM Artist JOIN Style ON id_style = Style.id ORDER BY Artist.name", \PDO::FETCH_ASSOC)->fetchAll();
    }

    public function insert()
    {
        // prepared request
        $statement = $this::$pdoConnection->prepare("INSERT INTO $this->table (name, id_style, about, picture ) VALUES (:name, :style, :about, :picture)");
        $statement->bindValue(':name', $_POST['name']);
        $statement->bindValue(':style', $_POST['id_style']);
        $statement->bindValue(':about', $_POST['about']);
        $statement->bindValue(':picture', '/assets/DBimages/'.$_POST['picture']);
        $statement->execute();
    }
}
