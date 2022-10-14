<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Repetition implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        DefinitionElement                     $innerElement,
        public readonly bool                  $atLeastOne = false,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::Repetition;
        $this->children = [$innerElement->withParent($this)];
    }

    public function getInnerElementType(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedRepetition {
        $currDefinitionElementAncestors[] = $this;
        $parsedElements = [];
        $remainingInput = $input;
        while (true) {
            //echo PHP_EOL . " PARSING CHILD " . PHP_EOL;
            $parsedElement = $this->tryParseChild($this->children[0], $remainingInput, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
            if ($parsedElement === null) {
                break;
            }
            $parsedElements[] = $parsedElement;
            $parsedSoFar = implode('', array_map(fn($el) => $el->getParsedString(), $parsedElements));
            //echo "Repetition parsing pass for {$this->getInnerElementType()}. Remaining input: [$remainingInput]. Parsed so far: [$parsedSoFar]" . PHP_EOL;

        }
        if ($this->atLeastOne && count($parsedElements) === 0) {
            //echo "Failed to parse repetition of {$this->getInnerElementType()}." . PHP_EOL . PHP_EOL;
            return null;
        }

        $input = $remainingInput;
        $parsed = new ParsedRepetition($this->parent, $this, ...$parsedElements);

        //echo "Successfully parsed repetition of {$this->getInnerElementType()} as value [{$parsed->getParsedString()}]" . PHP_EOL . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return "{{$this->children[0]}}";
    }

    public function withParent(DefinitionElement|Definition $parent): Repetition
    {
        return new self($parent, $this->children[0], $this->atLeastOne);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): Repetition
    {
        $currChild = $this->children[0];
        $replacement = (string)$currChild === (string)$oldElement ? $newElement : $currChild;
        return new Repetition($this->parent, $replacement, $this->atLeastOne);
    }


}