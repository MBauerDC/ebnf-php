<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class ParsedTerminalString extends TerminalString implements ParsedDefinitionElement
{
    public function getParsedString(): string
    {
        return $this->value;
    }

    public function getInnerDefinitionElement(): DefinitionElement
    {
        return $this;
    }

    public function withParent(DefinitionElement|Definition $parent): ParsedTerminalString
    {
        return new self($parent, $this->value);
    }

}