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

    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedConcatenation
    {
        $elements = [];
        foreach ($this->children as $child) {
            $parsedElement = $child->tryParse($input, ...$otherDefinitions);
            if ($parsedElement === null) {
                return null;
            }
            $elements[] = $parsedElement;
        }
        return new ParsedConcatenation(...$elements);
    }

    public function __toString(): string
    {
        return implode(', ', $this->children);
    }
}