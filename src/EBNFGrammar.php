<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class EBNFGrammar
{
    protected array $definitionsWithoutDocumentDefinition = [];
    public function __construct(
        public readonly DefinitionList $definitionList,
        public readonly string $documentDefinitionName
    ) {
        if (!\array_key_exists($this->documentDefinitionName, $this->definitionList->getTopLevelDefinitions())) {
            throw new \InvalidArgumentException("Document definition {$this->documentDefinitionName} does not exist.");
        }
        $definitionsWithoutDocumentDefinition = $this->definitionList->getDefinitions();
        unset($definitionsWithoutDocumentDefinition[$this->documentDefinitionName]);
        $this->definitionsWithoutDocumentDefinition = $definitionsWithoutDocumentDefinition;
    }

    public function tryParse(string $input): ?ParsedDefinitionElement
    {
        return $this->definitionList[$this->documentDefinitionName]->tryParse($input, ...$this->definitionsWithoutDocumentDefinition);
    }

    public function __toString(): string
    {
        return implode("\n", $this->definitionList->getDefinitions());
    }
}