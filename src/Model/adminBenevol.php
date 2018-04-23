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
        return $this->id_volunteer;
    }

    function setId()
    {
        return $this->id_volunteer;
    }

    function getPhoto()
    {
        return $this->name;
    }

    function setPhoto()
    {
        return $this->name;
    }

    function getArticle()
    {
        return $this->surname;
    }

    function setArticle()
    {
        return $this->surname;
    }
}