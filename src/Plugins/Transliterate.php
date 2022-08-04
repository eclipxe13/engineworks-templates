<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugin;

class Transliterate implements Plugin
{
    /**
     * @return array{tr: string}
     */
    public function getCallablesTable(): array
    {
        return [
            'tr' => 'transliterate',
        ];
    }

    /** @var callable */
    private $defaultEncoder;

    /**
     * Transliterate constructor.
     *
     * @param callable|null $defaultEncoder If the default encoder is null then a null encoder is used
     */
    public function __construct(callable $defaultEncoder = null)
    {
        $this->setDefaultEncoder($defaultEncoder ? : [static::class, 'nullEncoder']);
    }

    /**
     * Uses a message and replace its keywords with the provided in $arguments
     *
     * The message can be an object, it will be translated to a string using valueToSting
     * The message should include the keywords to replace in curly braces, like:
     * Welcome to {city}, enjoy!
     *
     * Every argument that is included into the message is evaluated to a string
     * (using valueToString) and then is passed to the encoder.
     *
     * If the encoder is null then uses the default encoder.
     *
     * @param string $message String with elements using '{key}' template
     * @param array<string, mixed> $arguments Key/value array with the replacements
     * @param callable|null $encoder function to use for encode
     */
    public function transliterate(string $message, array $arguments = [], callable $encoder = null): string
    {
        // put message as string
        $message = $this->valueToString($message);
        // if there are no arguments then exit
        if ('' === $message || 0 == count($arguments)) {
            return $message;
        }
        // create encoded arguments (only included if they exist in matches)
        $encodedArguments = [];
        $encoder = ($encoder) ? : $this->getDefaultEncoder();
        foreach ($arguments as $key => $value) {
            $transKey = '{' . $key . '}';
            if (false === strpos($message, $transKey)) {
                continue;
            }
            $encodedArguments[$transKey] = call_user_func($encoder, $this->valueToString($value));
        }
        // if there are any encoded arguments then exit
        if (! count($encodedArguments)) {
            return $message;
        }
        // translit using strtr php function
        return strtr($message, $encodedArguments);
    }

    /**
     * Retrieve the keys inside the curly braces
     * If the keys are duplicated they are removed, therefore,
     * if you need consecutive keys then use array_values function
     *
     * @return string[]
     */
    public function getCurlyBracesKeys(string $message): array
    {
        if ('' === trim($message)) {
            return [];
        }
        // preg_match_all return 0 if not found, false on error
        if (! preg_match_all('/({(?<={)[^{}\r\n]+?(?=})})/U', $message, $matches)) {
            return [];
        }
        return array_unique($matches[0]);
    }

    /**
     * Convert a value to string, if the value is:
     * scalar or object with method __toString() then the string cast
     * array or traversable then iterates the object converting every member and concat the result
     * else empty string
     *
     * @param mixed $value
     */
    public function valueToString($value): string
    {
        if (is_null($value) || is_scalar($value) || (is_object($value) && is_callable([$value, '__toString']))) {
            return (string) $value;
        }
        if (is_iterable($value)) {
            $buffer = [];
            foreach ($value as $innerValue) {
                $buffer[] = $this->valueToString($innerValue);
            }
            return implode('', $buffer);
        }
        return '';
    }

    public function getDefaultEncoder(): callable
    {
        return $this->defaultEncoder;
    }

    /**
     * @param callable(string):string $defaultEncoder
     */
    public function setDefaultEncoder(callable $defaultEncoder): void
    {
        $this->defaultEncoder = $defaultEncoder;
    }

    /**
     * Function to setup a null encoder
     */
    public static function nullEncoder(string $string): string
    {
        return $string;
    }
}
