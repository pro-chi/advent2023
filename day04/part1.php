<?php

$fr = fopen('input', 'r');

$cards = [];

while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' === $line) {
        continue;
    }

    [$cardString, $restString] = explode(':', $line);
    sscanf($cardString,'Card %d', $cardNumber);
    [$winningString, $numberString] = explode('|', trim($restString));

    $winningArray = array_filter(explode(' ', trim($winningString)), fn ($item) => !empty($item));
    $numbers = array_filter(explode(' ', trim($numberString)), fn ($item) => !empty($item));
    $cards[(int) $cardNumber] = [
        'winning_numbers' => array_map(static fn ($number) => (int) $number, $winningArray),
        'numbers' => array_map(static fn ($number) => (int) $number, $numbers),
    ];
}

fclose($fr);

$sum = 0;

foreach ($cards as $cardNumber => $card) {

    if (count($card['winning_numbers']) != 10) {
        die('Something wrong');
    }

    if (count($card['numbers']) !== 25) {
        die('Something wrong');
    }

    $wins = array_intersect($card['numbers'], $card['winning_numbers']);

    $winsCount = count($wins);
    $points = 0;

    if ($winsCount) {
        $exponent = $winsCount - 1;
        $points = pow(2, $exponent);
        $sum += $points;
    }

    printf(
        "Card %02d: %s | %s\t=> %s => %d\n",
        $cardNumber,
        implode(' ', $card['winning_numbers']),
        implode(' ', $card['numbers']),
        implode(' ', $wins),
        $points
    );
}

printf("Suma sumarum: %d", $sum);
