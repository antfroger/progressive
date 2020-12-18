<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Exception\ParameterNotFoundException;

interface ParameterBagInterface
{
    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters
     *
     * @throws \LogicException if the parameter can not be added
     */
    public function add(array $parameters): void;

    /**
     * Sets a parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @throws \LogicException if the parameter can not be set
     */
    public function set(string $name, $value): void;

    /**
     * Gets a parameter.
     *
     * @throws ParameterNotFoundException if the parameter is not defined
     *
     * @return string The parameter name
     * @return mixed  The parameter value
     */
    public function get(string $name);

    /**
     * Returns whether a parameter is defined.
     *
     * @param string $name The parameter name
     *
     * @return bool true if the parameter name is defined, false otherwise
     */
    public function has(string $name): bool;
}
