<?php
require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;



$sourceFile = fopen("day1.txt",'r');
$start = time();
$depthReadings = new Collection([]);
while(!feof($sourceFile)) {
    $line = fgets($sourceFile);
    $depthReadings->push((int)$line);

}
$increases = 0;
echo(pretty_print($depthReadings) . "\n");
for($i=0;$i< $depthReadings->count() -3; $i++) {
    $firstSlice = $depthReadings->slice($i,3);
    $secondSlice = $depthReadings->slice($i+1,3);
    $first = $depthReadings->slice($i,3)->sum();
    $second = $depthReadings->slice($i+1,3)->sum();
    if($second > $first) {
        $increases++;
    }
   // var_dump($depthReadings->slice($i,$i+2));
    echo("I {$i} I+2: " . ($i+2) ." First: ". pretty_print($firstSlice) . "Sum: {$first}\t Second: ".pretty_print($secondSlice) . "Sum: {$second}\n");
}
echo ("Total increases: {$increases}\n")


?>
