<?php
declare(strict_types=1);
echo "TEST";
include(__DIR__ . "/../../vendor/autoload.php");
$ebnfParser = new \dcAG\EBNF\EBNFParser();
$startTime = new DateTimeImmutable();
$endTime = $startTime->add(new DateInterval('PT10S'))->getTimestamp();
$simpleGrammarText = \file_get_contents(__DIR__ . "/../fixtures/exampleGrammar.ebnf");
echo "simpleGrammarText: {$simpleGrammarText}" . PHP_EOL;
$result = $ebnfParser->parseEBNFGrammer($simpleGrammarText);
$arr = [
    'character' => [],
    'digit' => [],
    'symbol' => [],
    'terminal' => [],
];
if (null !== $result) {
    echo PHP_EOL . "SUCCESSFULLY PARSED EBNF GRAMMAR" . PHP_EOL;
    /** @var \dcAG\EBNF\ParsedDefinition $result */
    $result->visitPreorder(function (\dcAG\EBNF\DefinitionElement $currNode, \dcAG\EBNF\DefinitionElement|\dcAG\EBNF\Definition|null $currNodeParent) {
        /** @var \dcAG\EBNF\ParsedDefinitionElement $currNode */
        if ($currNode instanceof \dcAG\EBNF\ParsedDefinitionReference) {
            echo "parsed definition [{$currNode->getDefinitionName()}]: {$currNode->getParsedString()}" . PHP_EOL;
        }
    }, parsedElementsOnly: true);
}

