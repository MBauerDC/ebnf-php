<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Repetition implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement $innerElementType,
    ) {
        $this->elementType = DefinitionElementType::Repetition;
        $this->children = [$innerElementType];
    }

    public function getInnerElementType(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $parsedElements = [];
        $remainingInput = $input;
        while (true) {
            $parsedElement = $this->children[0]->tryParse($remainingInput, ...$otherDefinitions);
            if ($parsedElement === null) {
                break;
            }
            $parsedElements[] = $parsedElement;
            $remainingInput = substr($remainingInput, \mb_strlen($parsedElement->getParsedString()));
        }
        return new ParsedRepetition($this, ...$parsedElements);
    }

    public function __toString(): string
    {
        return "{{$this->children[0]}}";
    }


}