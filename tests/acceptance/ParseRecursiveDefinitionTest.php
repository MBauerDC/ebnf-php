<?php
declare(strict_types=1);

namespace dcAG\tests\EBNF\acceptance;

use dcAG\EBNF\Alternation;
use dcAG\EBNF\Concatenation;
use dcAG\EBNF\Definition;
use dcAG\EBNF\DefinitionList;
use dcAG\EBNF\DefinitionReference;
use dcAG\EBNF\EBNFGrammar;
use dcAG\EBNF\TerminalString;
use PHPUnit\Framework\TestCase;

class ParseRecursiveDefinitionTest extends TestCase
{
    protected EBNFGrammar $ebnfGrammar;

    public function setUp(): void
    {
        parent::setUp();

        $mpDef = new Definition(
            'mp',
            new Alternation(
                new Concatenation(
                    new TerminalString('('),
                    new DefinitionReference('mp'),
                    new TerminalString(')'),
                    new DefinitionReference('mp'),
                ),
                new TerminalString(''),
            )
        );
        $mpDefList = new DefinitionList($mpDef);
        $this->ebnfGrammar = new EBNFGrammar($mpDefList, 'mp');
    }

    public function testConstructionOfRecursiveGrammar(): void
    {
        $this->assertNotNull($this->ebnfGrammar);
    }

    public function testParsingWithRecursiveGrammar(): void
    {
        $result = $this->ebnfGrammar->tryParse('()()(())');
        $this->assertNotNull($result);
    }
}
