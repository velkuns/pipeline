<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineArray
{
    /** @use PipelineTrait<array<string|int|float|bool|array<string|int|float|bool>>> */
    use PipelineTrait;

    /**
     * @param array<string|int|float|bool|array<string|int|float|bool>> $input
     */
    public function __construct(
        private readonly array $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory = $factory;
    }

    public function count(): PipelineInt
    {
        return $this->factory->int(\count($this->input));
    }

    public function countValues(): PipelineArray
    {
        return $this->factory->array(\array_count_values($this->input));
    }

    public function sum(): PipelineInt
    {
        return $this->factory->int((int) \array_sum($this->input));
    }

    public function product(): PipelineInt
    {
        return $this->factory->int((int) \array_product($this->input));
    }

    public function map(callable $callback): self
    {
        return $this->factory->new(\array_map($callback, $this->input));
    }

    public function max(): PipelineInt
    {
        return $this->factory->int((int) \max($this->input));
    }

    public function filter(callable $callback, int $mode = 0): self
    {
        return $this->factory->new(\array_filter($this->input, $callback, $mode));
    }

    public function slice(int $offset, ?int $length = null): PipelineArray
    {
        return $this->factory->array(\array_slice($this->input, $offset, $length));
    }

    public function reduce(
        callable $callable,
        $init
    ): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString {
        return $this->factory->new(\array_reduce($this->input, $callable, $init));
    }

    public function walk(callable $callable, $arg = null): PipelineArray
    {
        $input = $this->input;
        \array_walk($input, $callable, $arg);

        return $this->factory->array($input);
    }

    public function chunk(int $length): PipelineArray
    {
        return $this->factory->array(\array_chunk($this->input, $length));
    }

    public function unique(): PipelineArray
    {
        return $this->factory->array(\array_unique($this->input));
    }

    public function intersect(): PipelineArray
    {
        return $this->factory->array(\array_intersect(...$this->input));
    }

    public function current(): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString
    {
        return $this->factory->new(\current($this->input));
    }

    public function sort(): PipelineArray
    {
        $input = $this->input;
        \sort($input);

        return $this->factory->array($input);
    }

    public function rsort(): PipelineArray
    {
        $input = $this->input;
        \rsort($input);

        return $this->factory->array($input);
    }

    public function each(): PipelineEach
    {
        return new PipelineEach($this->input);
    }
}
