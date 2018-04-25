<?php
namespace Model;

/**
 * Class Item
 *
 */


Class Article
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

    public function getContent() : string
    {
        return $this->content;
    }
    public function getPicture(): string
	{
		return $this->picture; 
	}

}