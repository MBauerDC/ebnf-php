<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedDefinitionReference implements ParsedDefinitionElement
{
    use ParsedElementRepresentation;

    public function __construct(
        public readonly string $definitionName,
        public readonly ParsedDefinition $parsedDefinition,
    ) {
        $this->elementType = DefinitionElementType::DefinitionReference;
        $this->children = [];
        $this->parsedString = $parsedDefinition->getParsedString();
        $this->innerDefinitionElement = $this->parsedDefinition->getRightHandSide();
    }

    public function getDefinitionName(): string
    {
        return $this->definitionName;
    }

    public function getParsedDefinition(): ParsedDefinition
    {
        return $this->parsedDefinition;
    }

    public function getInnerDefinitionElement(): DefinitionReference
    {
        return new DefinitionReference($this->parent, $this->definitionName);
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedDefinitionReference
    {
        $this->parsedDefinition->getRightHandSide()->setParent($parent);
        return new self($this->definitionName, $this->parsedDefinition);
    }
}