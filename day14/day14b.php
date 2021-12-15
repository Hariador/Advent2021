<?php

require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;

$sourceData = fopen("testData.txt",'r');

$initial = trim(fgets($sourceData));
//burn line
fgets($sourceData);
$rules = new Collection();
while(!feof($sourceData)) {
    $ruleData = explode(' ',trim(fgets($sourceData)));
    $rules[$ruleData[0]] = $ruleData[2];
}

printLine($initial);
$polymer = str_split($initial);
foreach($rules as $name => $rule) {
    printLine("MATCH: {$name} RESULT: {$rule}");
}

$histogram = [];
$size = count($polymer);

    for($pointer=0; $pointer < $size-1; $pointer++ ){
        $key = $polymer[$pointer] . $polymer[$pointer+1];
       // printLine("Matching on: {$key}");
        $hists = totalsForPair($key,$rules,10);
        foreach($hists as $key =>$count) {
            if(isset($histogram[$key])) {
                $histogram[$key] = $histogram[$key] + $count;
            } else {
                $histogram[$key] = $count;
            }
        }
    }


//printLine(pretty_print($polymer));
printLine("Size: ". count($polymer));

$min = PHP_INT_MAX;
$max = 0;
foreach($histogram as $letter) {
    $max = max($letter,$max);
    $min = min($letter,$min);
}

printLine("Least: {$min}");
printLine("Most: {$max}");
printLine("Diff: " . ($max - $min));

function totalsForPair(string $key, Collection $rules, int $gens): array {
    $polymer = str_split($key);
    $size = 2;
    for($i=0;$i<$gens;$i++) {
        printLine($i);
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
    $histogram = [];
    foreach($polymer as $molc) {
        if(isset($histogram[$molc])) {
            $histogram[$molc]++;
        } else {
            $histogram[$molc] = 1;
        }
    }

    return $histogram;
}