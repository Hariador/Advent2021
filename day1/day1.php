<?php

$sourceFile = fopen("day1.txt",'r');
$start = time();
$oldValue = 0;
$increaseCounter = 0;

while(!feof($sourceFile)) {
    $line = fgets($sourceFile);
    if($line > $oldValue) {
        $increaseCounter++;
    }
    $oldValue = $line;

}
echo("Increase {$increaseCounter} times");
