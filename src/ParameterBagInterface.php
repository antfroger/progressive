<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Exception\ParameterNotFoundException;

interface ParameterBagInterface
{
    /**
     * Adds parameters.
     *
     * @param array<string, mixed> $parameters An array of parameters
     */
    public function add(array $parameters): void;

    /**
     * Sets a parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     */
    public function set(string $name, mixed $value): void;

    /**
     * Gets a parameter.
     *
     * @return string The parameter name
     * @return mixed  The parameter value
     *
     * @throws ParameterNotFoundException if the parameter is not defined
     */
    public function get(string $name): mixed;

    /**
     * Returns whether a parameter is defined.
     *
     * @param string $name The parameter name
     *
     * @return bool true if the parameter name is defined, false otherwise
     */
    public function has(string $name): bool;
}
