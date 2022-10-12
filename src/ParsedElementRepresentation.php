<?php
declare(strict_types=1);

namespace dcAG\EBNF;

trait ParsedElementRepresentation
{
    use ElementRepresentation;
    public readonly string $parsedString;
    protected DefinitionElement $innerDefinitionElement;

    public function getParsedString(): string
    {
        return $this->parsedString;
    }

    public function getInnerDefinitionElement(): DefinitionElement
    {
        return $this->innerDefinitionElement;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        return $this->innerDefinitionElement->tryParse($input, ...$otherDefinitions);
    }

    public function __toString(): string
    {
        return $this->innerDefinitionElement->__toString();
    }
}