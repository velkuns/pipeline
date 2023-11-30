<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineInt
{
    /** @use PipelineTrait<int> */
    use PipelineTrait;

    public function __construct(
        private readonly int $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory = $factory;
    }

    public function negate(): PipelineInt
    {
        return $this->factory->int(-$this->input);
    }

    public function add(int $value): PipelineInt
    {
        return $this->factory->int($this->input + $value);
    }

    public function sub(int $value): PipelineInt
    {
        return $this->factory->int($this->input - $value);
    }

    public function product(int $value): PipelineInt
    {
        return $this->factory->int($this->input * $value);
    }
}
