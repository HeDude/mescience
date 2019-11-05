<?php
$source = $_GET[ "source" ];
echo "hash=".hash( "sha256", $source ); 
?>
