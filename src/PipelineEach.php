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
 * Class PipelineEach
 *
 * @method self explode(string $glue)
 * @method self map(callable $callable)
 * @method self unique()
 * @method self intersect()
 * @method self current()
 */
class PipelineEach
{
    /** @use PipelineTrait<array> */
    use PipelineTrait;

    /** @var \SplQueue<array{0: string, 1: array<mixed>}> $pipeQueue */
    private \SplQueue $pipeQueue;

    public function __construct(
        private readonly array $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory   = $factory;
        $this->pipeQueue = new \SplQueue();
    }

    public function __call(string $name, array $arguments): self
    {
        $this->pipeQueue->enqueue([$name, $arguments]);

        return $this;
    }

    /**
     * Start new SubPipeline. So we need to advance on the current "each pipeline", then start new one with current
     * updated input.
     */
    public function each(): PipelineEach
    {
        throw new \LogicException('Each inner existing each is currently not supported!');
    }

    public function end(): PipelineArray
    {
        $input = $this->input;

        while (!$this->pipeQueue->isEmpty()) {
            [$name, $args] = $this->pipeQueue->dequeue();

            foreach ($input as $index => $item) {
                $input[$index] = $this->factory->new($input)->$name(...$args)->get();
            }
        }

        return $this->factory->array($input);
    }
}
