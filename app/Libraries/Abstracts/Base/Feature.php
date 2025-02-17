<?php

namespace App\Libraries\Abstracts\Base;

use Validator;
use Lang;
use Exception;

abstract class Feature
{

    /**
     * The input action type.
     *
     * @var array
     */
    protected $action;

    /**
     * The input interface implementation.
     *
     * @var array
     */
    protected $input = [];

    /**
     * The release input.
     *
     * @var array
     */
    protected $releaseInput = [];

    /**
     * The arguments list.
     *
     * @var array
     */
    protected $arguments;

    /**
     * The responses list.
     *
     * @var array
     */
    protected $responses;

    /**
     * Get the feature id code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        $providers = array_unique(config('feature.providers'));

        $code = array_search(get_called_class(), $providers);

        return (is_string($code) ? $code : null);
    }

    /**
     * Get the validation rules that apply to arguments input.
     *
     * @return array
     */
    protected function getRules()
    {
        $deploy = $this->deployRules();

        $handle = $this->handleRules();

        $rules = [];

        $rules['deploy'] = (is_array($deploy) ? $deploy : []);

        $rules['handle'] = (is_array($handle) ? $handle : []);

        return $rules;
    }

    /**
     * Get input argument.
     *
     * @param string $name
     * @return mixed|null
     */
    protected function getInput(string $name)
    {
        return (isset($this->input[$name]) ? $this->input[$name] : null);
    }

    /**
     * Get the output handle.
     *
     * @param array $handle
     * @return array
     */
    protected function output(array $handle = []): array
    {
        $getResponses = $this->getResponsesDescription();
        /* Set type */
        $handle = collect($handle)->map(function ($value, $key) use ($getResponses) {
            if (isset($getResponses[$this->action][$key])) {
                switch ($getResponses[$this->action][$key]['type']) {
                    case 'string':
                        return (string) $value;
                    case 'integer':
                        return (int) $value;
                    case 'array':
                        return (array) $value;
                    case 'object':
                        return (object) $value;
                    case 'boolean':
                        return (bool) $value;
                }
            }
            return $value;
        })->all();

        return $handle;
    }

    /**
     * Get the feature arguments description.
     *
     * @param string $name
     * @return string
     */
    protected function getArgumentsOneDescription(string $name): string
    {
        return Lang::dict('feature.' . strtr(get_class($this), ['\\' => '.']) . '.document', 'arguments.' . $name, 'Undefined');
    }

    /**
     * Get the feature arguments list.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        $deploy = $this->deployArguments();

        $handle = $this->handleArguments();

        $arguments = [];

        $arguments['deploy'] = (is_array($deploy) ? $deploy : []);

        $arguments['handle'] = (is_array($handle) ? $handle : []);

        return $arguments;
    }

    /**
     * Get the feature responses description.
     *
     * @param string $name
     * @return string
     */
    protected function getResponsesOneDescription(string $name): string
    {
        return Lang::dict('feature.' . strtr(get_class($this), ['\\' => '.']) . '.document', 'responses.' . $name, 'Undefined');
    }

    /**
     * Get the feature responses list.
     *
     * @return array
     */
    protected function getResponses(): array
    {
        $deploy = $this->deployResponses();

        $handle = $this->handleResponses();

        $responses = [];

        $responses['deploy'] = (is_array($deploy) ? $deploy : []);

        $responses['handle'] = (is_array($handle) ? $handle : []);

        return $responses;
    }

    /**
     * Review release input argument.
     *
     * @return void
     */
    private function reviewInput()
    {
        $getArguments = $this->getArgumentsDescription();
        foreach ($this->releaseInput as $arg => $argv) {
            if (isset($getArguments[$this->action][$arg])) {
                $this->input[$arg] = $argv;
            }
        }
        $this->releaseInput = [];
    }

    /**
     * Get the feature description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return Lang::dict('feature.' . strtr(get_class($this), ['\\' => '.']) . '.document', 'description', 'Undefined');
    }

    /**
     * Get the arguments list description.
     *
     * @return array
     * @throws \Exception
     */
    public function getArgumentsDescription(): array
    {
        if (!isset($this->arguments)) {
            $arguments = $this->getArguments();
            $rules = $this->getRules();
            $this->arguments = [];
            foreach ($arguments as $action => $args) {
                $this->arguments[$action] = [];
                foreach ($args as $info) {
                    if (is_array($info)) {
                        /* argument type */
                        if (isset($info[1])) {
                            $this->arguments[$action][$info[0]]['type'] = $info[1];
                        } else {
                            $this->arguments[$action][$info[0]]['type'] = 'unknown';
                        }
                        /* argument status */
                        if (isset($rules[$action][$info[0]])) {
                            $this->arguments[$action][$info[0]]['status'] = ((is_array($rules[$action][$info[0]]) && in_array('required', $rules[$action][$info[0]])) || (is_string($rules[$action][$info[0]]) && strpos($rules[$action][$info[0]], 'required') !== false) ? 'required' : 'optional');
                        } else {
                            $this->arguments[$action][$info[0]]['status'] = 'optional';
                        }
                        /* argument description */
                        if (isset($info[2])) {
                            $this->arguments[$action][$info[0]]['description'] = $info[2];
                        } else {
                            $this->arguments[$action][$info[0]]['description'] = $this->getArgumentsOneDescription($action . '.' . $info[0]);
                        }
                    } else {
                        throw new Exception('Feature arguments is undefined correctly.');
                    }
                }
            }
        }
        return $this->arguments;
    }

    /**
     * Get the responses list description.
     *
     * @return array
     */
    public function getResponsesDescription(): array
    {
        if (!isset($this->responses)) {
            $parameters = $this->getResponses();
            $this->responses = [];
            foreach ($parameters as $action => $tag) {
                $this->responses[$action] = [];
                foreach ($tag as $info) {
                    /* parameter type */
                    if (isset($info[1])) {
                        $this->responses[$action][$info[0]]['type'] = $info[1];
                    } else {
                        $this->responses[$action][$info[0]]['type'] = 'unknown';
                    }
                    /* parameter description */
                    if (isset($info[2])) {
                        $this->responses[$action][$info[0]]['description'] = $info[2];
                    } else {
                        $this->responses[$action][$info[0]]['description'] = $this->getResponsesOneDescription($action . '.' . $info[0]);
                    }
                }
            }
        }

        return $this->responses;
    }

    /**
     * Set input arguments.
     *
     * @param string $name
     * @param mixed $value
     * @param boolean $release
     *
     * @return void
     */
    public function setInput(string $name, $value, bool $release = false)
    {
        if ($release) {
            $this->releaseInput[$name] = $value;
        } else {
            $this->input[$name] = $value;
        }
    }

    /**
     * Get the begin handle deployment of the feature.
     *
     * @return array
     */
    public function getDeployment(): array
    {
        /* Set action type */
        $this->action = 'deploy';
        /* Review release input */
        $this->reviewInput();
        /* Get rules */
        $rules = $this->getRules();
        /* Validate */
        Validator::make($this->input, $rules[$this->action])->validate();
        /* Get deploy */
        $deploy = $this->deploy();

        $this->input = [];

        return (is_array($deploy) ? $deploy : []);
    }

    /**
     * Execute the function handle to run.
     *
     * @param array $deployment
     * @return array|null
     */
    public function run(array $deployment = []): ?array
    {
        /* Input deployment */
        if (count($deployment) > 0) {
            foreach ($deployment as $arg => $argv) {
                $this->setInput($arg, $argv);
            }
        }
        /* Set action type */
        $this->action = 'handle';
        /* Review release input */
        $this->reviewInput();
        /* Get rules */
        $rules = $this->getRules();
        /* Validate */
        Validator::make($this->input, $rules[$this->action])->validate();
        /* Run handle */
        $handle = $this->handle();

        $this->input = [];

        return (is_array($handle) ? $handle : null);
    }

    /**
     * Get the begin handle deployment of the feature.
     *
     * @return array
     */
    protected function deploy(): array
    {
        return $this->output([
            //
        ]);
    }

    /**
     * Execute the feature handle.
     *
     * @return array|null
     */
    protected function handle(): ?array
    {
        return null;
    }
}
