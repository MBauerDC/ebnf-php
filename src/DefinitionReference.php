<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class DefinitionReference implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        DefinitionElement | Definition | null $parent,
        public readonly string $definitionName
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::DefinitionReference;
        $this->children = [];
    }

    public function getDefinitionName(): string
    {
        return $this->definitionName;
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedDefinitionReference {
        //echo PHP_EOL . " - in try parse of definition reference {$this->definitionName} from input [$input]" . PHP_EOL;
        $existingDefinitionNames = [];
        foreach ($otherDefinitions as $definition) {
            $existingDefinitionNames[] = $definition->name;
            if ($definition->name === $this->definitionName) {

                $newDefinitionElementAncestors = [];
                $currDefinitionAncestors[] = $definition;
                echo PHP_EOL . " - parsing referenced definition [{$this->definitionName}]" . PHP_EOL;
                $parsed = $definition->tryParse($input, $newDefinitionElementAncestors, $currDefinitionAncestors, ...$otherDefinitions);
                if (null !== $parsed) {
                    echo PHP_EOL . " - successfully parsed referenced definition [{$this->definitionName}]" . PHP_EOL;
                    if ($this->definitionName === 'rule') {
                        echo " - parsed rule: " . $parsed->getParsedString() . PHP_EOL;
                        echo " - remaining input: $input" . PHP_EOL;
                    }
                    return new ParsedDefinitionReference($this->definitionName, $parsed);
                }
                echo PHP_EOL . " - failed to parse referenced definition [{$this->definitionName}]" . PHP_EOL;
                return null;
            }
        }
        throw new \Exception("Definition {$this->definitionName} not found among " . implode(', ', $existingDefinitionNames));
    }

    public function __toString(): string
    {
        return $this->definitionName;
    }

    public function withParent(DefinitionElement|Definition $parent): DefinitionReference
    {
        return new DefinitionReference($parent, $this->definitionName);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): DefinitionReference
    {
        return $this;
    }


}