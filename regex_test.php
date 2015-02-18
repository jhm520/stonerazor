<?php
$body = "some text>>72some more text";
if (preg_match("/\>\>[0-9]+/",$body,$needle)) print_r($needle);
echo "<br><br>\n";
echo preg_replace("/\>\>[0-9]+/","",$body);
echo "<br>\n";
echo count($needle);
?>