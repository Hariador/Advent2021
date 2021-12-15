<?php

require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;

$sourceData = fopen("data.txt",'r');

$initial = trim(fgets($sourceData));

//burn line
fgets($sourceData);
$rules = new Collection();
$cache = [];
$histCache = [];
while(!feof($sourceData)) {
    $ruleData = explode(' ',trim(fgets($sourceData)));
    $rules[$ruleData[0]] = $ruleData[2];
}

printLine($initial);
$polymer = str_split($initial);
$size = count($polymer);
$hist =[];
$cache = [];

for($pointer=0; $pointer < $size-1; $pointer++ ) {
    $key = $polymer[$pointer] . $polymer[$pointer + 1];
    $khist = countPoly($key,0,$cache,$rules);
    $khist[$polymer[$pointer +1]]--;
    $hist = mergeHist($hist, $khist);
}
$hist[str_split($key)[1]]++;
$min = PHP_INT_MAX;
$max = 0;
foreach($hist as $letter =>$count ) {
    $min = min($min,$count);
    $max = max($max,$count);
    printLine("{$letter}:{$count}");
}

printLine("Max: {$max}");
printLine("Min: {$min}");
$diff = $max - $min;
printLine("Diff: {$diff}");

function countPoly(string $key, int $gen, array& $cache, Collection& $rules): array
{
    $max = 40;
    $cacheKey = $key . (string) ($max - $gen);
    if(isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    $k = str_split($key);
    if($gen == $max) {
        if($k[0] == $k[1]) {
            return [$k[0] => 2];
        } else {
            return [$k[0] => 1, $k[1] => 1];
        }
    }

    $n = $rules[$key];
    $k0 = countPoly($k[0].$n,$gen +1, $cache,$rules);
    $k0[$n]--;

    //printLine(pretty_print_assoc($k0));
    $k1 = countPoly($n.$k[1],$gen+1,$cache,$rules);
    //printLine(pretty_print_assoc($k1));
    $kt = mergeHist($k0,$k1);
    //printLine(pretty_print_assoc($kt));
    $cache[$cacheKey] = $kt;
    return $kt;
}

function mergeHist(array $k1, array $k2): array {
    foreach($k2 as $letter => $count) {
        if(isset($k1[$letter])) {
            $k1[$letter] += $count;
        } else {
            $k1[$letter] = $count;
        }
    }
    return $k1;
}


