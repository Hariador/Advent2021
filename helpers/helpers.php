<?php

function pretty_print($array): string {
    $output = "";
    foreach($array as $value) {
        $output.="{$value} ";
    }
    return $output;
}