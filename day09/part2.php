<?php

$fr = fopen('input', 'r');

$sum = 0;

while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' === $line) {
        continue;
    }

    $numbers = array_map(fn ($item) => (int) $item, explode(' ', $line));


    $sum += countNextValue($numbers);
}

fclose($fr);

printf("Suma sumarum %d\n", $sum);

function countNextValue(array $input)
{
    $numbers = getNextLine($input);

    $arrayFilter = array_filter($numbers, fn($item) => (int) $item !== 0);

    $lastPlaceholder = 0;

    if ([] !== $arrayFilter) {
        $lastPlaceholder = countNextValue($numbers);
    }

    return array_shift($input) - $lastPlaceholder;
}


function getNextLine(array $input): array
{
    $output = [];

    for ($i = 0; $i < count($input) - 1; $i++) {
        $output[] = $input[$i + 1] - $input[$i];
    }

    return $output;
}
