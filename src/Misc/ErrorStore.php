<?php
namespace Misc;

/**
* This class is used to store errors coming from various sources,
* and show them later on demand
*/

class ErrorStore
{
    private $messages = [];

    public function storeMsg(string $msg)
    {
        $this->messages[] = htmlspecialchars($msg);
    }

    public function getMsgNumber() : int
    {
        return count($this->messages);
    }

    /**
    * return an array containing all unformated error messages
    */
    public function getAllMsg() : array
    {
        return $this->messages;
    }


    /**
    * return a single string made of all the messages after processing by
    * $formater() – see below ↓.
    * At each successfull call, the store is emptied.
    * @param function|null $formater
    * @return string
    */
//TODO : is that needed finally ?
    public function formatAllMsg($formater = null) : string
    {
        $res = '';

        if (is_callable($formater)) {
            foreach ($this->messages as $msg) {
                $res .= $formater($msg);
            }

            $this->messages = [];
            return $res;
        } elseif (null === $formater) {
            #return a standard formating
            foreach ($this->messages as $msg) {
                $res .= '<p>' . $msg . "</p>\n" ;
            }

            $this->messages = [];
            return $res;
        } else {
            #$formater need to be a callable function ...
            $res = <<< EOT
<p>Désolé {__CLASS_ . '.' . __FUNCTION__} prends comme argument une fonction
 acceptant en paramètre une chaîne et renvoyant une chaîne,
 ce qui n'est pas le cas de {$formater} ... </p>
EOT;
            $messages[] = $res;
            return $res;
        }
    }
}
