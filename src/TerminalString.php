<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class TerminalString implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        public readonly ?string $value = null,
    ) {
        $this->elementType = DefinitionElementType::TerminalString;
        $this->children = [];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function tryParse(string $input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        if ($this->value === null) {
            return null;
        }
        if (str_starts_with($input, $this->value)) {
            return new ParsedTerminalString($this->value);
        }
        return null;
    }

    public function __toString(): string
    {
        return '"' . $this->value . '"';
    }


}