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
     * return an unformated string as obtained by the DB engine
     * @return string
     */
    public function getDateAsRaw(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDateAsString(): string
    {
        setlocale(LC_ALL, null);
        return strftime('%A %d %B %Y', strtotime($this->date));
    }


    /**
     * return a (UNIX) timestamp
     * @return int
     */
    public function getDateAsTimestamp(): int
    {
        return strtotime($this->date);
    }
}
