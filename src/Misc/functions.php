<?php

/**
 * Returns a whole page to show in case of critical error
 * @param string $title title of the warning page
 * @param string $msg body of the warning page
 * @return string the raw, formated page to show
 */
function emergencyPage(string $title, string $msg) : string
{
    return <<< EOP
<!DOCTYPE html>
<html lang="fr">
  <head>
	<title>Festizik – quelque chose de grave s'est passé</title>
	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!--to deactivate the old "compatibility with that old IE" mode of edge -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<style>
	    main { text-align: center }
	    .error {
	        color: red;
	        font-style: italic bold;
	        border: 1px solid darkred;
	        border-radius: 1rem;
	     }
    </style>
  </head>

  <body>
    <header>
        <h1>{$title}</h1>
    </header>

    <main>
        <p>Message d'erreur&nbsp;:</p>
        <p class="error">
            {$msg}
        </p>    
    </main>

  </body>
</html>
EOP;
}
