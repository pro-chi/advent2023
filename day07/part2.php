<?php

$fr = fopen('input', 'r');

$hands = [];

while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' === $line) {
        continue;
    }

    sscanf($line, "%s %d\n", $hand, $bid);
    $hands[] = [
        'hand' => $hand,
        'bid' => $bid,
        'value' => getHandValue($hand)
    ];
}

usort(
    $hands,
    static function ($a, $b) {
        if ($a['value'] === $b['value']) {
            return compareHandByCards($a['hand'], $b['hand']);
        }

        return $a['value'] <=> $b['value'];
    }
);

$result = 0;

foreach ($hands as $i => $hand) {
    $result += ($i + 1) * $hand['bid'];
}

printf("Suma sumarum: %d", $result);

function getHandValue(string $hand): int
{
    if (!defined('FIVE_OF_A_KIND')) {
        define('FIVE_OF_A_KIND', 7);
        define('FOUR_OF_A_KIND', 6);
        define('FULL_HOUSE', 5);
        define('THREE_OF_A_KIND', 4);
        define('TWO_PAIR', 3);
        define('ONE_PAIR', 2);
        define('HIGH_CARDS', 1);
    }

    $cards = [];

    foreach (str_split($hand) as $card) {
        $cards[$card] = isset($cards[$card]) ? $cards[$card] + 1 : 1;
    }

    $cards = useJokers($cards);

    $cardTypes = count($cards);

    if ($cardTypes === 1) {
        return FIVE_OF_A_KIND;
    }

    if (count(array_filter($cards, fn ($item) => $item === 4)) === 1) {
        return FOUR_OF_A_KIND;
    }

    if ($cardTypes === 2) {
        return FULL_HOUSE;
    }

    if ($cardTypes === 3 && count(array_filter($cards, fn ($item) => $item === 3)) === 1) {
        return THREE_OF_A_KIND;
    }

    if ($cardTypes === 3 && count(array_filter($cards, fn ($item) => $item === 2)) === 2) {
        return TWO_PAIR;
    }

    if ($cardTypes === 4) {
        return ONE_PAIR;
    }

    if ($cardTypes === 5 && [] !== array_filter($cards, fn ($item) => in_array($item, [2, 3, 4, 5, 6]))) {
        return HIGH_CARDS;
    }

    return 0;
}

function useJokers(array $cards): array
{
    if (!isset($cards['J'])) {
        return $cards;
    }

    $jokers = $cards['J'];
    unset($cards['J']);

    if ([] === $cards) {
        return ['A' => 5];
    }

    if (count($cards) !== 4) {
        asort($cards);
    } else {
        uksort($cards, 'compareCard');
    }

    $topCard = array_key_last($cards);
    $cards[$topCard] += $jokers;

    return $cards;
}

function compareHandByCards(string $a, string $b): int
{
    $cardsA = str_split($a);
    $cardsB = str_split($b);


    foreach ($cardsA as $i => $valueA) {
        $valueB = $cardsB[$i];
        if ($valueA === $valueB) {
            continue;
        }

        return compareCard($valueA, $valueB);
    }

    return 0;
}


function compareCard($a, $b)
{
    $cardValues = [
        'J' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'T' => 10,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];

    return $cardValues[$a] <=> $cardValues[$b];
}
