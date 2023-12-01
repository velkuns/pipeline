<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class PipelineString
{
    /** @use PipelineTrait<string> */
    use PipelineTrait;

    private array $allowedMethods = [
        'haystack_first' => [
            'trim'  => 'trim',
            'split' => 'str_split',
        ],
        'haystack_last' => [
            'explode' => 'explode',
        ]
    ];

    public function __construct(
        private readonly string $input,
        PipelineFactory $factory = new PipelineFactory()
    ) {
        $this->factory = $factory;
    }

    public function regex(string $function, string $pattern, string $replacement = '', ...$args): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString
    {
        $result = null;
        switch ($function) {
            case 'match':
                \preg_match($pattern, $this->input, $result, ...$args);
                break;
            case 'matchAll':
                \preg_match_all($pattern, $this->input, $result, ...$args);
                break;
            case 'replace':
                $result = \preg_replace($pattern, $replacement, $this->input, ...$args);
                break;
            default:
                throw new \LogicException('Unsupported regex function!');
        }

        return $this->factory->new($result);
    }

    public function __call(string $name, array $args): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString
    {
        if (isset($this->allowedMethods['haystack_first'][$name])) {
            return $this->useHaystackFirst($this->allowedMethods['haystack_first'][$name], $args);
        }

        if (isset($this->allowedMethods['haystack_last'][$name])) {
            return $this->useHaystackLast($this->allowedMethods['haystack_first'][$name], $args);
        }

        throw new \LogicException("function currently not supported (name: $name)!");
    }

    private function useHaystackFirst(string $name, array $args): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString
    {
        //~ Put input as first argument.
        \array_unshift($args, $this->input);

        return $this->factory->new(\call_user_func_array($name, $args));
    }

    private function useHaystackLast(string $name, array $args): PipelineInt|Pipeline|PipelineBool|PipelineArray|PipelineFloat|PipelineString
    {
        //~ Put input as first argument.
        $args[] = $this->input;

        return $this->factory->new(\call_user_func_array($name, $args));
    }
}
