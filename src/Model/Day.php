<?php
namespace Model;

/**
 * Class Scene
 *
 */
class Day
{
    private $id_day;
    private $name;
    private $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_day;
    }

//TODO : delete the «name» field unless someone find a good reason to keep it.
//who knows?
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
    public function getDateAsString(): string
    {
        setlocale(LC_ALL, null);
        return strftime('%A %d %B %Y', strtotime($this->date));
    }
}
