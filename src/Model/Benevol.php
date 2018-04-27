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
class Benevol
{
    private $id_volunteer;

    private $name;

    private $surname;

    private $phone;

    private $disponibility_start;

    private $disponibility_end;

    /**
     * @return int
     */
     function __construct($id_volunteer, $name, $surname, $phone, $disponibility_start, $disponibility_end)
    {
         $this->id_volunteer = $id_volunteer;
         $this->name = $name;
         $this->surname = $surname;
         $this->phone = $phone;
         $this->disponibility_start = $disponibility_start;
         $this->disponibility_end = $disponibility_end;
    }

    function getId_volunteer()
    {
        return $this->id_volunteer;
    }

    function getName()
    {
        return $this->name;
    }

    function getSurname()
    {
        return $this->surname;
    }

    function getPhone()
    {
        return $this->phone;
    }

    function getDisponibility_start()
    {
        return $this->disponibility_start;
    }

    function getDisponibility_end()
    {
        return $this->disponibility_end;
    }

    function getContent()
    {
        return $this->content;
    }
}
    