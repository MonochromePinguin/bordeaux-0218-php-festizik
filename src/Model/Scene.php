<?php
namespace Model;

/**
 * Class Scene
 *
 */
class Scene
{
    private $id_scene;
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_scene;
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
