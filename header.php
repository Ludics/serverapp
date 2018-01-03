<?php

$logfile = fopen("log.txt", "a+");
function myLOG($logdata){
    global $logfile;
    fwrite($logfile, date("Y-m-d H:i:s$ ", time()) . $logdata . PHP_EOL);
}
?>
