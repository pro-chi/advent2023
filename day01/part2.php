<?php

const DIGITS = [
   1 => '~(one)~',
   2 => '~(two)~',
   3 => '~(three)~',
   4 => '~(four)~',
   5 => '~(five)~',
   6 => '~(six)~',
   7 => '~(seven)~',
   8 => '~(eight)~',
   9 => '~(nine)~',
];

$lines = file('input', FILE_IGNORE_NEW_LINES);
$sum = 0;

foreach ($lines as $line => $originChars) {
    if (empty($originChars)) {
        continue;
    }

    $number = (int) (find_first_number($originChars) . find_last_number($originChars));
    printf("%04d: %s\t\t %d + %d = %d\n", $line + 1, $originChars, $number, $sum, $number + $sum);
    $sum += $number;

}

function find_first_number(string $line) {
    $chars = '';
    $charsCount = strlen($line);

    for ($i=0; $i<=$charsCount; $i++) {
        $chars  .= substr($line, $i, 1);
        $chars = str_replace(DIGITS, array_keys(DIGITS), $chars, $replaced);

        if ($replaced) {
            break;
        }
    }

    $lineNumbers = array_filter(str_split($chars), static function ($char) { return is_numeric($char); });

    return array_shift($lineNumbers);
}

function find_last_number(string $line) {
    $chars = '';
    $charsCount = strlen($line);

    for ($i = $charsCount - 1; $i>=0; $i--) {
        $chars  = substr($line, $i, 1) . $chars;
        $chars = str_replace(DIGITS, array_keys(DIGITS), $chars, $replaced);

        if ($replaced) {
            break;
        }
    }

    $lineNumbers = array_filter(str_split($chars), static function ($char) { return is_numeric($char); });

    return array_pop($lineNumbers);
}



echo $sum;
