<?php
namespace CCMS\Email\DI;

use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\DI\CompilerExtension;
use Tracy\Debugger;

class EmailExtension extends CompilerExtension implements IEntityProvider
{
    /**
     * @var array
     */
    public $defaults = [
        'fromEmail' => null
    ];

    public function loadConfiguration()
    {
        $config = $this->validateConfig($this->defaults);
        if (empty($config)) {
            throw new \UnexpectedValueException("Please configure the Email extensions using the section '{$this->name}:' in your config file.");
        }

        $builder = $this->getContainerBuilder();
        $res = $this->loadFromFile(__DIR__ . '/services.neon');
        $this->compiler->parseServices($builder, $res);
        $builder->getDefinition('emailTemplateService')
        ->setArguments([
            'emailFrom' => $config['fromEmail']
        ]);
    }

    /**
     * Returns associative array of Namespace => mapping definition
     *
     * @return array
     */
    function getEntityMappings()
    {
        return [
            'CCMS\Email' => __DIR__ . '/../Entity',
        ];
    }
}