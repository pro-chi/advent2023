<?php

$fr = fopen('input', 'r');

$steps = str_split(trim(fgets($fr)));

$matches = [];
while (!feof($fr)) {
    $line = trim(fgets($fr));

    if ('' === $line) {
        continue;
    }

    preg_match('~^(\w+) = \((\w+), (\w+)\)$~', $line, $match);

    $matches[$match[1]] = ['left' => $match[2], 'right' => $match[3]];
}

$tmp = [];
$nodes = [];

foreach ($matches as $name => $item) {
    $node = new Node($name);

    $tmp[$name] = $node;

    if (str_ends_with($name, 'A')) {
        $nodes[] = $node;
    }
}

foreach ($matches as $name => $item) {
    $tmp[$name]->setLeft($tmp[$item['left']]);
    $tmp[$name]->setRight($tmp[$item['right']]);
}

unset($matches, $tmp);

$a = 1;

foreach ($nodes as $node) {
    $b = walkThrough($node, $steps);
    // find least common multiple
    $a = gmp_lcm($a, $b);
}

printf("Steps required %d", $a);

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
    private bool $isEndNode = false;


    public function __construct(string $name)
    {
        $this->name = $name;
        $this->isEndNode = str_ends_with($name, 'Z');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEndNode(): bool
    {
        return $this->isEndNode;
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
