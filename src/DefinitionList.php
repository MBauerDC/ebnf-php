<?php
declare(strict_types=1);

namespace dcAG\EBNF;


use function dcAG\EBNF\validation\definitionTerminates;

class DefinitionList implements \ArrayAccess
{
    protected array $definitions = [];
    protected array $containedReferences = [];
    protected array $topLevelDefinitions = [];
    protected Definition $documentDefinition;
    protected array $definitionsWithoutDocumentDefinition = [];

    /**
     * NOTE: Definitions must be passed in a constructive order,
     * meaning that (non-recursive) references may only occur when
     * the referenced definition is before the referencing definition in the parameter list.
     * @param Definition ...$definitions
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Definition ...$definitions
    ) {
        foreach ($definitions as $definition) {
            if (!Validation::definitionTerminates($definition, ...$this->definitions)) {
                throw new \InvalidArgumentException("Definition {$definition->name} does not terminate.");
            }
            $this->containedReferences += $definition->rightHandSide->getContainedReferences();
            $this->definitions[$definition->name] = $definition;
        }
        foreach ($this->definitions as $definitionName => $definition) {
            if (!\array_key_exists($definitionName, $this->containedReferences)) {
                $this->topLevelDefinitions[$definitionName] = $definition;
            }
        }
        $topLevelDefinitionsCount = \count($this->topLevelDefinitions);
        if (0 === $topLevelDefinitionsCount) {
            throw new \InvalidArgumentException("No top-level definitions found.");
        }
        if (1 === $topLevelDefinitionsCount) {
            $this->documentDefinition = $this->topLevelDefinitions[array_key_first($this->topLevelDefinitions)];
            $definitionsWithoutDocumentDefinition  = $this->definitions;
            unset($definitionsWithoutDocumentDefinition[$this->documentDefinition->name]);
            $this->definitionsWithoutDocumentDefinition = $definitionsWithoutDocumentDefinition;
        } else {
            $this->documentDefinition = new Definition(
                '_document',
                new Alternation(
                    ...(
                        \array_map(
                            static fn(Definition $definition) => new DefinitionReference($definition->name),
                            $this->topLevelDefinitions
                        )
                    )
                )
            );
            $this->definitionsWithoutDocumentDefinition = $this->definitions;
        }
    }

    public function tryParseDocument(string $input): ?ParsedDefinitionElement
    {
        $parsedElement = $this->documentDefinition->tryParse($input, ...$this->definitionsWithoutDocumentDefinition);
        return $parsedElement;
    }

    public function getDocumentDefinition(): Definition
    {
        return $this->documentDefinition;
    }

    public function getTopLevelDefinitions(): array
    {
        return $this->topLevelDefinitions;
    }

    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->definitions);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->definitions[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException("Cannot set value on DefinitionList.");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException("Cannot unset value on DefinitionList.");
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

}