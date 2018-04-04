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

    /**
     * @return string
     */
    public function getDate(): string
    {
        $day = static::$days[$this->id_day -1];
        if ($day) {
            return $day->getDateAsString();
        } else {
            return 'Index de jour erroné : ' . $this->id_day;
        }
        return ;
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


# THIS COULD BE USED AS A TEMPLATE TO GENERATE THE NOT-DRY-AT-ALL FUNCTIONS
# BELOW ↓ :
    // public function get%fonc%(): string {
    //     $%prop% = static::$%prop%s[$this->id_%prop%];
    //     if ( $%prop% )
    //         return %class%->get%fonc%
    // }

    /**
     * @return string
     */
//TODO : THIS IS NOT "DRY" AT ALL! DEDUPLICATE THIS CODE!
//with some  __GET() magical member?
    public function getSceneName(): string
    {
        $scene = static::$scenes[$this->id_scene -1];
        if ($scene) {
            return $scene->getName();
        } else {
            return 'Index de scène erroné : ' . $this->id_scene;
        }
        return ;
    }

//TODO : THIS IS NOT "DRY" AT ALL! DEDUPLICATE THIS CODE!
    /**
     * @return string
     */
    public function getArtistName(): string
    {
        $artist = Concert::$artists[$this->id_artist -1];
        if ($artist) {
            return $artist->getName();
        } else {
            return 'Index d\'artiste erroné : ' . $this->id_artist;
        }
    }

//TODO : THIS IS NOT "DRY" AT ALL! DEDUPLICATE THIS CODE!
    /**
     * @return string
     */
    public function getArtistStyle(): string
    {
        $artist = Concert::$artists[$this->id_artist -1];
        if ($artist) {
            return $artist->getStyle();
        } else {
            return 'Index d\'artiste erroné : ' . $this->id_artist;
        }
    }

    /**
     * @return bool
     */
    public function getCancelled(): bool
    {
        return $this->cancelled;
    }
}
