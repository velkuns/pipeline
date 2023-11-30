<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineFactory
{
    public function new(mixed $input): PipelineFloat|PipelineString|PipelineBool|PipelineArray|PipelineInt|Pipeline
    {
        return match (true) {
            \is_string($input) => new PipelineString($input),
            \is_array($input)  => new PipelineArray($input),
            \is_int($input)    => new PipelineInt($input),
            \is_float($input)  => new PipelineFloat($input),
            \is_bool($input)   => new PipelineBool($input),
            default            => new Pipeline(false),
        };
    }

    public function array(array $input): PipelineArray
    {
        return new PipelineArray($input);
    }

    public function string(string $input): PipelineString
    {
        return new PipelineString($input);
    }

    public function int(int $input): PipelineInt
    {
        return new PipelineInt($input);
    }

    public function float(float $input): PipelineFloat
    {
        return new PipelineFloat($input);
    }

    public function bool(bool $input): PipelineBool
    {
        return new PipelineBool($input);
    }
}
