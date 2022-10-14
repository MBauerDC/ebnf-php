<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedOptional implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement | ParsedDefinitionElement $innerElement,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Optional;
        $this->children = [$innerElement];
        $this->parsedString = $innerElement instanceof ParsedDefinitionElement
            ? $innerElement->getParsedString()
            : '';
        $this->innerDefinitionElement =
            new Optional(
                $parent,
                $innerElement instanceof ParsedDefinitionElement ?
                    $innerElement->getInnerDefinitionElement() :
                    $innerElement
            );
    }

    public function getInnerElement(): DefinitionElement|ParsedDefinitionElement
    {
        return $this->children[0];
    }

    public function getInnerDefinitionElement(): Optional
    {
        return $this->innerDefinitionElement;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedOptional
    {
        $newChild = $this->children[0]->withParent($parent);
        return new self($parent, $newChild);
    }
}