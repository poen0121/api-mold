<?php

namespace App\Libraries\Upgrades;

use Prettus\Repository\Generators\RepositoryEloquentGenerator;

class BetterRepositoryEloquentGenerator extends RepositoryEloquentGenerator
{
    /**
     * Get Validator.
     *
     * @return string
     */
    public function getValidatorMethod()
    {
        if ($this->validator != 'yes') {
            return '';
        }

        $class = $this->getClass();

        return ' ' . $class . 'Validator::class';
    }
}
