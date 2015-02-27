<?php
include "scripts/header.php";
if (preg_match_all("/\>\>([0-9]+)/","a sample>>44 reply AND ANDOTHERE>>543354fFDSAFDS",$token)) print_r($token);
?>