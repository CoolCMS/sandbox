<?php

namespace CCMS\Email\Components\Templates;

use Mesour\DataGrid\ArrayDataSource;
use Mesour\DataGrid\Components\Link;
use Mesour\DataGrid\Grid;
use Nette\Application\UI\Control;
use CCMS\Email\EmailTemplateService;

class TemplatesGrid extends Control
{
    /**
     * @var EmailTemplateService
     */
    private $emailTemplatesService;

    public function __construct(EmailTemplateService $emailTemplateService){
        parent::__construct();
        $this->emailTemplatesService = $emailTemplateService;
    }

    public $onShowEdit = [];

    public function createComponentGrid()
    {
        $dataSource = new ArrayDataSource($this->getDataSource());
        $grid = new Grid();
        $grid->setDataSource($dataSource);
        $grid->addText('name', 'Nazev');
        $grid->addText('module', 'Modul');
        $grid->addActions('Akce')
            ->addButton()
            ->setType('btn-primary')
            ->setText('Upravit')
            ->setAttribute('href', new Link('grid:Edit!',[
                'id' => '{id}'
            ]));
        return $grid;
    }

    public function handleEdit($id){
        $this->onShowEdit($id);
    }

    /**
     * @return array
     */
    private function getDataSource()
    {
        return $this->emailTemplatesService->getRequiredEmailTemplates();
    }

    public function render()
    {
        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/default.latte');
        $template->render();
    }
}