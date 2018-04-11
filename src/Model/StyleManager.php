<?php
namespace Model;

class StyleManager extends AbstractManager
{
    const TABLE = 'styles';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE, 'Style');
    }
}
