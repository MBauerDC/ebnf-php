<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Concatenation implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement ...$innerElementTypes,
    ) {
        $this->elementType = DefinitionElementType::Concatenation;
        $children = [];
        foreach ($innerElementTypes as $innerElementType) {
            $children[] =
                ($innerElementType instanceof Alternation) ?
                    new Grouping($innerElementType) :
                    $innerElementType;
        }
        $this->children = $children;
    }

    /** @return DefinitionElement[] */
    public function getInnerElementTypes(): array {
        return $this->children;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedConcatenation
    {
        $elements = [];
        $childStr = \implode(', ', \array_map(fn(DefinitionElement $el) => (string)$el, $this->children));
        $remainingInput = $input;
        foreach ($this->children as $child) {
            $parsedElement = $child->tryParse($remainingInput, ...$otherDefinitions);
            if ($parsedElement === null) {
                //echo "Failed to parse concatenation ($childStr)." . PHP_EOL;
                return null;
            }
            $elements[] = $parsedElement;
        }
        $input = $remainingInput;

        $parsed = new ParsedConcatenation(...$elements);
        //echo "Successfully parsed concatenation ($childStr) with value [{$parsed->getParsedString()}]." . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return implode(', ', $this->children);
    }
}