<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Optional implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        public readonly DefinitionElement $innerElement
    ) {
        $this->elementType = DefinitionElementType::Optional;
        $this->children = [$this->innerElement];
    }

    public function getInnerElement(): DefinitionElement
    {
        return $this->innerElement;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $parsedElement = $this->innerElement->tryParse($input, ...$otherDefinitions);
        //echo "Successfully parsed optional of {$this->getInnerElement()}." . PHP_EOL;
        return new ParsedOptional($parsedElement ?? $this->innerElement);
    }

    public function __toString(): string
    {
        return "[{$this->innerElement}]";
    }


}