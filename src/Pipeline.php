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
 * Class Pipe
 *
 * @author Romain Cottard
 */
class Pipeline
{
    use PipelineTrait;

    protected array|string|int|float|bool $input;

    public function __construct(bool $flushStore = true, PipelineFactory $factory = new PipelineFactory())
    {
        if ($flushStore === true) {
            Store::flush();
        }

        $this->factory = $factory;
    }
}
