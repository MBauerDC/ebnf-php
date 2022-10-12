<?php
declare(strict_types=1);

namespace dcAG\tests\EBNF\acceptance;

use dcAG\EBNF\EBNFGrammar;
use dcAG\tests\EBNF\fixtures\ExampleEBNFProvider;
use PHPUnit\Framework\TestCase;

class ParsesValidTextSuccessfullyTest extends TestCase
{
    protected string $validExampleInput;
    protected EBNFGrammar $exampleGrammar;

    public function setUp(): void
    {
        parent::setUp();
        $this->validExampleInput = ExampleEBNFProvider::getValidExampleInput();
        $this->exampleGrammar = ExampleEBNFProvider::getExampleGrammar();
        echo PHP_EOL . PHP_EOL . PHP_EOL . "Grammar: " . PHP_EOL . PHP_EOL . $this->exampleGrammar->__toString();
    }

    public function testParsesValidTextWithoutError(): void
    {
        $parsedResult = $this->exampleGrammar->tryParse($this->validExampleInput);
        $this->assertNotNull($parsedResult);
    }

}
