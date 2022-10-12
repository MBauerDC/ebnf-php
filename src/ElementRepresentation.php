<?php
declare(strict_types=1);

namespace dcAG\EBNF;

trait ElementRepresentation
{
    /**
     * @var string[]|null
     */
    private ?array $containedReferences = null;
    public readonly DefinitionElementType $elementType;
    /** @readonly @var DefinitionElement[] */
    public readonly array $children;

    public function getElementType(): DefinitionElementType
    {
        return $this->elementType;
    }

    /** @return DefinitionElement[] */
    public function getChildren(): array
    {
        return $this->children;
    }

    /** @return string[] */
    public function getContainedReferences(): array
    {
        $this->ensureReferencesGathered();
        return $this->containedReferences;
    }

    private function ensureReferencesGathered(): void
    {
        if ($this->containedReferences === null) {
            if ($this->elementType === DefinitionElementType::DefinitionReference) {
                $this->containedReferences = [];
                $this->containedReferences[$this->definitionName] = $this->definitionName;
                return;
            }
            $containedReferences = [];
            foreach ($this->children as $child) {
                $containedReferences += $child->getContainedReferences();
            }
            $this->containedReferences = $containedReferences;
        }
    }

    public function hasDefinitionReference(): bool
    {
        $this->ensureReferencesGathered();
        static $hasReferences;
        if (null === $hasReferences) {
            $hasReferences = count($this->containedReferences) > 0;
        }
        return $hasReferences;
    }

    public function hasDefinitionReferenceTo(string $name): bool
    {
        $this->ensureReferencesGathered();
        return array_key_exists($name, $this->containedReferences);
    }

}