<?php
require_once '../vendor/autoload.php';

use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Illuminate\Support\Collection;
use Bramus\Ansi\Ansi;

$sourceData = fopen("testData.txt",'r');
$index = 0;
$inputs = [];
$outputs = [];

while(!feof($sourceData)) {
    $data = explode('|', fgets($sourceData));
    $inputs[$index] = explode(' ',trim($data[0]));
    $out = explode(' ',trim($data[1]));
    $outputs[$index] = $out;
    $index++;
}

$sum = 0;
for($i =0;$i<$index;$i++) {
    $one = "";
    $seven = "";
    $four = "";
    $eight = "";
    $contains235= [];
    $contains690= [];
    $storage = new Collection();
    setupStorage($storage);

    //sort Data
    foreach($inputs[$i] as $input) {
        $length = strlen($input);
        $input = str_split($input);
        sort($input);
        $input = implode("",$input);
        switch ($length) {
            case 2: $one = $input;
                break;
            case 3: $seven = $input;
                break;
            case 4: $four = $input;
                break;
            case 7: $eight = $input;
                break;
            case 5: $contains235[] = $input;
                break;
            case 6: $contains690[]  = $input;
        }
    }
    $known = "";
    // Add what we know about One
    setColToKnown($storage,5,$one);
    setColToKnown($storage,6, $one);
    $known .= $one;

    $colZero = getStrDiff($seven,$one);

    // We know that the one character between one and seven is Col 0

    setColToKnown($storage,0,$colZero);
    $known .= $colZero;
    $diff41 = getStrDiff($four,$one);
    setColToKnown($storage,1,$diff41);
    setColToKnown($storage,2,$diff41);
    $known .= $diff41;
    // Fine the last two remaining wires by finding the diff between $known and $eight
    $diff8k = getStrDiff($eight,$known);
    setColToKnown($storage,3,$diff8k);
    setColToKnown($storage,4,$diff8k);
    foreach($contains235 as $key => $number) {
        if(str_con($one,$number) == $one) {
            $three = $number;
            unset($contains235[$key]);
        }
    }
    $col2 = str_con($storage[3][2],$three);
    $col1 = str_replace($col2,"",$storage[0][1]);
    setColToKnown($storage,1,$col1);
    setColToKnown($storage,2,$col2);
    $col4 = str_con($storage[3][4],$three);
    $col3 = str_replace($col4,"",$storage[0][3]);
    setColToKnown($storage,4,$col4);
    setColToKnown($storage,3,$col3);
    foreach($contains235 as $key => $number) {
        if(strlen(str_con(getRowString($storage,5),$number))==5) {
            $five = $number;
        }

    }
    $col5 = str_con($one,$five);
    $col6 = str_replace($col5,"",$one);
    setColToKnown($storage,5,$col5);
    setColToKnown($storage,6,$col6);
    printDiag($storage);
    $map = [];
    for($j=0;$j<10;$j++) {
        $number = getRowString($storage,$j);
        $map[$number] = $j;
    }
    $finalNumber = "";
    foreach($outputs[$i] as $output) {
        $output = str_split($output);
        sort($output);
        $output = implode("",$output);
        $finalNumber.= $map[$output];
    }
   // printLine($finalNumber);
    $sum += (int)$finalNumber;

}

printLine($sum);

function match(string $sequence): bool {
    $length = strlen($sequence);
    return ($length == 2 || $length == 4 || $length == 3 || $length == 7 );
}

function setupStorage(Collection &$storage): void {
    $storage[0] = new Collection(["","","*","","","",""]);
    $storage[1] = new Collection(["*","*","*","*","*","",""]);
    $storage[2] = new Collection(["","*","","","","*",""]);
    $storage[3] = new Collection(["","*","","*","","",""]);
    $storage[4] = new Collection(["*","","","*","*","",""]);
    $storage[5] = new Collection(["","","","*","","","*"]);
    $storage[6] = new Collection(["","","","","","","*"]);
    $storage[7] = new Collection(["","*","*","*","*","",""]);
    $storage[8] = new Collection(["","","","","","",""]);
    $storage[9] = new Collection(["","","","*","","",""]);

}
function setColToKnown(Collection $storage,int $col, string $known): void {
    for($i=0;$i<=9;$i++) {
        if($storage[$i][$col] !== "*" ) {
            $storage[$i][$col] = $known;
        }
    }
}

/**
 * Returns the characters of the first that are not present in the second
 * @param string $first
 * @param string $second
 * @return string
 */
function getStrDiff(string $first, string $second): string {
    $f=  str_split($first);
    $s=  str_split($second);
    $common = array_intersect($f,$s);
    foreach($common as $char) {
        $first =  str_replace($char,"",$first);
    }
    return $first;

}

function printDiag(Collection $storage): void {
    $console = new Ansi(New \Bramus\Ansi\Writers\StreamWriter());
    foreach($storage as $digit) {
        foreach($digit as $wire) {
            if(strlen($wire) == 1) {
                if($wire == "*") {
                    $console->color(SGR::COLOR_FG_WHITE_BRIGHT)->text($wire . "\t");
                } else {
                    $console->color([SGR::COLOR_FG_GREEN])->text($wire ."\t");
                }
            } else {
                $console->color(SGR::COLOR_FG_RED)->text($wire."\t");
            }
        }
        $console->lf();
    }
    $console->reset();
}

function getRowString(Collection $storage, int $row): string {
    $temp = "";
    foreach($storage[$row] as $wire) {
        if($wire == '*') {
            //skip
        } else {
            $temp .= $wire;
        }
    }
    $t = str_split($temp);
    sort($t);
    $temp = implode(array_unique($t));
    return $temp;
}

