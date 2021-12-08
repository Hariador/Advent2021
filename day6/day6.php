<?php
require_once '../vendor/autoload.php';

$sourceData = fopen("data.txt",'r');
$initialState = explode(',',fgets($sourceData));
$cache = [];
$total = 0;
$days = 80;
foreach($initialState as $fish) {
    $key = 0 . "-" .$fish;
    if(isset($cache[$key])) {
        printLine("Cache hit for {$fish} using " . $cache[$key]);
        $total += $cache[$key];
    } else {
        $spawn = lifecyle($fish,256, $cache);
        $total += $spawn;
        $cache[$key] = $spawn;
    }

}

printLine("Total: {$total}");

function lifecyle(int $fish, int $d, array &$cache):int {
    $days = $d;
    $count = 1;
    while($days > 0) {
        $days--;
        if($fish == 0) {
            $key = $days . "-" .$fish;
            if(isset($cache[$key])) {
                $desc = $cache[$key];
                printLine("Cache hit on day {$days}.");
            } else {
                $desc = lifecyle(8, $days, $cache);
                $cache[$key] = $desc;
            }
            $count += $desc;
            $fish = 6;
        } else {
            $fish--;
        }
    }

    return $count;
}
