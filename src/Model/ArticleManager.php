<?php
namespace Model;

use Model\Article;

/**
 *
 */
class ArticleManager extends AbstractManager
{
    const TABLE = 'Article';
    private $articles;
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
