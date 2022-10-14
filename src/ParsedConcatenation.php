<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedConcatenation implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement|Definition|null $parent,
        ParsedDefinitionElement ...$parsedInnerElements
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Concatenation;
        $this->children = $parsedInnerElements;
        $parsedString = '';
        foreach ($parsedInnerElements as $parsedInnerElement) {
            $parsedString .= $parsedInnerElement->getParsedString();
        }
        $this->parsedString = $parsedString;
        $this->innerDefinitionElement = new Concatenation(...\array_map(fn(ParsedDefinitionElement $el) => $el->getInnerDefinitionElement(), $parsedInnerElements));
    }

    /** @return ParsedDefinitionElement[] */
    public function getParsedInnerElements(): array {
        return $this->children;
    }

    public function getInnerDefinitionElement(): Concatenation
    {
        return $this->innerDefinitionElement;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedConcatenation
    {
        $newChildren = \array_map(static fn(ParsedDefinitionElement $child): ParsedDefinitionElement => $child->withParent($parent), $this->children);
        return new self($parent, ...$newChildren);
    }

}