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

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedDefinitionElement {
        return $this->innerDefinitionElement->tryParse($input, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
    }

    public function __toString(): string
    {
        return $this->innerDefinitionElement->__toString();
    }


    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): DefinitionElement
    {
        return $this;
    }
}