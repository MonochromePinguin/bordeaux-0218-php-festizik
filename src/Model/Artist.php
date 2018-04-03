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
    private $idStyle;
    private $about;
    private $picture;

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
     * @param string Name
     * @return string
     */
    public function setName(string $name): string
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdStyle(): int
    {
        return $this->idStyle;
    }

    /**
     * @param int IdStyle
     * @return Artist
     */
    public function setIdStyle(int $idStyle): int
    {
//TODO : add verifications ...
        $this->idStyle = $idStyle;
        return $this;
    }

//TODO : add setStyle(string), getStyle(string) avec tt la logique
// liant cette classe à Style ?
}
