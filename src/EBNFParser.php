<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class EBNFParser
{
    protected readonly EBNFGrammar $EBNFGrammar;

    public function __construct() {
        $this->EBNFGrammar = $this->buildEBNFGrammar();
    }

    public function getLetterDefinition(): Definition
    {
        return new Definition(
            'letter',
            new Alternation(null,
                new TerminalString(null,'a'),
                new TerminalString(null,'b'),
                new TerminalString(null,'c'),
                new TerminalString(null,'d'),
                new TerminalString(null,'e'),
                new TerminalString(null,'f'),
                new TerminalString(null,'g'),
                new TerminalString(null,'h'),
                new TerminalString(null,'i'),
                new TerminalString(null,'j'),
                new TerminalString(null,'k'),
                new TerminalString(null,'l'),
                new TerminalString(null,'m'),
                new TerminalString(null,'n'),
                new TerminalString(null,'o'),
                new TerminalString(null,'p'),
                new TerminalString(null,'q'),
                new TerminalString(null,'r'),
                new TerminalString(null,'s'),
                new TerminalString(null,'t'),
                new TerminalString(null,'u'),
                new TerminalString(null,'v'),
                new TerminalString(null,'w'),
                new TerminalString(null,'x'),
                new TerminalString(null,'y'),
                new TerminalString(null,'z'),
                new TerminalString(null,'A'),
                new TerminalString(null,'B'),
                new TerminalString(null,'C'),
                new TerminalString(null,'D'),
                new TerminalString(null,'E'),
                new TerminalString(null,'F'),
                new TerminalString(null,'G'),
                new TerminalString(null,'H'),
                new TerminalString(null,'I'),
                new TerminalString(null,'J'),
                new TerminalString(null,'K'),
                new TerminalString(null,'L'),
                new TerminalString(null,'M'),
                new TerminalString(null,'N'),
                new TerminalString(null,'O'),
                new TerminalString(null,'P'),
                new TerminalString(null,'Q'),
                new TerminalString(null,'R'),
                new TerminalString(null,'S'),
                new TerminalString(null,'T'),
                new TerminalString(null,'U'),
                new TerminalString(null,'V'),
                new TerminalString(null,'W'),
                new TerminalString(null,'X'),
                new TerminalString(null,'Y'),
                new TerminalString(null,'Z')
            )
        );
    }

    public function getDigitDefinition(): Definition
    {
        return new Definition(
            'digit',
            new Alternation(null,
                new TerminalString(null,'0'),
                new TerminalString(null,'1'),
                new TerminalString(null,'2'),
                new TerminalString(null,'3'),
                new TerminalString(null,'4'),
                new TerminalString(null,'5'),
                new TerminalString(null,'6'),
                new TerminalString(null,'7'),
                new TerminalString(null,'8'),
                new TerminalString(null,'9')
            )
        );
    }

    public function getSymbolDefinition(): Definition
    {
        return new Definition(
            'symbol',
            new Alternation(null,
                new TerminalString(null,'['),
                new TerminalString(null,']'),
                new TerminalString(null,'{'),
                new TerminalString(null,'}'),
                new TerminalString(null,'('),
                new TerminalString(null,')'),
                new TerminalString(null,'<'),
                new TerminalString(null,'>'),
                new TerminalString(null,'"'),
                new TerminalString(null,"'"),
                new TerminalString(null,"-"),
                new TerminalString(null,'='),
                new TerminalString(null,'|'),
                new TerminalString(null,'~'),
                new TerminalString(null,'^'),
                new TerminalString(null,'°'),
                new TerminalString(null,'?'),
                new TerminalString(null,'`'),
                new TerminalString(null,'´'),
                new TerminalString(null,'*'),
                new TerminalString(null,'+'),
                new TerminalString(null,'#'),
                new TerminalString(null,'_'),
                new TerminalString(null,'!'),
                new TerminalString(null,'§'),
                new TerminalString(null,'$'),
                new TerminalString(null,'%'),
                new TerminalString(null,'&'),
                new TerminalString(null,'@'),
                new TerminalString(null,'€'),
                new TerminalString(null,'/'),
                new TerminalString(null,'\\'),
                new TerminalString(null,'.'),
                new TerminalString(null,','),
                new TerminalString(null,';'),
            )
        );
    }

    public function getSymbolDefinitionWithoutQuotes(): Definition
    {
        return new Definition(
            'symbolWOQuotes',
            new Alternation(null,
                new TerminalString(null,'['),
                new TerminalString(null,']'),
                new TerminalString(null,'{'),
                new TerminalString(null,'}'),
                new TerminalString(null,'('),
                new TerminalString(null,')'),
                new TerminalString(null,'<'),
                new TerminalString(null,'>'),
                new TerminalString(null,'"'),
                new TerminalString(null,"-"),
                new TerminalString(null,'='),
                new TerminalString(null,'|'),
                new TerminalString(null,'~'),
                new TerminalString(null,'^'),
                new TerminalString(null,'°'),
                new TerminalString(null,'?'),
                new TerminalString(null,'`'),
                new TerminalString(null,'´'),
                new TerminalString(null,'*'),
                new TerminalString(null,'+'),
                new TerminalString(null,'#'),
                new TerminalString(null,'_'),
                new TerminalString(null,'!'),
                new TerminalString(null,'§'),
                new TerminalString(null,'$'),
                new TerminalString(null,'%'),
                new TerminalString(null,'&'),
                new TerminalString(null,'@'),
                new TerminalString(null,'€'),
                new TerminalString(null,'/'),
                new TerminalString(null,'\\'),
                new TerminalString(null,'.'),
                new TerminalString(null,','),
                new TerminalString(null,';'),
            )
        );
    }

    public function getSymbolDefinitionWithoutDoubleQuotes(): Definition
    {
        return new Definition(
            'symbolWODblQuotes',
            new Alternation(null,
                new TerminalString(null,'['),
                new TerminalString(null,']'),
                new TerminalString(null,'{'),
                new TerminalString(null,'}'),
                new TerminalString(null,'('),
                new TerminalString(null,')'),
                new TerminalString(null,'<'),
                new TerminalString(null,'>'),
                new TerminalString(null,"'"),
                new TerminalString(null,"-"),
                new TerminalString(null,'='),
                new TerminalString(null,'|'),
                new TerminalString(null,'~'),
                new TerminalString(null,'^'),
                new TerminalString(null,'°'),
                new TerminalString(null,'?'),
                new TerminalString(null,'`'),
                new TerminalString(null,'´'),
                new TerminalString(null,'*'),
                new TerminalString(null,'+'),
                new TerminalString(null,'#'),
                new TerminalString(null,'_'),
                new TerminalString(null,'!'),
                new TerminalString(null,'§'),
                new TerminalString(null,'$'),
                new TerminalString(null,'%'),
                new TerminalString(null,'&'),
                new TerminalString(null,'@'),
                new TerminalString(null,'€'),
                new TerminalString(null,'/'),
                new TerminalString(null,'\\'),
                new TerminalString(null,'.'),
                new TerminalString(null,','),
                new TerminalString(null,';'),
            )
        );
    }

    public function getCharacterDefinition(): Definition
    {
        return new Definition(
            'character',
            new Alternation(null,
                new DefinitionReference(null,'letter'),
                new DefinitionReference(null,'digit'),
                new DefinitionReference(null,'symbol'),
                new TerminalString(null,'_')
            )
        );
    }

    public function getCharacterDefinitionWithoutQuotes(): Definition
    {
        return new Definition(
            'characterWOQuotes',
            new Alternation(null,
                new DefinitionReference(null,'letter'),
                new DefinitionReference(null,'digit'),
                new DefinitionReference(null,'symbolWOQuotes'),
                new TerminalString(null,'_')
            )
        );
    }

    public function getCharacterDefinitionWithoutDoubleQuotes(): Definition
    {
        return new Definition(
            'characterWODblQuotes',
            new Alternation(null,
                new DefinitionReference(null,'letter'),
                new DefinitionReference(null,'digit'),
                new DefinitionReference(null,'symbolWODblQuotes'),
                new TerminalString(null,'_')
            )
        );
    }

    public function getIdentifierDefinition(): Definition
    {
        return new Definition(
            'identifier',
            new Concatenation(null,
                new DefinitionReference(null,'letter'),
                new Repetition(null,
                    new Alternation(null,
                        new DefinitionReference(null,'letter'),
                        new DefinitionReference(null,'digit'),
                        new TerminalString(null,'_')
                    )
                )
            )
        );
    }

    public function getTerminalDefinition(): Definition
    {
        return new Definition(
            'terminal',
            new Alternation(null,
                new Concatenation(null,
                    new TerminalString(null,'"'),
                    new DefinitionReference(null,'characterWODblQuotes'),
                    new Repetition(null,
                        new DefinitionReference(null,'characterWODblQuotes'),
                    ),
                    new TerminalString(null,'"')
                ),
                new Concatenation(null,
                    new TerminalString(null,"'"),
                    new DefinitionReference(null,'characterWOQuotes'),
                    new Repetition(null,
                        new DefinitionReference(null,'characterWOQuotes'),
                    ),
                    new TerminalString(null,"'"),
                ),
            )
        );
    }

    public function getLHSDefinition(): Definition
    {
        return new Definition(
            'lhs',
            new DefinitionReference(null,'identifier')
        );
    }

    public function getInlineWhitespaceDefinition(): Definition
    {
        return new Definition(
            'inlineWhitespace',
            new Concatenation(null,
                new Alternation(null,
                    new TerminalString(null,' '),
                    new TerminalString(null,'\t'),
                ),
                new Repetition(null,
                    new Alternation(null,
                        new TerminalString(null,' '),
                        new TerminalString(null,'\t'),
                    )
                )
            )
        );
    }

    public function getNewLineWhitespaceDefinition(): Definition
    {
        return new Definition(
            'newLineWhitespace',
            new Concatenation(null,
                new Alternation(null,
                    new TerminalString(null,'\\r'),
                    new TerminalString(null,'\\n'),
                ),
                new Repetition(null,
                    new Alternation(null,
                        new TerminalString(null,'\\r'),
                        new TerminalString(null,'\\n'),
                    )
                )
            )
        );
    }

    public function getAllWhiteSapceDefinition(): Definition
    {
        return new Definition(
            'allWhitespace',
            new Concatenation(null,
                new Alternation(null,
                    new DefinitionReference(null,'inlineWhitespace'),
                    new DefinitionReference(null,'newLineWhitespace'),
                ),
                new Repetition(null,
                    new Alternation(null,
                        new DefinitionReference(null,'inlineWhitespace'),
                        new DefinitionReference(null,'newLineWhitespace'),
                    )
                )
            )
        );
    }

    public function getConcatenationDefinition(): Definition
    {
        return new Definition(
            'concatenation',
            new Concatenation(null,
                new DefinitionReference(null,'rhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,','),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
            )
        );
    }

    public function getAlternationDefinition(): Definition
    {
        return new Definition(
            'alternation',
            new Concatenation(null,
                new DefinitionReference(null,'rhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,'|'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
            )
        );
    }

    public function getOptionalDefinition(): Definition
    {
        return new Definition(
            'optional',
            new Concatenation(null,
                new TerminalString(null,'['),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,']')
            )
        );
    }

    public function getGroupingDefinition(): Definition
    {
        return new Definition(
            'grouping',
            new Concatenation(null,
                new TerminalString(null,'('),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,')')
            )
        );
    }

    public function getRepetitionDefinition(): Definition
    {
        return new Definition(
            'repetition',
            new Concatenation(null,
                new TerminalString(null,'('),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,')')
            )
        );
    }

    public function getRHSDefinition(): Definition
    {
        return new Definition(
            'rhs',
                new Alternation(null,
                    new Concatenation(null,
                        new DefinitionReference(null,'rhs'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new Repetition(null,
                            new Concatenation(null,
                                new TerminalString(null,','),
                                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                                new DefinitionReference(null,'rhs'),
                            ),
                            atLeastOne: true
                        ),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new DefinitionReference(null,'rhs'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new Repetition(null,
                            new Concatenation(null,
                                new TerminalString(null,'|'),
                                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                                new DefinitionReference(null,'rhs'),
                            ),
                            atLeastOne: true
                        ),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new TerminalString(null,'['),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new DefinitionReference(null,'rhs'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new TerminalString(null,']'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new TerminalString(null,'{'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new DefinitionReference(null,'rhs'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new TerminalString(null,'}'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new TerminalString(null,'('),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new DefinitionReference(null,'rhs'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                        new TerminalString(null,')'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new DefinitionReference(null,'identifier'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                    new Concatenation(null,
                        new DefinitionReference(null,'terminal'),
                        new Optional(null,new DefinitionReference(null,'inlineWhitespace'))
                    ),
                )

        );
    }

    public function getRuleDefinition(): Definition
    {
        return new Definition(
            'rule',
            new Concatenation(null,
                new DefinitionReference(null,'lhs'),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new TerminalString(null,'='),
                new Optional(null,new DefinitionReference(null,'inlineWhitespace')),
                new DefinitionReference(null,'rhs'),
                new TerminalString(null,';'),
                new Optional(null, new DefinitionReference(null,'allWhitespace')),
            )
        );
    }

    public function getGrammarDefinition(): Definition
    {
        return new Definition(
            'grammar',
            new Repetition(null,
                new DefinitionReference(null,'rule')
            )
        );
    }

    public function buildEBNFGrammar(): EBNFGrammar
    {
        return new EBNFGrammar(
            new DefinitionList(
                $this->getLetterDefinition(),
                $this->getDigitDefinition(),
                $this->getSymbolDefinition(),
                $this->getSymbolDefinitionWithoutQuotes(),
                $this->getSymbolDefinitionWithoutDoubleQuotes(),
                $this->getCharacterDefinition(),
                $this->getCharacterDefinitionWithoutQuotes(),
                $this->getCharacterDefinitionWithoutDoubleQuotes(),
                $this->getIdentifierDefinition(),
                $this->getTerminalDefinition(),
                $this->getLHSDefinition(),
                $this->getInlineWhitespaceDefinition(),
                $this->getNewLineWhitespaceDefinition(),
                $this->getAllWhiteSapceDefinition(),
                $this->getRHSDefinition(),
                $this->getRuleDefinition(),
                $this->getGrammarDefinition(),
            ),
            'grammar'
        );
    }

    public function parseEBNFGrammer(string $input): mixed
    {
        $parseResult = $this->EBNFGrammar->tryParse($input);
        return $parseResult;
        /*
        if (null === $parseResult) {
            throw new \Exception('Could not parse EBNF grammar.');
        }
        $terminals = [];
        $identifiers = [];
        $concatenations = [];
        $alternations = [];
        $optionals = [];
        $groupings = [];
        $repetitions = [];
        $rules = [];

        $ruleDefinitions = [];
        $ruleDefinitionExtractingVisitor =
            function (ParsedDefinitionElement $parsedEl)
            use (
                &$terminals,
                &$identifiers,
                &$concatenations,
                &$alternations,
                &$optionals,
                &$groupings,
                &$repetitions,
                &$rules,
            ) {
                if ($parsedEl instanceof ParsedDefinition || $parsedEl instanceof ParsedDefinitionReference) {
                    $name = $parsedEl->definitionName;
                    switch ($name) {
                        case 'terminal':
                            $terminals[] = new TerminalString(null,$parsedEl->getParsedString());
                            break;
                    }
                }
             };
        */
    }

    public function visitParsedDefinitionElementPreOrder(ParsedDefinitionElement $element, \Closure $visitor): void
    {
        // Visit the tree of parsed definition elements in pre-order.
        $visitor($element);
        foreach ($element->getChildren() as $child) {
            if ($child instanceof ParsedDefinitionElement) {
                $this->visitParsedDefinitionElementPreOrder($child, $visitor);
            }
        }
    }

    public function visitParsedDefinitionElementPostOrder(ParsedDefinitionElement $element, \Closure $visitor): void
    {
        // Visit the tree of parsed definition elements in post-order.
        foreach ($element->getChildren() as $child) {
            if ($child instanceof ParsedDefinitionElement) {
                $this->visitParsedDefinitionElementPostOrder($child, $visitor);
            }
        }
        $visitor($element);
    }

    public function visitParsedDefinitionElementLevelOrder(ParsedDefinitionElement $element, \Closure $visitor): void
    {
        // Visit the tree of parsed definition elements in level-order.
        $queue = new \SplQueue();
        $queue->enqueue($element);
        while (!$queue->isEmpty()) {
            $current = $queue->dequeue();
            $visitor($current);
            foreach ($current->getChildren() as $child) {
                if ($child instanceof ParsedDefinitionElement) {
                    $queue->enqueue($child);
                }
            }
        }
    }

}

