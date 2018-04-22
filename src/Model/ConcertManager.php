<?php
namespace Model;

/**
 *
 */
class ConcertManager extends AbstractManager
{
    const TABLE = 'Concert';

    const AVAILABLE_SORT_CRITERIAS = [
        [ 'name' => 'Day', 'label' => 'journée' ],
        [ 'name' => 'ArtistName', 'label' => "nom d'artiste" ],
        [ 'name' => 'Scene', 'label' => 'scène' ]
    ];


    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        Concert::initStatics();
        parent::__construct(self::TABLE);
    }

//TODO: this should be generalized to all tables into AbstractManager
    /**
    * returns an array of all sort criteria usable in sortConcertArray()
    */
    public static function getAvailableSortCriterias() : array
    {
        return self::AVAILABLE_SORT_CRITERIAS;
    }


    /**
    * sort the given array by the given criteria
    * @return bool true if all was ok, false otherwise
    **/
    public function sortArray(array &$list, string $criteria)
    {

        $valid = false;

        # is the criteria valid?
        foreach (self::AVAILABLE_SORT_CRITERIAS as $test) {
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
}
