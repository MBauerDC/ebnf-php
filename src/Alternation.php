<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Alternation implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement ...$innerElementTypes,
    ) {
        $this->elementType = DefinitionElementType::Alternation;
        $children = [];
        foreach ($innerElementTypes as $innerElementType) {
            $children[] =
                ($innerElementType instanceof Concatenation) ?
                    new Grouping($innerElementType) :
                    $innerElementType;
        }
        $this->children = $children;
    }

    /** @return DefinitionElement[] */
    public function getInnerElementTypes(): array {
        return $this->children;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedAlternation
    {
        $elements = [];
        $found = false;
        $concatenate = array_map(fn(DefinitionElement $el) => (string)$el, $this->children);
        $remainingInput = $input;
        foreach ($this->children as $child) {
            $parsedElement = $child->tryParse($remainingInput, ...$otherDefinitions);
            $elements[] = $parsedElement ?? $child;
            if ($parsedElement !== null) {
                $found = true;
                break;
            }
        }

        $concatenation = implode(' | ', $concatenate);
        if (!$found) {
            //echo "Failed to parse alternation ($concatenation)." . PHP_EOL;
            return null;
        }
        $input = $remainingInput;
        $parsed = new ParsedAlternation(...$elements);
        //echo "Successfully parsed alternation ($concatenation) with value [{$parsed->getParsedString()}]." . PHP_EOL;
        return $parsed;
    }

    public function __toString(): string
    {
        return implode(' | ', $this->children);
    }


}