<?php
namespace Model;

/**
 * Class Item
 *
 */
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
        static::$styles = [];

        //build $styles as an associative array using Artist::id as index
        foreach ((new StyleManager())->selectAll('id') as $object) {
            static::$styles[ $object->getId() ] = $object;
        }
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
        $style = static::$styles[$this->id_style];
        if ($style) {
            return $style->getName();
        } else {
            return 'Index de style erroné : ' . $this->id_style;
        }
    }

    public function getImageURL()
    {
        return $this->picture;
    }

    public function getDescription()
    {
        return $this->about;
    }
}
