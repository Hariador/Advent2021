<?php
require_once '../vendor/autoload.php';

use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Illuminate\Support\Collection;
use Bramus\Ansi\Ansi;

$sourceData = fopen("data.txt",'r');
$index = 0;
$inputs = [];
$outputs = [];
$console = new Ansi(new \Bramus\Ansi\Writers\StreamWriter());

while(!feof($sourceData)) {
    $data = explode('|', fgets($sourceData));
    $inputs[$index] = explode(' ',trim($data[0]));
    $outputs[$index] = explode(' ',trim($data[1]));
    $index++;
}

$count= 0;
for($i =0;$i<$index;$i++) {
    foreach($inputs[$i] as $input) {
        $console->text($input.' ');
    }
    $console->text('|');
    foreach($outputs[$i] as $output) {
//        var_dump($output);
//        printLine("----");
        if(match($output)) {
            $count++;
            $console->color(SGR::COLOR_FG_GREEN);
        } else {
            $console->color(SGR::COLOR_FG_RED);
        }
        $console->text($output. " ");
    }
    $console->lf()->reset();

}

function match(string $sequence): bool {
    $length = strlen($sequence);
    return ($length == 2 || $length == 4 || $length == 3 || $length == 7 );
}
printLine($count);