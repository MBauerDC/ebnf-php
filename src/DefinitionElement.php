<?php
declare(strict_types=1);

namespace dcAG\EBNF;

interface DefinitionElement
{
    public function getElementType(): DefinitionElementType;

    /** @return DefinitionElement[] */
    public function getChildren(): array;

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedDefinitionElement;

    public function __toString(): string;

    public function hasDefinitionReference(): bool;

    public function hasDefinitionReferenceTo(string $name): bool;

    /** @return string[] */
    public function getContainedReferences(): array;

    public function getParent(): DefinitionElement|Definition|null;

    public function withParent(DefinitionElement|Definition $parent): DefinitionElement;

    public function setParent(DefinitionElement|Definition $parent): void;

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): DefinitionElement;

    public function replaceInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): void;
    //public function getRegexPatternPart(string $escapeCharacter, ): string;
}