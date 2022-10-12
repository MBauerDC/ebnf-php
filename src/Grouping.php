<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Grouping implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement $innerElement,
    ) {
        $this->elementType = DefinitionElementType::Grouping;
        $this->children = [$innerElement];
    }

    public function getInnerElement(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        return $this->children[0]->tryParse($input, ...$otherDefinitions);
    }

    public function __toString(): string
    {
        return "({$this->children[0]})";
    }


}