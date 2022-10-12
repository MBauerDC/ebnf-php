<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedAlternation implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement ...$innerElements,
    ) {
        $this->elementType = DefinitionElementType::Alternation;
        $this->children = $innerElements;
        $parsedString = '';
        $found = false;
        foreach ($innerElements as $innerElement) {
            if ($innerElement instanceof ParsedDefinitionElement) {
                $parsedString = $innerElement->getParsedString();
                $found = true;
                break;
            }
        }
        $this->parsedString = $parsedString;
        $this->innerDefinitionElement = new Alternation(...$innerElements);
        if (!$found) {
            throw new \InvalidArgumentException('No parsed element found in alternation ' . $this);
        }
    }

    /** @return DefinitionElement[] */
    public function getInnerElements(): array {
        return $this->children;
    }

    public function getParsedInnerElement(): ParsedDefinitionElement {
        foreach ($this->children as $innerElement) {
            if ($innerElement instanceof ParsedDefinitionElement) {
                return $innerElement;
            }
        }
        throw new \ErrorException('No parsed element found in Alternation.');
    }

    public function getInnerDefinitionElement(): Alternation
    {
        return $this->innerDefinitionElement;
    }

}