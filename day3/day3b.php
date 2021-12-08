<?php
require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;

$sourceFile = fopen("day3.txt",'r');

$source = fgets($sourceFile);
$bitWidth = strlen(trim($source));
echo("Setting bit width to {$bitWidth}\n");
$sourceData = new Collection();

$gamma = 0;
$epsilon = 0;
$oneCounts = [];
$zeroCounts = [];
$gammaBits = [];
$epsilonBits = [];
for($i=0;$i<=$bitWidth-1;$i++){
    $gammaBits[$i] = 0;
    $epsilonBits[$i] = 1;
    $oneCounts[$i] = 0;
    $zeroCounts[$i] = 0;
}
$bitPattern = parse_input($source,$bitWidth);
$sourceData->push($bitPattern);
echo("Size: " . count($bitPattern) ."\n");
echo(bindec($source)."\t");
echo(convert_bits_to_dec($bitPattern). "\t".pretty_print($bitPattern)."\n");
while(!feof($sourceFile)) {
    $source = trim(fgets($sourceFile));
    echo(bindec($source)."\t");
    $bitPattern = parse_input($source,$bitWidth);
    echo(convert_bits_to_dec($bitPattern). "\t".pretty_print($bitPattern)."\n");
    $sourceData->push($bitPattern);
}


for($i=0;$i<=$bitWidth-1;$i++) {
    if($oneCounts[$i] >= $zeroCounts[$i] ) {
        $gammaBits[$i] = 1;
    }
    if ($zeroCounts[$i] <= $oneCounts[$i]) {
        $epsilonBits[$i] = 0;
    }
}
echo("Gamma Bits: ". pretty_print($gammaBits) . "E:" . pretty_print($epsilonBits) ."\n");
echo("Gamma: {$gamma} Epsilon: {$epsilon} Power: ". $gamma * $epsilon."\n");
$oxRate = find_ox_rate($sourceData);
$coRate = find_co_rate($sourceData);
echo("Oxy Gen Rate: ". $oxRate ."\n");
echo("CO2 Scrub Rate: ". $coRate."\n");
echo("Rating: " . $oxRate * $coRate . "\n");

function parse_input(string $source, int $size): array {
    $temp = [];
    for($i=0;$i<=$size-1;$i++) {
        $temp[$i] = (int)$source[$i];
    }
    return $temp;
}
function count_bits(array &$oneCounts, array &$zeroCounts, array $bitPattern, int $size): void {
    for($i=0;$i<=$size;$i++) {
        $bit = (int)$bitPattern[$i];
        if($bit) {
            $oneCounts[$i]++;
        } else {
            $zeroCounts[$i]++;
        }
    }
}

function find_ox_rate(Collection $sourceData): int {
   $temp = $sourceData;
   $index = 0;

   while($temp->count() > 1) {
       $counts = get_bit_counts($temp,$index);
       if($counts[1] >= $counts[0]) {
           $keyBit = 1;
       } else {
           $keyBit = 0;
       }
       $temp = $temp->filter(function($value) use ($keyBit,$index) {
           echo("String: " . pretty_print($value). " ".$value[$index]." ".$keyBit . "\n");
           $result = $value[$index] == $keyBit;

           return $result;
       });
       echo("-------------------------------\n");
       $index++;
   }
   return convert_bits_to_dec($temp->first());
}

function find_co_rate(Collection $sourceData): int {
    $temp = $sourceData;
    $index = 0;

    while($temp->count() > 1) {
        $counts = get_bit_counts($temp,$index);
        if($counts[0] <= $counts[1]) {
            $keyBit = 0;
        } else {
            $keyBit = 1;
        }
        $temp = $temp->filter(function($value) use ($keyBit,$index) {
            echo("String: " . pretty_print($value). " ".$value[$index]." ".$keyBit . "\n");
            $result = $value[$index] == $keyBit;

            return $result;
        });
        echo("-------------------------------\n");
        $index++;
    }
    return convert_bits_to_dec($temp->first());
}



function get_bit_counts(Collection $sourceData, int $sigBit): array {
    $zeros = 0;
    $ones = 0;
    foreach($sourceData as $bitPattern) {
        if($bitPattern[$sigBit] === 1) {
            $ones++;
        } else {
            $zeros++;
        }
    }
    return [$zeros,$ones];
}


