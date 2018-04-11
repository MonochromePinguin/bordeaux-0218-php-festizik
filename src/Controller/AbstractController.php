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
     * @param ErrorStore $errorStore
     */
    public function __construct(ErrorStore $errorStore)
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
}
