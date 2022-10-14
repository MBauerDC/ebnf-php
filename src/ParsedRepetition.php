<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedRepetition implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        DefinitionElement|Definition|null $parent,
        Repetition $innerDefinitionElement,
        ParsedDefinitionElement ...$parsedInnerElements,
    ) {
        $this->parent = $parent;
        /*
        if (\count($parsedInnerElements) === 0) {
            throw new \InvalidArgumentException('At least one parsed inner element is required.');
        }*/
        $this->elementType = DefinitionElementType::Repetition;
        $this->children = $parsedInnerElements;
        $this->parsedString = array_reduce(
            $parsedInnerElements,
            fn(
                string $carry,
                ParsedDefinitionElement $parsedInnerElement
            ): string =>
                $carry . $parsedInnerElement->getParsedString(),
            '',
        );
        $this->innerDefinitionElement = $innerDefinitionElement;
    }

    /**
     * @return ParsedDefinitionElement[]
     */
    public function getInnerElements(): array
    {
        return $this->children;
    }

    public function getInnerDefinitionElement(): Repetition
    {
        return $this->innerDefinitionElement;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedRepetition
    {
        $newChildren = \array_map(static fn(ParsedDefinitionElement $child): ParsedDefinitionElement => $child->withParent($parent), $this->children);
        /** @var Repetition $innerDefinitionElement */
        return new self($parent, $this->innerDefinitionElement->withParent($parent), ...$newChildren);
    }
}