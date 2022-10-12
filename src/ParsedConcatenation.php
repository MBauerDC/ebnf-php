<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedConcatenation implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        ParsedDefinitionElement ...$parsedInnerElements
    ) {
        $this->elementType = DefinitionElementType::Concatenation;
        $this->children = $parsedInnerElements;
        $parsedString = '';
        foreach ($parsedInnerElements as $parsedInnerElement) {
            $parsedString .= $parsedInnerElement->getParsedString();
        }
        $this->parsedString = $parsedString;
        $this->innerDefinitionElement = new Concatenation(...$parsedInnerElements);
    }

    /** @return ParsedDefinitionElement[] */
    public function getParsedInnerElements(): array {
        return $this->children;
    }

    public function getInnerDefinitionElement(): Concatenation
    {
        return $this->innerDefinitionElement;
    }

}