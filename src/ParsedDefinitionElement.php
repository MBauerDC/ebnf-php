<?php
declare(strict_types=1);

namespace dcAG\EBNF;

interface ParsedDefinitionElement extends DefinitionElement
{
    public function getParsedString(): string;
    public function getInnerDefinitionElement(): DefinitionElement;
    public function withParent(DefinitionElement|Definition $parent): ParsedDefinitionElement;
}