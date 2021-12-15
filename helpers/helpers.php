<?php

function pretty_print($array): string {
    $output = "";
    foreach($array as $value) {
        $output.="{$value}";
    }
    return $output;
}

function pretty_print_assoc($array): string {
    $output = "";
    foreach($array as $key =>$value) {
        $output.="{$key}={$value} ";
    }
    return $output;
}

function convert_bits_to_dec(array $array): int {
    $n = 0;
    $length = count($array) - 1;
    for($exp=$length;$exp>=0;$exp--){
        if($array[$exp]) {

            $n += pow(2,$length-$exp);
        }
    }

    return $n;
}

function printLine(string $s = ""): void {
    echo($s."\n");
}

function str_con(string $needles, string $search): string  {
    $result = "";
    $sStrings = str_split($search);
    foreach(str_split($needles) as $needle) {
        foreach($sStrings as $char) {
            if($needle == $char) {
                $result .= $needle;
                break;
            }
        }
    }
    return $result;
}