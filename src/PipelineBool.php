<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineBool
{
    /** @use PipelineTrait<bool> */
    use PipelineTrait;

    public function __construct(
        private readonly bool $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory = $factory;
    }

    public function negate(): PipelineBool
    {
        return $this->factory->bool(!$this->input);
    }
}
