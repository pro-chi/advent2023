<?php

$lines = file('input', FILE_IGNORE_NEW_LINES);

$sum = 0;

foreach ($lines as $line => $chars) {
    if (empty($chars)) {
        continue;
    }

    $lineNumbers = array_filter(str_split($chars), static function ($char) { return is_numeric($char); });

    if (count($lineNumbers) === 1) {
        $lineNumbers[] = reset($lineNumbers);
    }

    array_splice($lineNumbers, 1, -1);

    $number = (int) implode('', $lineNumbers);
    printf("%04d: %s\t\t %d + %d = %d\n", $line + 1, $chars, $number,  $sum, $number + $sum);
    $sum += $number;

}

echo $sum;
