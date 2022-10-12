<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class Validation
{

    public static function definitionTerminates(Definition $definition, Definition...$previousDefinitions): bool
    {
        return self::definitionElementTerminates($definition->rightHandSide, $definition->name, [], ...$previousDefinitions);
    }

    public static function definitionElementTerminates(
        DefinitionElement $definitionElement,
        ?string           $inDefinitionName,
        array             $recursionAncestorElementTypes = [],
        Definition        ...$previousDefinitions
    ): bool
    {
        /** @var array<string, Definition> $indexedDefinitions */
        $indexedDefinitions = array_combine(
            array_map(
                static fn(Definition $definition) => $definition->name,
                $previousDefinitions
            ),
            $previousDefinitions
        );

        $rhsType = $definitionElement->getElementType();
        switch ($rhsType) {
            case DefinitionElementType::TerminalString:
                print('TerminalString terminates' . PHP_EOL);
                return true;
            case DefinitionElementType::Concatenation:
                // If the concatenation contains a recursive reference, it will be considered terminating,
                // and we have to check whether the concatenation also contains something that is not a recursive reference.
                $containsRecursiveReference = false;
                $containsSomethingNotARecursiveReference = false;
                $reducedBool = \array_reduce(
                    $definitionElement->getChildren(),
                    static function (bool $carry, DefinitionElement $child)
                    use (
                        $inDefinitionName,
                        $recursionAncestorElementTypes,
                        $previousDefinitions,
                        &$containsRecursiveReference,
                        &$containsSomethingNotARecursiveReference
                    ): bool {
                        $returnValue =
                            $carry &&
                            self::definitionElementTerminates(
                                $child,
                                $inDefinitionName,
                                [...$recursionAncestorElementTypes, DefinitionElementType::Concatenation->value],
                                ...$previousDefinitions
                            );
                        if ($child instanceof DefinitionReference && $child->definitionName === $inDefinitionName) {
                            $containsRecursiveReference = true;
                        } else {
                            $containsSomethingNotARecursiveReference = true;
                        }
                        return $returnValue;
                    },
                    true
                );
                $terminates = $containsRecursiveReference ?
                    $containsSomethingNotARecursiveReference && $reducedBool :
                    $reducedBool;
                print("Concatenation terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            case DefinitionElementType::Alternation:
                // An alternation - like a concatenation - only terminates of ALL of its children terminate
                $terminates = \array_reduce(
                    $definitionElement->getChildren(),
                    static fn(bool $carry, DefinitionElement $child) => $carry &&
                        self::definitionElementTerminates(
                            $child,
                            $inDefinitionName,
                            [...$recursionAncestorElementTypes, DefinitionElementType::Alternation->value],
                            ...$previousDefinitions
                        ),
                    true
                );
                print("Alternation terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            case DefinitionElementType::DefinitionReference:
                /** @var DefinitionReference $definitionElement */
                $referencedDefinitionName = $definitionElement->definitionName;
                // Recursion is allowed only if no branch of the definition contains an element consisting only of references to the same definition.
                // Thus, for a recursive definition to terminate, it must have branches that do not contain any references to the same definition.
                // Practically, we ensure this by allowing recursive reference only when:
                //  - a concatenation is in the ancestor chain AND
                //  - the recursion does not occur directly as a branch of an alternation
                // And by checking whether a concatenation that contains a recursive reference has other different (terminating) elements.
                if ($referencedDefinitionName === $inDefinitionName) {
                    $lastAncestor = $recursionAncestorElementTypes[array_key_last($recursionAncestorElementTypes)] ?? null;
                    $terminates =
                        \in_array(DefinitionElementType::Concatenation->value, $recursionAncestorElementTypes, true) &&
                        $lastAncestor !== DefinitionElementType::Alternation->value;
                    print("Reference terminates? [$terminates]" . PHP_EOL);
                    return $terminates;
                }

                $referencedDefinition = $indexedDefinitions[$referencedDefinitionName] ?? null;
                if (null === $referencedDefinition) {
                    print("Reference terminates? []" . PHP_EOL);
                    return false;
                }
                $filteredPreviousDefinitions = \array_filter(
                    $previousDefinitions,
                    static fn(Definition $previousDefinition) => $previousDefinition->name !== $referencedDefinitionName
                );
                $terminates = self::definitionElementTerminates(
                    $referencedDefinition->rightHandSide,
                    $referencedDefinition->name,
                    $recursionAncestorElementTypes,
                    ...$filteredPreviousDefinitions
                );
                print("Reference terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            case DefinitionElementType::Grouping:
                $terminates = self::definitionElementTerminates(
                    $definitionElement->getChildren()[0],
                    $inDefinitionName,
                    [...$recursionAncestorElementTypes, DefinitionElementType::Grouping->value],
                    ...$previousDefinitions
                );
                print("Grouping terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            case DefinitionElementType::Optional:
                $terminates = self::definitionElementTerminates(
                    $definitionElement->getChildren()[0],
                    $inDefinitionName,
                    [...$recursionAncestorElementTypes, DefinitionElementType::Optional->value],
                    ...$previousDefinitions
                );
                print("Optional terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            case DefinitionElementType::Repetition:
                $terminates = self::definitionElementTerminates(
                    $definitionElement->getChildren()[0],
                    $inDefinitionName,
                    [...$recursionAncestorElementTypes, DefinitionElementType::Repetition->value],
                    ...$previousDefinitions
                );
                print("Repetition terminates? [$terminates]" . PHP_EOL);
                return $terminates;
            default:
                return false;

        }
    }
}