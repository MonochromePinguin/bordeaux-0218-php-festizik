<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 15:38
 * PHP version 7
 */

namespace Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;
use misc\ErrorStore as ErrorStore;

/**
 *
 */
abstract class AbstractController
{
    protected $twig;

    /**
    * @var ErrorStore $errorStore store the list of generated errors.
    * not static because we could want more than one errorStore.
    */
    protected $errorStore;

    /**
     *  Initializes this class.
     * @param ErrorStore|null
     * the ErrorStore is optional because a class must work well without it
     */
    public function __construct($errorStore = null)
    {
        $loader = new Twig_Loader_Filesystem(APP_VIEW_PATH);
        $this->twig = new Twig_Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addExtension(new \Twig_Extension_Debug());

        $this->errorStore = $errorStore;
    }


    /**
    * store an error message IF an errorStore is present
    * @param string
    */
    protected function storeMsg( string $string ) {
        if ( $this->errorStore )
            $this->errorStore->storeMsg( $string );
    }
}
