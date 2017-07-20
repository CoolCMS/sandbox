<?php
namespace CCMS\Email\Components\TemplateForm;

use App\Presenters\BasePresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use CCMS\Email\EmailTemplateService;
use ThajskyRaj\Finance\EmailTemplates;
use Tracy\Debugger;

class TemplateForm extends Control
{
    /**
     * @var null|int
     */
    private $id;

    /**
     * @var EmailTemplateService
     */
    private $emailTemplateService;

    /**
     * @var array
     * @callable
     */
    public $onDone = [];

    public function __construct($id = null, EmailTemplateService $emailTemplateService)
    {
        parent::__construct();
        $this->id = $id;
        $this->emailTemplateService = $emailTemplateService;
    }

    private function getShortcodes()
    {
        return $this->emailTemplateService->getShortCodesForEmailTemplate($this->id);
    }

    public function createComponentForm()
    {
        $form = new Form();
        $form->addText('fromName');
        $form->addText('subject')->setRequired('Prosím, zadajte predmety newslettera');

        $form->addTextArea('htmlMessage');
        $form->addTextArea('textMessage');

        $form->addSubmit('submit');

        if ($this->id) {
            $template = $this->emailTemplateService->getTemplate($this->id);
            if ($template) {
                $form->setDefaults($template->toArray());
            }
        }

        $form->onError[] = [$this, 'invalidSubmit'];
        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function invalidSubmit($form)
    {
        /** @var BasePresenter $presenter */
        $presenter = $this->getPresenter();
        $presenter->invalidSubmit($form);
    }

    public function processForm(Form $form)
    {
        $this->emailTemplateService->updateTemplate(
            $form->getValues(true),
            $this->id
        );
        $this->onDone();
    }

    public function render()
    {
        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/default.latte');
        $template->shortcodes = $this->getShortcodes();
        $template->render();
    }
}