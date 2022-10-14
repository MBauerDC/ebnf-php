<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Grouping implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement $innerElement,
    ) {
        if (!($innerElement instanceof Alternation || $innerElement instanceof Concatenation)) {
            throw new \InvalidArgumentException('Grouping can only be used with Alternation or Concatenation. Tried to add ' . get_class($innerElement));
        }
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Grouping;
        $this->children = [$innerElement->withParent($this)];
    }

    public function getInnerElement(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedGrouping {
        $currDefinitionElementAncestors[] = $this;
        $parsedInner = $this->tryParseChild($this->children[0], $input, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
        if (null !== $parsedInner) {
            //echo "Successfully parsed grouping of {$this->getInnerElement()}." . PHP_EOL;
            return new ParsedGrouping($this->parent, $parsedInner);
        }
        //echo "Failed to parse grouping of {$this->getInnerElement()}." . PHP_EOL . PHP_EOL;
        return null;
    }

    public function __toString(): string
    {
        return "({$this->children[0]})";
    }

    public function withParent(DefinitionElement|Definition $parent): Grouping
    {
        return new self($parent, $this->children[0]);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): Grouping
    {
        if (!($newElement instanceof Alternation || $newElement instanceof Concatenation)) {
            throw new \InvalidArgumentException('Grouping can only be used with Alternation or Concatenation');
        }
        $oldChild = $this->children[0];
        $replacement = (string)$oldChild === (string)$oldElement ? $newElement : $oldChild;
        return new Grouping($this->parent, $replacement);
    }


}