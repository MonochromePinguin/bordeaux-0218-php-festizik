<?php
namespace Model;

/**
 * Class Item
 *
 */
class Artist
{
    private $id_artist;
    private $name;
    private $id_style;
    private $about;
    private $picture;

    private static $styles;

    public static function initStatics() {
        static::$styles = ( new StyleManager() )->selectAll();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_artist;
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
        if ( $style )
            return $style->getName();
        else
            return 'Index de style erroné : ' . $this->id_style;
    }

//     /**
//      * @param string Name
//      * @return string
//      */
//     public function setName(string $name): string
//     {
//         $this->name = $name;
//         return $this;
//     }

//     /**
//      * @return int
//      */
//     public function getIdStyle(): int
//     {
//         return $this->idStyle;
//     }

//     /**
//      * @param int IdStyle
//      * @return Artist
//      */
//     public function setIdStyle(int $idStyle): int
//     {
// //TODO : add verifications ...
//         $this->idStyle = $idStyle;
//         return $this;
//     }

//TODO : add setStyle(string), getStyle(string) avec tt la logique
// liant cette classe à Style ?
}
