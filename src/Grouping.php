<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Grouping implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement $innerElement,
    ) {
        $this->elementType = DefinitionElementType::Grouping;
        $this->children = [$innerElement];
    }

    public function getInnerElement(): DefinitionElement
    {
        return $this->children[0];
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $parsedInner = $this->children[0]->tryParse($input, ...$otherDefinitions);
        if (null !== $parsedInner) {
            //echo "Successfully parsed grouping of {$this->getInnerElement()}." . PHP_EOL;
            return new ParsedGrouping($parsedInner);
        }
        //echo "Failed to parse grouping of {$this->getInnerElement()}." . PHP_EOL . PHP_EOL;
        return null;
    }

    public function __toString(): string
    {
        return "({$this->children[0]})";
    }


}