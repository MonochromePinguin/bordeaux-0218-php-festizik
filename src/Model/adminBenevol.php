<?php
/**
 * Created by PhpStorm.
 * User: wcs
 * Date: 23/10/17
 * Time: 10:57
 * PHP version 7
 */

namespace Model;

/**
 * Class Item
 *
 */
class adminBenevol
{
    private $id;

    private $photo;

    private $article;

    /**
     * @return int
     */
     function __construct($id, $photo, $article)
    {
         $this->id = $id;
         $this->photo = $photo;
         $this->article = $article;
    }

    function getId()
    {
        return $this->id;
    }

    function setId()
    {
        return $this->id;
    }

    function getPhoto()
    {
        return $this->photo;
    }

    function setPhoto()
    {
        return $this->photo;
    }

    function getArticle()
    {
        return $this->article;
    }

    function setArticle()
    {
        return $this->article;
    }
}