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
$numbers = [];
$partNumbers = [];
$n = 0;

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
            $numbers[$n] = (int) $number;
            for ($i = $markStart['x']; $i <= $markEnd['x']; $i++) {
                $matrix[$markEnd['y']][$i] = $n;
            }

            $n++;

            $number = '';
            $markStart = null;
            $markEnd = null;
        }
    }
}

foreach ($matrix as $line) {
    echo implode(' ', $line) . PHP_EOL;
}

for ($y = 0; $y < count($matrix); $y++) {
    for ($x = 0; $x < count($matrix[$y]); $x++) {
        if ('*' === $matrix[$y][$x]) {
            $surrounding = getSurroundingNumbers(['x' => $x, 'y' => $y], $matrix);

            if (count($surrounding) === 2) {
                $sum += ($numbers[array_shift($surrounding)] * $numbers[array_shift($surrounding)]);
            }
        }
    }
}

printf("Suma %d", $sum);


function getSurroundingNumbers($start, $matrix)
{
    $surrounding = [];
    $startX = $start['x'] - 1 > 0 ? $start['x'] - 1 : 0;
    $startY = $start['y'] - 1 > 0 ? $start['y'] - 1 : 0;

    for ($y = $startY; $y <= $start['y'] + 1; $y++) {
        for ($x = $startX; $x <= $start['x'] + 1; $x++) {
            if (($x >= $start['x'] && $x <= $start['x'] && $y >= $start['y'] && $y <= $start['y'])
                || !isset($matrix[$y][$x])
            ) {
                continue;
            }

            $surrounding[] = $matrix[$y][$x];
        }
    }

    return array_unique(array_filter($surrounding, fn ($item) => is_numeric($item)));
}
