<?php
declare(strict_types=1);

namespace dcAG\EBNF;

enum DefinitionElementType: string
{
    case Alternation = 'Alternation';
    case Concatenation = 'Concatenation';
    case Optional = 'Optional';
    case Repetition = 'Repetition';
    case Grouping = 'Grouping';
    case TerminalString = 'TerminalString';
    case DefinitionReference = 'DefinitionReference';
}
