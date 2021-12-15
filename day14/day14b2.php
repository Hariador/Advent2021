<?php

require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;

$sourceData = fopen("testData.txt",'r');

$initial = trim(fgets($sourceData));

//burn line
fgets($sourceData);
$rules = new Collection();
$cache = [];
while(!feof($sourceData)) {
    $ruleData = explode(' ',trim(fgets($sourceData)));
    $rules[$ruleData[0]] = $ruleData[2];
}

printLine($initial);
$polymer = str_split($initial);

$histogram = [];
$size = count($polymer);

foreach($rules as $key =>$rule) {
    $cache[$key] = genFive($key,$rules);
}
$result = [];
for($pointer=0; $pointer < $size-1; $pointer++ ){
    $key = $polymer[$pointer] . $polymer[$pointer+1];
    $cachedData = $cache[$key];
    $grab = false;
    if($pointer == 0) {
        $grab = true;
    }
    foreach($cachedData as $element) {
        if($grab) {
            $result[] = $element;
        } else {
            $grab = true;
        }
    }
}
printLine(count($result));
printLine(pretty_print($cache['NN']));
printLine(pretty_print($cache['NC']));
printLine(pretty_print($cache['CB']));
$min = PHP_INT_MAX;
$max = 0;
foreach($histogram as $letter) {
    $max = max($letter,$max);
    $min = min($letter,$min);
}
printLine("Least: {$min}");
printLine("Most: {$max}");
printLine("Diff: " . ($max - $min));

function genFive(string $key, Collection $rules): array {
    $polymer = str_split($key);
    $size = 2;
    for($i=0;$i<1;$i++) {
        $temp = [];
        $newSize = $size;
        for($pointer=0; $pointer < $size-1; $pointer++ ){
            $key = $polymer[$pointer] . $polymer[$pointer+1];
            // printLine("Matching on: {$key}");
            $temp[] = $polymer[$pointer];
            if(isset($rules[$key])) {
                $temp[] = $rules[$key];
                $newSize++;
            }
            if($pointer == $size-2) {
                $temp[]=$polymer[$pointer+1];
            }
        }
        $size = $newSize;
        $polymer = $temp;

    }

    return $polymer;
}
