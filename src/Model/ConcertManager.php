<?php
namespace Model;

/**
 *
 */
class ConcertManager extends AbstractManager
{
    const TABLE = 'concerts';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        Concert::initStatics();
        parent::__construct(self::TABLE, 'Concert');
    }
}