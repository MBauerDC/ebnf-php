<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedDefinition extends Definition
{
    public function __construct(
        string $name,
        ParsedDefinitionElement $rightHandSide
    ) {
        parent::__construct($name, $rightHandSide);
    }

    public function getParsedString(): string
    {
        /** @var ParsedDefinitionElement $rhs */
        $rhs = $this->rightHandSide;
        return $rhs->getParsedString();
    }

    public function getRightHandSide(): ParsedDefinitionElement
    {
        /** @var ParsedDefinitionElement $rhs */
        $rhs = $this->rightHandSide;
        return $rhs;
    }
}