<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineFloat
{
    /** @use PipelineTrait<float> */
    use PipelineTrait;

    public function __construct(
        private readonly float $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory = $factory;
    }

    public function negate(): PipelineFloat
    {
        return $this->factory->float(-$this->input);
    }

    public function add(float $value): PipelineFloat
    {
        return $this->factory->float($this->input + $value);
    }

    public function sub(float $value): PipelineFloat
    {
        return $this->factory->float($this->input - $value);
    }

    public function product(float $value): PipelineFloat
    {
        return $this->factory->float($this->input * $value);
    }
}
