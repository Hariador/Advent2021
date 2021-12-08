<?php

function pretty_print($array): string {
    $output = "";
    foreach($array as $value) {
        $output.="{$value}";
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