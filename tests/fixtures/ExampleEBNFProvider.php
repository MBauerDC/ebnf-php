<?php
declare(strict_types=1);

namespace dcAG\tests\EBNF\fixtures;

class ExampleEBNFProvider
{
    public static function getExampleGrammar(): \dcAG\EBNF\EBNFGrammar
    {
        $stra = new \dcAG\EBNF\TerminalString('a');
        $straDef = new \dcAG\EBNF\Definition('stra', $stra);
        $strb = new \dcAG\EBNF\TerminalString('b');
        $strbDef = new \dcAG\EBNF\Definition('strb', $strb);
        $strc = new \dcAG\EBNF\TerminalString('c');
        $strcDef = new \dcAG\EBNF\Definition('strc', $strc);
        $strd = new \dcAG\EBNF\TerminalString('d');
        $strdDef = new \dcAG\EBNF\Definition('strd', $strd);
        $concat = new \dcAG\EBNF\Concatenation($stra, $strb);
        $concatDef = new \dcAG\EBNF\Definition('concat', $concat);
        $alter = new \dcAG\EBNF\Alternation($strc, $strd);
        $alterDef = new \dcAG\EBNF\Definition('alter', $alter);
        $group = new \dcAG\EBNF\Grouping(new \dcAG\EBNF\Concatenation($concat, $alter));
        $groupDef = new \dcAG\EBNF\Definition('group', $group);
        $optRepOfC = new \dcAG\EBNF\Optional(new \dcAG\EBNF\Repetition($strc));
        $optRepOfCDef = new \dcAG\EBNF\Definition('optRepOfC', $optRepOfC);
        $simpleRef = new \dcAG\EBNF\DefinitionReference('optRepOfC');
        $bcword = new \dcAG\EBNF\Concatenation($strb, $strc, $simpleRef);
        $bcwordDef = new \dcAG\EBNF\Definition('bcword', $bcword);
        $optWhitespaceRepetition = new \dcAG\EBNF\Optional(
            new \dcAG\EBNF\Repetition(
                new \dcAG\EBNF\Alternation(
                    new \dcAG\EBNF\TerminalString(' '),
                    new \dcAG\EBNF\TerminalString('\r'),
                    new \dcAG\EBNF\TerminalString('\n'),
                    new \dcAG\EBNF\TerminalString('\t')
                )
            )
        );
        $optWhitespaceRepetitionDef = new \dcAG\EBNF\Definition('optWhitespaceRepetition', $optWhitespaceRepetition);
        $optWhitespaceRepetitionRef = new \dcAG\EBNF\DefinitionReference('optWhitespaceRepetition');
        $bcWordRef = new \dcAG\EBNF\DefinitionReference('bcword');
        $concatRef = new \dcAG\EBNF\DefinitionReference('concat');
        $alterRef = new \dcAG\EBNF\DefinitionReference('alter');
        $bcWordWhiteSpace = new \dcAG\EBNF\Concatenation($optWhitespaceRepetitionRef, $bcWordRef, $optWhitespaceRepetitionRef);
        $bcWordWhiteSpaceDef = new \dcAG\EBNF\Definition('bcWordWhiteSpace', $bcWordWhiteSpace);
        $bcWordWhiteSpaceRef = new \dcAG\EBNF\DefinitionReference('bcWordWhiteSpace');
        $concatWhiteSpace = new \dcAG\EBNF\Concatenation($optWhitespaceRepetitionRef, $concatRef, $optWhitespaceRepetitionRef);
        $concatWhiteSpaceDef = new \dcAG\EBNF\Definition('concatWhiteSpace', $concatWhiteSpace);
        $concatWhiteSpaceRef = new \dcAG\EBNF\DefinitionReference('concatWhiteSpace');
        $alterWhiteSpace = new \dcAG\EBNF\Concatenation($optWhitespaceRepetitionRef, $alterRef, $optWhitespaceRepetitionRef);
        $alterWhiteSpaceDef = new \dcAG\EBNF\Definition('alterWhiteSpace', $alterWhiteSpace);
        $alterWhiteSpaceRef = new \dcAG\EBNF\DefinitionReference('alterWhiteSpace');

        $document =
            new \dcAG\EBNF\Repetition(
                new \dcAG\EBNF\Alternation(
                    $bcWordWhiteSpaceRef,
                    $concatWhiteSpaceRef,
                    $alterWhiteSpaceRef
                )
            );
        $documentDef = new \dcAG\EBNF\Definition('document', $document);

        $definitionList = new \dcAG\EBNF\DefinitionList(
            $optWhitespaceRepetitionDef,
            $straDef,
            $strbDef,
            $strcDef,
            $strdDef,
            $concatDef,
            $alterDef,
            $groupDef,
            $optRepOfCDef,
            $bcwordDef,
            $concatWhiteSpaceDef,
            $alterWhiteSpaceDef,
            $bcWordWhiteSpaceDef,
            $documentDef
        );
        $grammar = new \dcAG\EBNF\EBNFGrammar($definitionList, 'document');
        return $grammar;
    }

    public static function getValidExampleInput(): string
    {
        return
            <<<'EOT'
       ab c d bc d c ab bcccc d bcc
       EOT;
    }
}