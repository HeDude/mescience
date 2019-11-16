<?php
require_once( 'model/configuration.php');
require_once( MESCIENCE_PROGRAM_PATH . '/mescience.php' );

$mescience = new MeScience();
$mescience->model( 'model/guides.json', 'model/gamepin.txt' );
$mescience->view(  'view/template/mescience.phtml' );
?>
