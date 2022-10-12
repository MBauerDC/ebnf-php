<?php
declare(strict_types=1);

namespace dcAG\EBNF;

interface DefinitionElement
{
    public function getElementType(): DefinitionElementType;
    /** @return DefinitionElement[] */
    public function getChildren(): array;
    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedDefinitionElement;
    public function __toString(): string;
    public function hasDefinitionReference(): bool;
    public function hasDefinitionReferenceTo(string $name): bool;
    /** @return string[] */
    public function getContainedReferences(): array;
    //public function getRegexPatternPart(string $escapeCharacter, ): string;
}