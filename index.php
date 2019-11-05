<?php
require_once(     'controller/socrates.php' );
$socrates = new Socrates();

$socrates->model( 'model/sources.txt', 'model/responses.txt' );
$socrates->view(  'view/template/socrates.phtml' );
?>
