<?php

namespace App\AdminModule;

use CCMS\Email\Components\TemplateForm\ITemplateForm;
use CCMS\Email\Components\Templates\ITemplatesGrid;

class EmailPresenter extends AdminPresenter
{
    /**
     * @inject
     * @var ITemplatesGrid
     */
    public $emailTemplatesGridFactory;

    /**
     * @inject
     * @var ITemplateForm
     */
    public $templateForm;

    public function actionEdit($id){

    }

    public function createComponentTemplateForm(){
        $templateForm = $this->templateForm->create(
            $this->getParameter('id')
        );
        $templateForm->onDone = function(){
            $this->flashMessage($this->getParameter('id') ? 'Šablona bola úspešne pridaná!' : 'Šablona bola úspešne upravená!', 'success');
            $this->redirect('default');
        };
        return $templateForm;
    }

    public function createComponentGrid(){
        $grid = $this->emailTemplatesGridFactory->create();
        $grid->onShowEdit[] = function($id){
            $this->redirect('edit', $id);
        };
        return $grid;
    }
}