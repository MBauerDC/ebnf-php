<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedGrouping implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
       ParsedDefinitionElement $innerElement,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Grouping;
        $this->children = [$innerElement];
        $this->parsedString = $innerElement->getParsedString();
        $this->innerDefinitionElement = new Grouping($parent, $innerElement->getInnerDefinitionElement());
    }

    public function getInnerElement(): ParsedDefinitionElement
    {
        return $this->children[0];
    }

    public function getInnerDefinitionElement(): Grouping
    {
        return $this->innerDefinitionElement;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedGrouping
    {
        $newChild = $this->children[0]->withParent($parent);
        return new self($parent, $newChild);
    }
}