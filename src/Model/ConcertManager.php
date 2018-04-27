<?php
namespace Model;

/**
 *
 */
class ConcertManager extends AbstractManager
{
    const TABLE = 'Concert';

    const availableSortCriterias = [
        [ 'name' => 'Day', 'label' => 'journée' ],
        [ 'name' => 'Scene', 'label' => 'scène' ]
    ];


//TODO: this should be generalized to all tables into AbstractManager

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        Concert::initStatics();
        parent::__construct(self::TABLE);
    }


    /**
    * returns an array of all sort criteria usable in sortConcertArray()
    */
    public static function getAvailableSortCriteria() : array
    {
        return self::availableSortCriterias;
    }


    /**
    * sort the given array by the given criteria
    * @return bool true if all was ok, false otherwise
    **/
    public function sortArray(array &$list, string $criteria)
    {

        $valid = false;

        # is the criteria valid?
        foreach (self::availableSortCriterias as $test) {
            if ($test['name'] == $criteria) {
                $valid = true;
                break;
            }
        }

        if (!$valid) {
            return false;
        }

        #now we can sort...
        $sortFunc='cmpBy' . $criteria;
        # a callable is definable by an array [ Object, method, params... ],
        # perhaps the only way when you must reference a static method ...
        return  usort($list, [ 'Model\Concert', $sortFunc ]);
    }

    public function selectAllByDay($day, $scene)
    {
        return static::$pdoConnection->query("SELECT hour, Scene.name AS Scene, Artist.name AS Artist, Day.name AS Day
          FROM Concert 
          JOIN Scene ON id_scene = Scene.id  
          JOIN Artist ON id_artist = Artist.id  
          JOIN Day ON id_day = Day.id
          WHERE id_day = $day
          AND id_scene = $scene",
            \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
}
