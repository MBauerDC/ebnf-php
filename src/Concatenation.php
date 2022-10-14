<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Concatenation implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement ...$innerElements,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Concatenation;
        $children = [];
        foreach ($innerElements as $innerElement) {
            $children[] =
                ($innerElement instanceof Alternation) ?
                    new Grouping($this, $innerElement) :
                    $innerElement->withParent($this);
        }
        $this->children = $children;
    }

    /** @return DefinitionElement[] */
    public function getInnerElementTypes(): array {
        return $this->children;
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedConcatenation {
        $currDefinitionElementAncestors[] = $this;
        $elements = [];
        $childStr = \implode(', ', \array_map(fn(DefinitionElement $el) => (string)$el, $this->children));
        $remainingInput = $input;
        foreach ($this->children as $child) {
            $parsedElement = $this->tryParseChild($child, $remainingInput, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
            if ($parsedElement === null) {
                echo "Failed to parse concatenation ($childStr)." . PHP_EOL;
                return null;
            }
            $elements[] = $parsedElement;
        }
        $input = $remainingInput;

        $parsed = new ParsedConcatenation($this->parent, ...$elements);
        echo "Successfully parsed concatenation ($childStr) with value [{$parsed->getParsedString()}]." . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return implode(', ', $this->children);
    }

    public function withParent(DefinitionElement|Definition $parent): Concatenation
    {
        return new self($parent, ...$this->children);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): Concatenation
    {
        $childrenWithReplacedInnerElement = \array_map(
            static fn(DefinitionElement $child) => (string)$child === (string)$oldElement ? $newElement : $child,
            $this->children
        );
        return new Concatenation($this->parent, ...$childrenWithReplacedInnerElement);
    }
}