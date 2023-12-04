<?php
$fr = fopen('input', 'r');

$matrix = [];

while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' !== $line) {
        $matrix[] = str_split($line);
    }
}

$sum = 0;
$partNumbers = [];

foreach ($matrix as $y => $line) {
    $markStart = null;
    $markEnd = null;
    $number = '';

    foreach ($line as $x => $char) {
        if (is_numeric($char)) {
            $number .= $char;
        }

        if (is_numeric($char) && null === $markStart) {
            $markStart = ['x' => $x, 'y' => $y];
        }

        if ((!is_numeric($char) || $x === count($line) - 1) && null !== $markStart) {
            $markEnd = ['x' => $x - 1, 'y' => $y];
        }

        if (null !== $markStart && null !== $markEnd) {
            $surrounding = getSurrounding($markStart, $markEnd, $matrix);
            $isPartNumber = [] !== array_filter(
                $surrounding,
                static fn ($input) => $input !== '.' && !is_numeric($input)
            );

            printf(
                "%d %s(%d)%s\n",
                (int) $number,
                implode('', $surrounding),
                count($surrounding),
                !$isPartNumber ? ' is not a partNumber' : ''
            );

            if ($isPartNumber) {
                $partNumbers[] = (int) $number;
            }

            $number = '';
            $markStart = null;
            $markEnd = null;
        }
    }
}


printf("Suma %d", array_sum($partNumbers));


function getSurrounding($start, $end, $matrix)
{
    $surrounding = [];
    $startX = $start['x'] - 1 > 0 ? $start['x'] - 1 : 0;
    $startY = $start['y'] - 1 > 0 ? $start['y'] - 1 : 0;

    for ($y = $startY; $y <= $end['y'] + 1; $y++) {
        for ($x = $startX; $x <= $end['x'] + 1; $x++) {
            if (($x >= $start['x'] && $x <= $end['x'] && $y >= $start['y'] && $y <= $end['y'])
                || !isset($matrix[$y][$x])
            ) {
                continue;
            }

            $surrounding[] = $matrix[$y][$x];
        }
    }

    return $surrounding;
}
