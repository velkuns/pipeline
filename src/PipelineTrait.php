<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

/**
 * @template T
 */
trait PipelineTrait
{
    private readonly PipelineFactory $factory;

    public function restore(string $name): Pipeline|PipelineString|PipelineFloat|PipelineBool|PipelineArray|PipelineInt
    {
        return $this->factory->new(Store::get($name));
    }

    public function store(string $name): self
    {
        Store::set($name, $this->input);

        return $this;
    }

    /**
     * @param string $names
     */
    public function retrieve(...$names): PipelineArray
    {
        return $this->factory->array(Store::getMany(...$names));
    }

    public function get(): mixed
    {
        return $this->input;
    }

    public function debug(): self
    {
        \var_export($this->input);

        return $this;
    }
}
