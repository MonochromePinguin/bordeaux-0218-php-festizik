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
class Style
{
    private $id_style;
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_style;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    // /**
    //  * @param string Name
    //  * @return Item
    //  */
    // public function setName(string $name): string
    // {
    //     $this->name = $name;
    //     return $this;
    // }
}
