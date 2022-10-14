<?php
declare(strict_types=1);

namespace dcAG\EBNF;


class Definition
{
    protected DefinitionElement $rightHandSide;

    public function __construct(
        public readonly string $name,
        DefinitionElement $rightHandSide,
    ) {
        $this->rightHandSide = $rightHandSide->withParent($this);
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedDefinition {
        $parsedElement = $this->rightHandSide->tryParse($input, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
        if (null === $parsedElement) {
            return null;
        }
        return new ParsedDefinition($this->name, $parsedElement);
    }

    public function __toString(): string
    {
        return "{$this->name} = {$this->rightHandSide};" . PHP_EOL;
    }

    public function getRightHandSide(): DefinitionElement
    {
        return $this->rightHandSide;
    }

    public function replaceInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): void
    {
        if ((string)$this->rightHandSide === (string)$oldElement) {
            $this->rightHandSide = $newElement->withParent($this);
        } else {
            $this->rightHandSide->replaceInnerElement($oldElement, $newElement);
        }
    }

    public function __clone()
    {
        $this->rightHandSide = (clone $this->rightHandSide)->withParent($this);
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitPreorder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $this->rightHandSide->visitPreorder($visitor, $parsedElementsOnly);
    }


    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement|Definition $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitPostorder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $this->rightHandSide->visitPostorder($visitor, $parsedElementsOnly);
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement|Definition $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitLevelOrder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $this->rightHandSide->visitLevelOrder($visitor, $parsedElementsOnly);
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitInverseLevelOrder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $this->rightHandSide->visitInverseLevelOrder($visitor, $parsedElementsOnly);
    }
}