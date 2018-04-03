<?php
namespace Model;

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
    public function getDateTime(): string
    {
        return $this->id_day . ' ' . $this->hour;
    }

    /**
     * @return string
     */
    public function getDay(): string
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

    /**
     * @return string
     */
    public function getSceneName(): string
    {
        return 'SceneName NON IMPLÉMENTÉ';
    }

    /**
     * @return string
     */
    public function getArtistName(): string
    {
        return ' ArtistName NON IMPLÉMENTÉ';
    }

    /**
     * @return string
     */
    public function getArtistGenre(): string
    {
        return 'ArtistGenre – not implemented';
    }

    /**
     * @return bool
     */
    public function getCancelled(): bool
    {
        return $this->cancelled;
    }
}
