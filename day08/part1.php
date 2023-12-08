<?php

$fr = fopen('input', 'r');

$steps = str_split(trim(fgets($fr)));

$matches = [];
$nodes = [];

while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' === $line) {
        continue;
    }

    preg_match('~^([A-Z]+) = \(([A-Z]+), ([A-Z]+)\)$~', $line, $match);

    $nodes[$match[1]] = new Node($match[1]);
    $matches[$match[1]] = ['left' => $match[2], 'right' => $match[3]];
}

foreach ($matches as $name => $item) {
    $nodes[$name]->setLeft($nodes[$item['left']]);
    $nodes[$name]->setRight($nodes[$item['right']]);
}

$node = $nodes['AAA'];
unset($matches, $nodes);

printf("Steps required %d", walkThrough($node, $steps));

function walkThrough(Node $node, array $steps): int
{
    $j = 0;
    $i = 0;

    while (!$node->isEndNode()) {
        $step = $steps[$i];
        $node = ($step === 'L') ? $node->getLeft() : $node = $node->getRight();

        ++$j;
        ++$i;

        if ($i === count($steps)) {
            $i = 0;
        }
    }

    return $j;
}

class Node
{
    private string $name;
    private ?Node $left;
    private ?Node $right;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isEndNode()
    {
        return 'ZZZ' === $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLeft(): ?Node
    {
        return $this->left;
    }

    public function setLeft(?Node $left): void
    {
        $this->left = $left;
    }

    public function getRight(): ?Node
    {
        return $this->right;
    }

    public function setRight(?Node $right): void
    {
        $this->right = $right;
    }
}
