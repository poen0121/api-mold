<?php

namespace App\Libraries\Upgrades;

use Prettus\Repository\Generators\ControllerGenerator;

class BetterControllerGenerator extends ControllerGenerator
{

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . 'Controller.php';
    }

    /**
     * Gets controller name based on model
     *
     * @return string
     */
    public function getControllerName()
    {
        return ucfirst(ucwords($this->getClass()));
    }

    /**
     * Gets dummy class name based on model
     *
     * @return string
     */
    public function getDummyClass()
    {
        return str_replace('/', '\\', $this->getName());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'controller' => $this->getControllerName(),
            'plural' => $this->getPluralName(),
            'singular' => $this->getSingularName(),
            'validator' => $this->getValidator(),
            'repository' => $this->getRepository(),
            'appname' => $this->getAppNamespace(),
            'dummyclass' => $this->getDummyClass()
        ]);
    }
}
