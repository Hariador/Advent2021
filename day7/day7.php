<?php
require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;

$sourceData = fopen("data.txt",'r');
$crapPos = new Collection(explode(',',fgets($sourceData)));
$total = $crapPos->count();
printLine("Total: {$total}");
$alignPos = $crapPos->median();
$lowerBound = min(0,$alignPos - 5);
$upperBound = max($crapPos->count(),$alignPos + 5);
$fuelCost = get_cost_for_pos($lowerBound,$crapPos);
printLine("Mediam POS: {$alignPos}");
for($i=0;$i<=$total;$i++) {
    printLine($i);
    $cost = get_cost_for_pos($i,$crapPos);
    $fuelCost = min($fuelCost,$cost);
}

printLine($fuelCost);


function get_cost_for_pos(int $pos, Collection $crapPos): int {
    $fuelCost = 0;
    foreach($crapPos as $crap) {
        $dist = abs($crap - $pos);
        for($i=1;$i<=$dist;$i++) {
            $fuelCost += $i;
        }
    }

    return $fuelCost;
}