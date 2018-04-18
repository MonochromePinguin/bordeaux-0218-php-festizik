<?php
namespace Model;

use Model\ArtistManager;

/**
 * Class Concert
 *
 */
class Concert
{
    /**
    * all of these vars are of the same type as theire corresponding field
    * into the DB
    */
    private $id_concert;
    private $id_day;
    private $hour;
    private $id_scene;
    private $id_artist;
    private $cancelled;

    /* this one is just a cache */
    private $dateObject = null;

    private static $artists;
    private static $scenes;
    private static $days;

    public static function initStatics()
    {
        static::$artists = ( new ArtistManager() )->selectAll();
        static::$scenes = ( new SceneManager() )->selectAll();
        static::$days = ( new DayManager() )->selectAll();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id_concert;
    }

    public function getDate(): \DateTime
    {
         $day = static::$days[$this->id_day -1];
        return $day ?
            new \DateTime( $day->getDateAsRaw() )
            # if an error occurend, returns an as-null-as-possible Date
            : \DateTime::createFromFormat('Y-m-d', '0000-01-01');
    }

    /**
     * @return string
     */
    public function getDateAsString(): string
    {
        $day = static::$days[$this->id_day -1];
        return $day ?
            $day->getDateAsString()
            :  'Index de jour erroné : ' . $this->id_day;
    }

    public function getDateTime() : \DateTime
    {
        if ( ! isset($this->dateObject) ) {

            $day = static::$days[$this->id_day -1];
            if ( isset($day) )
                $theDate = $day->getDateAsRaw();
            else
                $theDate = '0000-01-01';

            $this->dateObject = new \DateTime( $theDate . ' ' . $this->getHour() );
        }
        return $this->dateObject;
    }

//TODO : seems unneeded. Delete it if no-one want it.
    /**
     * @return string
     */
    public function getDayName(): string
    {
        return 'NON IMPLÉMENTÉ : ' . $this->id_day;
    }

    /**
     * @return string
     */
    public function getHour(): string
    {
        return $this->hour;
    }


    public function getSceneName(): string
    {
        $scene = static::$scenes[$this->id_scene -1];
        return ($scene) ?
            $scene->getName()
            :  'Index de scène erroné : ' . $this->id_scene;
    }


    #For DRY reasons, just export the Artist object instead of hiding it
    # and creating functions to access its datas

    # As I'm doomed to use 7.0 instead of 7.1,
    # I can't return a nullable type « ?Artist » ...

    /**
     * @return Artist|null
     */
    public function getArtist()
    {
        return Concert::$artists[$this->id_artist -1]  ??  null;
    }

    /**
     * @return bool
     */
    public function getCancelled(): bool
    {
        return $this->cancelled;
    }


    /*
     sort functions for use into ConcertManager::sortArray()
     all of them are callable callbacks for usort()
     */
    public static function cmpByScene(Concert $one, Concert $two) : int {
        return strcmp( $one->getSceneName(), $two ->getSceneName() );
    }

    public static function cmpByArtistName(Concert $one, Concert $two) : int {
        $artist1 = $one->getArtist();
        $artist2 = $two->getArtist();

        if ( isset($artist1) && isset($artist2) )
            return strcmp( $artist1->getName(), $artist2->getName() );
        else
            return 0;
    }

    public static function cmpByDay(Concert $one, Concert $two) : int {
       $date1 = $one->getDateTime();
       $date2 = $two->getDateTime();

       if ($date1 > $date2)
           return 1;
       elseif ($date1 < $date2)
            return -1;
       else
           return 0;
    }


}
