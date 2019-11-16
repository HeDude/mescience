<?php
require_once( 'model/configuration.php');
require_once( MESCIENCE_PROGRAM_PATH . '/socrates.php' );

$socrates = new Socrates();
$socrates->model( 'model/sources.txt', 'model/responses.txt' );
$socrates->view(  'view/template/socrates.phtml' );
?>
