<?php
require_once '../vendor/autoload.php';

use Caves\Cave;

$sourceFile = fopen("data.txt",'r');
$caveMap = new Cave();
while(!feof($sourceFile)) {
    $dataLine = trim(fgets($sourceFile));
    $caveMap->addRow($dataLine);
}
$caveMap->scanLows();
while($caveMap->fillBasins()) {};
$caveMap->print();
$area = 1;
foreach($caveMap->getSizes()->sortDesc()->shift(3) as $size) {
    $area = $size * $area;
}
printLine($area);
