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
}
