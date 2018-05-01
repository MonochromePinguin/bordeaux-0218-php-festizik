<?php
namespace Model;

class Artist
{
    private $id;
    private $name;
    private $id_style;
    private $about;
    private $picture;

    private static $styles;

    public static function initStatics()
    {
        static::$styles = ( new StyleManager() )->selectAll();
    }

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
    public function getName(): string
    {
        return $this->name;
    }

   /**
     * @return string
     */
    public function getStyle(): string
    {
        $style = static::$styles[$this->id_style -1];
        if ($style) {
            return $this->getName();
        } else {
            return 'Index de style erroné : ' . $this->id_style;
        }
    }

    /**
     * @return string|null
     */
    public function getImageURL()
    {
        return $this->picture;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->about;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $id_style
     */
    public function setIdStyle($id_style)
    {
        $this->id_style = $id_style;
    }

    /**
     * @param mixed $picture
     * @returns
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @param mixed $styles
     */
    public static function setStyles($styles)
    {
        self::$styles = $styles;
    }
}
