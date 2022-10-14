<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class TerminalString implements DefinitionElement
{
    use ElementRepresentation;

    public readonly string $value;

    protected bool $isNewLine;

    protected function checkForMatch(string $input, string &$match): bool
    {
        if ($this->isNewLine) {
            $matches = [];
            $doesMatch = \preg_match('/^\R/', $input, $matches);
            $match = $doesMatch ? $matches[0] : '';
            return $doesMatch !== false && $doesMatch !== 0;
        }
        $matches = \str_starts_with($input, $this->value);
        $match = $matches ? $this->value : '';
        return $matches;
    }

    public function __construct(
        DefinitionElement | Definition | null $parent,
        string $value,
    ) {
        $this->parent = $parent;
        $this->elementType = DefinitionElementType::TerminalString;
        $this->children = [];
        $this->value = $value;
        $this->isNewLine = \in_array($this->value, ['\r', '\n', '\r\n', '\n\r']);

    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function tryParse(
        string &$input,
        array $currDefinitionElementAncestors,
        array $currDefinitionAncestors,
        Definition ...$otherDefinitions
    ): ?ParsedTerminalString {
        //echo "Trying to parse terminal string {$this->value} from input [$input]" . PHP_EOL;
        $matched = '';
        if ($this->checkForMatch($input, $matched)) {
            //echo "Successfully parsed terminal string {$this->value}." . PHP_EOL;
            if ($this->isNewLine) {
                $input = \preg_replace('/\R/', '' , $input, 1);
            } else {
                $input = substr($input, \mb_strlen($this->value));
            }
            return new ParsedTerminalString($this->parent, $matched);
        }
        //echo "Failed to parse terminal string {$this->value}." . PHP_EOL;
        return null;
    }

    public function __toString(): string
    {
        return '"' . $this->value . '"';
    }

    public function withParent(DefinitionElement|Definition $parent): TerminalString
    {
        return new self($parent, $this->value);
    }

    public function withReplacedInnerElement(DefinitionElement $oldElement, DefinitionElement $newElement): TerminalString
    {
        return $this;
    }

}