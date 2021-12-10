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
/** @var Chunk $chunk */
foreach($chunks as $chunk) {
    if($chunk->isValid()) {
        printLine($chunk);
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
printLine("Corrupted Score: {$sum}");