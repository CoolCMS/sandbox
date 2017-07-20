<?php

namespace CCMS\Core\DI;

use Nette\DI\CompilerExtension;

class CoreExtension extends CompilerExtension
{
    /**
     * @var array
     */
    public $defaults = [];

    public function loadConfiguration()
    {
        $config = $this->validateConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $res = $this->loadFromFile(__DIR__ . '/services.neon');
        $this->compiler->parseServices($builder, $res);

    }
}