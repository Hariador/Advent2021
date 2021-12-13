<?php

require_once '../vendor/autoload.php';

use Pathing\Cave;
use Pathing\Paths;
use Pathing\History;

$caves = new Paths();
$sourceFile = fopen("data.txt",'r');

while(!feof($sourceFile)) {
    $parts = explode('-',trim(fgets($sourceFile)));
    if($caves->contains($parts[0])) {
        $caves->get($parts[0])->addConnection($parts[1]);
    } else {
        $cave = new Cave($parts[0]);
        $cave->addConnection($parts[1]);
        $caves->add($cave);
    }
    if($caves->contains($parts[1])) {
        $caves->get($parts[1])->addConnection($parts[0]);
    } else {
        $cave = new Cave($parts[1]);
        $cave->addConnection($parts[0]);
        $caves->add($cave);
    }

}

$caves->print();
$history = new History();
$cave = $caves->get('start');

$blocked = '';
printLine(countPaths($history,$blocked,$cave,$caves, false));

function countPaths(History $history, string $blocker, Cave $cave, Paths $paths, bool $doubledBack): int {
    $history->push($cave);
    if($cave->isEnd()) {
        printLine(pretty_print($history));
        return 1;
    }
    $pathCount = 0;
    foreach($cave->getConnections() as $connection) {
        $next = $paths->get($connection);
        if($next->getLabel() === $blocker) {
            continue;
        }

        if($next->isStart()) {
            continue;
        }

        if($next->isSmall()) {
            if(!$history->visited($next->getLabel())) {
                $pathCount += countPaths($history,$blocker,$next,$paths,$doubledBack);
                $history->pop();
            } else {
                if(!$doubledBack) {
                    $pathCount += countPaths($history,$next->getLabel(),$next,$paths,true);
                    $history->pop();
                }
            }
        }

        if($next->isLarge() || $next->isEnd()) {
            $pathCount += countPaths($history,$blocker,$next,$paths,$doubledBack);
            $history->pop();
        }
    }
    return $pathCount;
}