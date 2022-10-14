<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Optional implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement $innerElement
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Optional;
        $this->children = [$innerElement->withParent($this)];
    }


    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedOptional {
        $currDefinitionElementAncestors[] = $this;
        $parsedInner = $this->tryParseChild($this->children[0], $input, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
        //echo "Successfully parsed optional of {$this->getInnerElement()}." . PHP_EOL;
        return new ParsedOptional($this->parent, $parsedInner ?? $this->children[0]);
    }

    public function __toString(): string
    {
        return "[{$this->children[0]}]";
    }

    public function withParent(DefinitionElement|Definition $parent): Optional
    {
        return new self($parent, $this->children[0]);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): Optional
    {
        $oldChild = $this->children[0];
        $replacement = (string)$oldChild === (string)$oldElement ? $newElement : $oldChild;
        return new Optional($this->parent, $replacement);
    }

}