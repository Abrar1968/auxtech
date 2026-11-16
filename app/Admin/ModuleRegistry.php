<?php

namespace App\Admin;

use InvalidArgumentException;

class ModuleRegistry
{
    /** @var array<string, Module> */
    private array $modules = [];

    /** @param list<class-string<Module>> $classes */
    public function __construct(array $classes)
    {
        foreach ($classes as $class) {
            $module = new $class;
            $this->modules[$module->key()] = $module;
        }
    }

    public function get(string $key): Module
    {
        return $this->modules[$key] ?? throw new InvalidArgumentException("Unknown admin module [{$key}].");
    }

    public function has(string $key): bool
    {
        return isset($this->modules[$key]);
    }

    /** @return array<string, Module> */
    public function all(): array
    {
        return $this->modules;
    }

    /** @return list<string> */
    public function keys(): array
    {
        return array_keys($this->modules);
    }
}
