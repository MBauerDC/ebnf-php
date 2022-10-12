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

    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedAlternation
    {
        $elements = [];
        $found = false;
        foreach ($this->children as $child) {
            $parsedElement = $child->tryParse($input, ...$otherDefinitions);
            $elements[] = $parsedElement ?? $child;
            if ($parsedElement !== null) {
                $found = true;
            }
        }
        if (!$found) {
            return null;
        }
        return new ParsedAlternation(...$elements);
    }

    public function __toString(): string
    {
        return implode(' | ', $this->children);
    }


}