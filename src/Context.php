<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Exception\ParameterNotFoundException;

class Context implements ParameterBagInterface
{
    /** @var array<string, mixed> */
    private $parameters = [];

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->add($parameters);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function add(array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function get(string $name): mixed
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException(
                sprintf('Parameter "%s" does not exist in Context', $name)
            );
        }

        return $this->parameters[$name];
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }
}
