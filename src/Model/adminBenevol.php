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
    private $id_article;
    private $title;
    private $id_page;
    private $picture;
    private $content; 

     /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_article;
    }

   /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(): string
    {
        return $this->title;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function setContent(): string
    {
        return $this->content;
    }

    public function getPicture() : string
    {
        return $this->picture;
    }

    public function setPicture(): string
    {
        return $this->picture;
    }
}