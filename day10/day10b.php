<?php
require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;
use Chunk\Chunk;

$sourceData = fopen("data.txt",'r');

$chunks = new Collection();
while(!feof($sourceData)) {
    $chunks->push(new Chunk(trim(fgets($sourceData))));
}
$sum = 0;
$scores = new Collection();
/** @var Chunk $chunk */
foreach($chunks as $chunk) {
    if($chunk->isValid()) {
        $chunk->fix();
        printLine($chunk);
        $scores->push($chunk->score());
    } else {
        switch($chunk->getCorruptChar()) {
            case ')': $sum +=3;
                break;
            case ']': $sum +=57;
                break;
            case '}': $sum += 1197;
                break;
            case '>': $sum += 25137;
        }
    }
}

 $sorted = $scores->sort();

 $s = $sorted->values()->all();

printLine("Auto Correct Score:" . $s[$sorted->count()/2]);
printLine("Corrupted Score: {$sum}");