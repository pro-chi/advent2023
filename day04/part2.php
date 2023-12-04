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

    $winningArray = array_map(static fn ($number) => (int) $number, array_filter(explode(' ', trim($winningString)), fn ($item) => !empty($item)));
    $numbers = array_map(static fn ($number) => (int) $number,array_filter(explode(' ', trim($numberString)), fn ($item) => !empty($item)));

    $cards[(int) $cardNumber] = [
        'plays' => 0,
        'number' => $cardNumber,
        'points' => count(array_intersect($winningArray, $numbers)),
    ];
}

fclose($fr);

$sum = 0;
$playedCards = [];
$newCards = [];


do {
    $currentCard = array_shift($cards);
    $cardNumber = $currentCard['number'];

    $playing[$cardNumber][] = $currentCard;
    printf("%d - %d (%d)\n", $cardNumber, $currentCard['points'], count($playing[$cardNumber]));

    while ([] !== $playing[$cardNumber]) {
        $card = array_shift($playing[$cardNumber]);
        $playedCards[] = $card;
        $newCards = array_slice($cards, 0, $card['points']);

        foreach ($newCards as $item) {
            $playing[$item['number']][] = $item;
        }
    }
} while( ([] !== $cards));

printf("Suma sumarum: %d", count($playedCards));
