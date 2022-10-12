<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedGrouping implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
       ParsedDefinitionElement $innerElement,
    ) {
        $this->elementType = DefinitionElementType::Grouping;
        $this->children = [$innerElement];
        $this->parsedString = $innerElement->getParsedString();
        $this->innerDefinitionElement = new Grouping($innerElement);
    }

    public function getInnerElement(): ParsedDefinitionElement
    {
        return $this->children[0];
    }

    public function getInnerDefinitionElement(): Grouping
    {
        return $this->innerDefinitionElement;
    }
}