<?php

require_once '../vendor/autoload.php';

use Illuminate\Support\Collection;
use Squids\Cave;

$cave = new Cave();
$sourceFile= fopen("data.txt",'r');

while(!feof($sourceFile)) {
   $cave->addRow(trim(fgets($sourceFile)));
}

$flashes = 0;
for($i=0;$i<1000;$i++) {
    $cave->step();
    if($cave->flash() == 100) {
        $cave->print();
        printLine($i);
        break;
    }
}
