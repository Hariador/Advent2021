<?php
require_once '../vendor/autoload.php';
use Illuminate\Support\Collection;
use Board\Board;

$sourceFile = fopen("day4.txt",'r');
$calledNumbers = explode(',',fgets($sourceFile));
$boards = new Collection();
while(!feof($sourceFile)) {
    fgets($sourceFile);
    $boardData = new Collection();
    add_line($boardData, $sourceFile);
    $board = new Board($boardData);
    $boards->push($board);
}
$halt = false;
$last = 0;
foreach($calledNumbers as $call) {
    $last = (int)$call;
    /** @var Board $board */
    foreach($boards as $board) {
        $board->stamp((int)$call);
    }

    foreach($boards as $board) {
        $board->checkForWin();
        if($board->isWinner()) {
            $halt = true;
        }
    }
    if($halt) {
        break;
    }
}

foreach($boards as $board) {
    if($board->isWinner()) {
        $board->print();
        printLine("Last: {$last} SUM: " . $board->noFoundSum());
        printLine($board->noFoundSum() * $last);
    }
}
function add_line(Collection &$board, $fStream): void {
    for($i=0;$i<5;$i++) {
        $board->push(explode(' ',trim(fgets($fStream))));
    }
}

function print_boards(Collection $boards): void {
    /** @var Board $board */
    foreach($boards as $board) {
        $board->print();
        printLine("---------------");
    }
}