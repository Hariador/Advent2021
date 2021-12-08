<?php
require_once '../vendor/autoload.php';
use Lines\Point;
use Lines\Line;
use Lines\Grid;
use Illuminate\Support\Collection;
$sourceFile = fopen("day5.txt",'r');
$lines = new Collection();
$maxX = 0;
$maxY = 0;
while(!feof($sourceFile)) {
    $lineData= fgets($sourceFile);
    $line = new Line($lineData);
    $maxX = max($line->getMaxX(),$maxX);
    $maxY = max($line->getMaxY(),$maxY);
    $lines->push($line);
}

$grid = new Grid($maxX,$maxY);
foreach($lines as $line) {
    $grid->addLine($line);
}
//$grid->print();
printLine($grid->countem());