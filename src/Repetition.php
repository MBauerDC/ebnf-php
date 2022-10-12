<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Repetition implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement $innerElementType,
        public readonly bool $atLeastOne = false,
    ) {
        $this->elementType = DefinitionElementType::Repetition;
        $this->children = [$innerElementType];
    }

    public function getInnerElementType(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $parsedElements = [];
        $remainingInput = $input;
        while (true) {
            $parsedElement = $this->children[0]->tryParse($remainingInput, ...$otherDefinitions);
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

        $parsed = new ParsedRepetition($this, ...$parsedElements);
        //echo "Successfully parsed repetition of {$this->getInnerElementType()} as value [{$parsed->getParsedString()}]" . PHP_EOL . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return "{{$this->children[0]}}";
    }


}