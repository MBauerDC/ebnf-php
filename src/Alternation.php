<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Alternation implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement ...$innerElements,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Alternation;
        $children = [];
        foreach ($innerElements as $innerElement) {
            $children[] =
                ($innerElement instanceof Concatenation) ?
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
    ): ?ParsedAlternation {
        //echo PHP_EOL . " - in try parse of alternation from input [$input]" . PHP_EOL;
        $currDefinitionElementAncestors[] = $this;
        $elements = [];
        $found = false;
        $concatenate = array_map(fn(DefinitionElement $el) => (string)$el, $this->children);
        $remainingInput = $input;
        /**
         * @var DefinitionElement $child
         */
        foreach ($this->children as $child) {
            $parsedElement = $this->tryParseChild($child, $remainingInput, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
            $elements[] = $parsedElement ?? $child;
            if ($parsedElement !== null) {
                $found = true;
                break;
            }
        }

        $concatenation = implode(' | ', $concatenate);
        if (!$found) {
            echo "Failed to parse alternation ($concatenation)." . PHP_EOL;
            return null;
        }
        $input = $remainingInput;
        $parsed = new ParsedAlternation($this->parent, ...$elements);
        echo "Successfully parsed alternation ($concatenation) with value [{$parsed->getParsedString()}]." . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return implode(' | ', $this->children);
    }

    public function withParent(DefinitionElement|Definition $parent): Alternation
    {
        return new Alternation($parent, ...$this->children);
    }

    public function withoutBranch(DefinitionElement $branch): Alternation
    {
        $childrenWithoutBranch = \array_filter(
            $this->children,
            static fn(DefinitionElement $child) => (string)$child !== (string)$branch
        );
        return new Alternation($this->parent, ...$childrenWithoutBranch);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): Alternation
    {
        $childrenWithReplacedInnerElement = \array_map(
            static fn(DefinitionElement $child) => (string)$child === (string)$oldElement ? $newElement : $child,
            $this->children
        );
        return new Alternation($this->parent, ...$childrenWithReplacedInnerElement);
    }


}