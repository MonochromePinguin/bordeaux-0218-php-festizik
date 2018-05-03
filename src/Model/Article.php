<?php
namespace Model;

/**
 * Class Item
 *
 */


class Article
{
    private $id;
    private $title;
    private $id_page;
    private $picture;
    private $content;



     /**
     * @return int
     */
    public function getId(): int
    {

        return $this->id;
    }

   /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function getPicture(): string
	{
		return $this->picture;
	}

        /**
     * @param mixed $name
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $id_style
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

}
