<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 11:00
 */

namespace Model;

/**
 *
 */
class AdminManager extends AbstractManager
{
    const TABLE = 'Administration';
    public $username;
    public $password;

    /**
     *  Initializes this class
     */
    public function __construct(string $username, string $password)
    {
        parent::__construct(self::TABLE);
        $this->username = $username;
        $this->password = $password;
    }

    public function connectDb(string $username, string $password)
    {
        $request = self::$pdoConnection->prepare(
            "SELECT * FROM " . $this->table . " WHERE username = :username AND password = :password"
        );
        $request->setFetchMode(\PDO::FETCH_ASSOC);
        $request->bindValue('username', $username);
        $request->bindValue('password', $password);
        $request->execute();

        return $request->fetch();
    }

    public function isLoginCorrect(string $username, string $password)
    {
        return $this->connectDb($username, $password);
    }
}
