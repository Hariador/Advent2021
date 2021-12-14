<?php

require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;
use Lines\Point;

$sourceFile= fopen("data.txt",'r');
$points = new Collection();
$maxY = 0;
$maxX = 0;
while(!feof($sourceFile)) {
    $line = trim(fgets($sourceFile));
    if($line == '') {
        break;
    } else {
        $point = new Point($line);
        $points[$point->getKey()] = $point;
    }
}
$maximums = getMaximums($points);
$maxY = $maximums[0];
$maxX = $maximums[1];
printLine("Point Count: {$points->count()} Y:{$maxY} X:{$maxX}");
while(!feof($sourceFile)) {
    $line = trim(fgets($sourceFile));
    printLine("DOING: {$line}");
    $data = explode(' ',$line);
    $foldData = explode('=',$data[2]);
    if($foldData[0] == 'x') {
        $points = foldX($foldData[1],$maxX,$points);
    }
    if($foldData[0] == 'y') {
        $points = foldY($foldData[1],$maxY,$points);
    }
    $maximums = getMaximums($points);
    $maxY = $maximums[0];
    $maxX = $maximums[1];
    printLine("Point Count: {$points->count()} Y:{$maxY} X:{$maxX}");
}

draw($points,$maxY,$maxX);
function getMaximums(Collection $points): array {
    $x =0;
    $y =0;
    /** @var Point $point */
    foreach($points as $point) {
        $y = max($y,$point->Y());
        $x = max($x,$point->X());
    }

    return [$y,$x];
}

function foldY(int $axis, int $max, Collection $points): Collection {
    $temp = new Collection();
    /** @var Point $point */
    foreach($points as $point) {
        if($point->Y() > $axis) {
            $point->setY($max - $point->Y());
        }
        $temp[$point->getKey()] = $point;
    }

    return $temp;
}

function foldX(int $axis, int $max, Collection $points): Collection {
    $temp = new Collection();
    /** @var Point $point */
    foreach($points as $point) {
        if($point->X() > $axis) {
            $point->setX($max - $point->X());
        }
        $temp[$point->getKey()] = $point;
    }

    return $temp;
}

function draw(Collection $points, int $Y, $X): void {
    for($i=0;$i<$Y+1;$i++){
        for($j=0;$j<$X;$j++){
            $key = $j.'-'.$i;
            if(isset($points[$key])) {
                echo('#');
            } else {
                echo(' ');
            }

        }
        printLine();
    }
}