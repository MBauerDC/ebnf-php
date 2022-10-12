<?php
declare(strict_types=1);

namespace dcAG\EBNF;


class Definition
{
    public function __construct(
        public readonly string $name,
        public readonly DefinitionElement $rightHandSide,
    ) {}

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $parsedElement = $this->rightHandSide->tryParse($input, ...$otherDefinitions);
        return $parsedElement;
    }

    public function __toString(): string
    {
        return "{$this->name} = {$this->rightHandSide};" . PHP_EOL;
    }
}