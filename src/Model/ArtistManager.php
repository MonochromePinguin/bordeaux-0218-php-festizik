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
        Artist::initStatics();
        parent::__construct(self::TABLE, 'Artist');
    }
}
