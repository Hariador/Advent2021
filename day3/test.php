<?php
require_once '../vendor/autoload.php';

$temp = [1,1,1,1,0];
echo(bindec("11110") ."\t".convert_bits_to_dec($temp));