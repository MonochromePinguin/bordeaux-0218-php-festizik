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
}
