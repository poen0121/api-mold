<?php

namespace App\Libraries\Traits\Info\Attach;
use Exception;

trait Muppet
{
    /**
     * Resources for additional fillers.
     *
     * @var array
     */
    private $muppetFillers = [];

    /**
     * Put resources to the filler.
     *
     * @param string $key
     * @param mixed $data
     * 
     * @return void
     */
    public function putFiller(string $key, $data)
    {
        $this->muppetFillers[$key] = $data;
        return $this;
    }

    /**
     * Get resources from the filler.
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function getFiller(string $key)
    {
        if (array_key_exists($key, $this->muppetFillers)) {
            return $this->muppetFillers[$key];
        }
        throw new Exception('Muppet Filler: Resource not found: Unknown resource \'' . $key . '\'.');
    }
}