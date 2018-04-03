<?php
namespace Model;

class ArtistManager extends AbstractManager
{
    const TABLE = 'artists';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE, 'Artist');
//TODO : S'assurer que ttes les tables dépendantes soient chargées ...
// → les initialiser !?
    }
}
