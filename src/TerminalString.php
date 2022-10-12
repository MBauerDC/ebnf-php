<?php
declare(strict_types=1);

namespace dcAG\EBNF;

class TerminalString implements DefinitionElement
{
    use ElementRepresentation;

    public function __construct(
        public readonly string $value,
    ) {
        $this->elementType = DefinitionElementType::TerminalString;
        $this->children = [];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function tryParse(string &$input, Definition ...$otherDefinitions): ?ParsedDefinitionElement
    {
        //echo "Trying to parse terminal string {$this->value} from input [$input]" . PHP_EOL;
        if (str_starts_with($input, $this->value)) {
            //echo "Successfully parsed terminal string {$this->value}." . PHP_EOL;
            $input = substr($input, \mb_strlen($this->value));
            return new ParsedTerminalString($this->value);
        }
        //echo "Failed to parse terminal string {$this->value}." . PHP_EOL;
        return null;
    }

    public function __toString(): string
    {
        return '"' . $this->value . '"';
    }


}