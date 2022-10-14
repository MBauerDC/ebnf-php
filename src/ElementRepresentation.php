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
    /** @var DefinitionElement[] */
    protected array $children;
    protected null|DefinitionElement|Definition $parent = null;

    public function getParent(): DefinitionElement|Definition|null
    {
        return $this->parent;
    }


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

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitPreorder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $visitor($this, $this->parent);
        foreach ($this->children as $child) {
            if (!$parsedElementsOnly || $child instanceof ParsedDefinitionElement) {
                $child->visitPreorder($visitor);
            }
        }
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement|Definition $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitPostorder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        foreach ($this->children as $child) {
            if (!$parsedElementsOnly || $child instanceof ParsedDefinitionElement) {
                $child->visitPostorder($visitor);
            }
        }
        $visitor($this, $this->parent);
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement|Definition $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitLevelOrder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        $queue = new \SplQueue();
        $queue->enqueue($this);
        while (!$queue->isEmpty()) {
            $current = $queue->dequeue();
            if (!$parsedElementsOnly || $current instanceof ParsedDefinitionElement) {
                $visitor($current, $current->getParent());
            }
            foreach ($current->getChildren() as $child) {
                if (!$parsedElementsOnly || $child instanceof ParsedDefinitionElement) {
                    $queue->enqueue($child);
                }
            }
        }
    }

    /**
     * @param callable(DefinitionElement $currNode, DefinitionElement $currNodeParent): void $visitor
     * @param bool $parsedElementsOnly
     * @return void
     */
    public function visitInverseLevelOrder(callable $visitor, bool $parsedElementsOnly = false): void
    {
        //Collect all nodes in a level order, then iterate in reverse order and call the visitor on each node
        $nodes = [];
        $this->visitLevelOrder(
            static function(DefinitionElement $currNode, DefinitionElement $currNodeParent) use (&$nodes) { $nodes[] = $currNode; },
            $parsedElementsOnly
        );
        $nodes = array_reverse($nodes);
        foreach ($nodes as $node) {
            if (!$parsedElementsOnly || $node instanceof ParsedDefinitionElement) {
                $visitor($node, $node->getParent());
            }
        }

    }

    public function setParent(DefinitionElement|Definition $parent): void
    {
        $this->parent = $parent;
    }

    protected function tryParseChild(
        DefinitionElement $child,
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedDefinitionElement {

        //Check with currDefinitionAncestors if curr child is a recursive reference
        $isRecursiveReference = false;
        if ($child instanceof DefinitionReference) {
            foreach ($currDefinitionAncestors as $ancestor) {
                if ($ancestor->name === $child->definitionName) {
                    $isRecursiveReference = true;
                    break;
                }
            }
        }
        //If child is recursive reference, find closest ancestor (including this) which is an alternation.
        //The ancestor (or self) directly after this alternation will be the current alternation branch.
        //Create a new definition for the recursive definition which excludes the current alternation branch,
        //then create a new array of definitions where the original definition is replaced with the reduced one
        //to pass into the recursive definition reference's tryParse-method.

        $newOtherDefinitions = $otherDefinitions;
        if ($isRecursiveReference) {
            /** @var  $child DefinitionReference */

            $currentAlternation = null;
            $reducedCurrentAlternation = null;
            $reversedElementAncestors = \array_reverse($currDefinitionElementAncestors);
            $previousAncestor = null;
            foreach ($reversedElementAncestors as $ancestor) {
                if ($ancestor->getElementType() === DefinitionElementType::Alternation) {
                    $currentAlternation = $ancestor;
                    $currentAlternationBranch = $previousAncestor;
                    $reducedCurrentAlternation = $ancestor->withoutBranch($currentAlternationBranch);
                    break;
                }
                $previousAncestor = $ancestor;
            }
            if (null === $reducedCurrentAlternation) {
                throw new \ErrorException("Could not find closest alternation for recursive reference.");
            }
            $definition = $otherDefinitions[$child->definitionName];
            $clonedDefinition = clone $definition;
            $replacingVisitor =
                static function (
                    DefinitionElement $currEl,
                    DefinitionElement|Definition $currParent
                ) use ($currentAlternation, $reducedCurrentAlternation): void {
                    if ((string)$currEl === (string)$currentAlternation) {
                        $currParent->replaceInnerElement($currEl, $reducedCurrentAlternation);
                        $i = 1+1;
                    }
                };
            $clonedDefinition->getRightHandSide()->visitLevelOrder($replacingVisitor);
            $newOtherDefinitions[$clonedDefinition->name] = $clonedDefinition;
        }

        $parsed = $child->tryParse($input, $currDefinitionElementAncestors, $currDefinitionAncestors, ...$newOtherDefinitions);
        return $parsed;
    }

    public function replaceInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): void
    {
        $newChildren = [];
        foreach ($this->children as $child) {
            if ((string)$child === (string)$oldElement) {
                $newChildren[] = $newElement;
            } else {
                $newChildren[] = $child;
            }
        }
        $this->children = $newChildren;
    }

    public function __clone()
    {
        foreach ($this->children as $key => $child) {
            $clone = clone $child;
            $this->children[$key] = $clone->withParent($this);
        }
    }

}