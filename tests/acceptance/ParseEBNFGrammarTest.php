<?php
declare(strict_types=1);

namespace dcAG\tests\EBNF\acceptance;

use dcAG\EBNF\EBNFParser;
use PHPUnit\Framework\TestCase;

class ParseEBNFGrammarTest extends TestCase
{
    protected EBNFParser $ebnfParser;

    public function setUp(): void
    {
        parent::setUp();
        $this->ebnfParser = new EBNFParser();
    }

    public function testParseSimpleGrammar(): void
    {
        $simpleGrammarText = \file_get_contents(__DIR__ . "/../fixtures/exampleGrammar.ebnf");
        echo "simpleGrammarText: {$simpleGrammarText}" . PHP_EOL;
        $this->assertNotNull('a');
        $result = $this->ebnfParser->parseEBNFGrammer($simpleGrammarText);
        $this->assertNotNull($result);
        var_export($result);
    }
}
