<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedOptional implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement | ParsedDefinitionElement $innerElement,
    ) {
        $this->elementType = DefinitionElementType::Optional;
        $this->children = [$innerElement];
        $this->parsedString = $innerElement instanceof ParsedDefinitionElement
            ? $innerElement->getParsedString()
            : '';
        $this->innerDefinitionElement = new Optional($innerElement);
    }

    public function getInnerElement(): DefinitionElement|ParsedDefinitionElement
    {
        return $this->children[0];
    }

    public function getInnerDefinitionElement(): Optional
    {
        return $this->innerDefinitionElement;
    }
}