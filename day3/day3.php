<?php
require_once '../vendor/autoload.php';

$sourceFile = fopen("day3.txt",'r');

$bitPattern = fgets($sourceFile);
$bitWidth = strlen(trim($bitPattern));
echo("Setting bit width to {$bitWidth}\n");

$gamma = 0;
$epsilon = 0;
$oneCounts = [];
$zeroCounts = [];
$gammaBits = [];
$epsilonBits = [];
for($i=0;$i<=$bitWidth-1;$i++){
    $gammaBits[$i] = 0;
    $epsilonBits[$i] = 0;
    $oneCounts[$i] = 0;
    $zeroCounts[$i] = 0;
}

counter($oneCounts,$zeroCounts,$bitPattern, $bitWidth-1);
while(!feof($sourceFile)) {
    $bitPattern = fgets($sourceFile);
    counter($oneCounts,$zeroCounts,$bitPattern,$bitWidth-1);
}


for($i=0;$i<=$bitWidth-1;$i++) {
    if($oneCounts[$i]> $zeroCounts[$i]) {
        $gammaBits[$i] = 1;
        $gamma += pow(2,$bitWidth-$i-1);
    } else {
        $epsilonBits[$i] = 1;
        $epsilon += pow(2,$bitWidth-$i-1);
    }
}
echo("Gamma Bits: ". pretty_print($gammaBits) . "E:" . pretty_print($epsilonBits) ."\n");
echo("Gamma: {$gamma} Epsilon: {$epsilon} Power: ". $gamma * $epsilon."\n");

function counter(array &$oneCounts, array &$zeroCounts, string $bitPattern, int $size): void {
    for($i=0;$i<=$size;$i++) {
        $bit = (int)$bitPattern[$i];
        if($bit) {
            $oneCounts[$i]++;
        } else {
            $zeroCounts[$i]++;
        }
    }
}


