<?php
namespace CCMS\User;

use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\DI\CompilerExtension;

class UserExtension extends CompilerExtension implements IEntityProvider
{
    /**
     * @var array
     */
    public $defaults = [
    ];

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $res = $this->loadFromFile(__DIR__ . '/services.neon');
        $this->compiler->parseServices($builder, $res);
    }

    /**
     * Returns associative array of Namespace => mapping definition
     *
     * @return array
     */
    function getEntityMappings()
    {
        return [
            'CCMS\User' => __DIR__ . '/../',
        ];
    }
}