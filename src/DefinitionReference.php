<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class DefinitionReference implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        public readonly string $definitionName
    ) {
        $this->elementType = DefinitionElementType::DefinitionReference;
        $this->children = [];
    }

    public function getDefinitionName(): string
    {
        return $this->definitionName;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        $existingDefinitionNames = [];
        foreach ($otherDefinitions as $definition) {
            $existingDefinitionNames[] = $definition->name;
            if ($definition->name === $this->definitionName) {
                return $definition->tryParse($input, ...$otherDefinitions);
            }
        }
        throw new \Exception("Definition {$this->definitionName} not found among " . print_r($existingDefinitionNames, true));
    }

    public function __toString(): string
    {
        return $this->definitionName;
    }


}