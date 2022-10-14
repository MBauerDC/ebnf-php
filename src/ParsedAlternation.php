<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedAlternation implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement ...$innerElements,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Alternation;
        $this->children = $innerElements;
        $parsedString = '';
        $found = false;
        $definitionElements = [];
        foreach ($innerElements as $innerElement) {
            if ($innerElement instanceof ParsedDefinitionElement) {
                $parsedString = $innerElement->getParsedString();
                $found = true;
                $definitionElements[] = $innerElement->getInnerDefinitionElement();
            } else {
                $definitionElements[] = $innerElement;
            }
        }
        $this->parsedString = $parsedString;
        $this->innerDefinitionElement = new Alternation(...$definitionElements);
        if (!$found) {
            throw new \InvalidArgumentException('No parsed element found in alternation ' . $this);
        }
    }

    /** @return DefinitionElement[] */
    public function getInnerElements(): array {
        return $this->children;
    }

    public function getParsedInnerElement(): ParsedDefinitionElement {
        foreach ($this->children as $innerElement) {
            if ($innerElement instanceof ParsedDefinitionElement) {
                return $innerElement;
            }
        }
        throw new \ErrorException('No parsed element found in Alternation.');
    }

    public function getInnerDefinitionElement(): Alternation
    {
        return $this->innerDefinitionElement;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedAlternation
    {
        $newChildren = \array_map(static fn(DefinitionElement $child): DefinitionElement => $child->withParent($parent), $this->children);
        return new self($parent, ...$newChildren);
    }

}