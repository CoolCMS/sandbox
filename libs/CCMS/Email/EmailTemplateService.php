<?php

namespace CCMS\Email;

use Doctrine\ORM\EntityManager;
use Nette\Mail\Message;
use CCMS\Core\InvalidArgumentException;
use CCMS\Core\ModulesManager;
use CCMS\Core\RuntimeException;
use CCMS\Email\Entity\EmailTemplate;
use Tracy\Debugger;

class EmailTemplateService
{
    /**
     * @var ModulesManager
     */
    private $modulesManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $emailFrom;

    private $defaultShortCodes = [];

    public function __construct($emailFrom, ModulesManager $modulesManager, EntityManager $entityManager)
    {
        $this->modulesManager = $modulesManager;
        $this->entityManager = $entityManager;
        $this->emailFrom = $emailFrom;
    }

    public function getRequiredEmailTemplates()
    {
        $moduleData = $this->modulesManager->getStoredDataForModule('email');
        if (!isset($moduleData['template'])) {
            return [];
        }
        $requiredTemplatesByModules = [];
        foreach ($moduleData['template'] as $id => $templateData) {
            $requiredTemplatesByModules[$id] = $templateData + ['id' => $id];
        }
        return $requiredTemplatesByModules;
    }

    public function getShortCodesForEmailTemplate($id){
        $templates = $this->getRequiredEmailTemplates();
        if(!isset($templates[$id])){
            return $this->defaultShortCodes;
        }
        return array_merge($templates[$id]['shortCodes'], $this->defaultShortCodes);
    }

    /**
     * @param int|string $id
     * @return null|object|EmailTemplate
     */
    public function getTemplate($id)
    {
        $repository = $this->entityManager->getRepository(EmailTemplate::class);
        if (is_numeric($id)) {
            return $repository->find($id);
        }
        return $repository->findOneBy(['templateKey' => $id]);
    }

    public function updateTemplate($data, $id)
    {
        $template = $this->getTemplate($id);
        if (!is_numeric($id)) {
            $data['templateKey'] = $id;
        }

        if ($template) {
            $template->update($data);
        } else {
            $template = new EmailTemplate($data);
            $this->entityManager->persist($template);
        }

        $this->entityManager->flush();
        return $template;
    }

    /**
     * @param $templateId
     * @param $shortCodes
     * @param $to
     * @return Message
     */
    public function createMessageFromTemplate($templateId, $shortCodes, $to){
        $template = $this->getTemplate($templateId);
        if(!$template){
            throw new RuntimeException(sprintf('šablona [%s] neexistuje', $templateId));
        }
        $this->checkRequiredShortCodes($templateId, $shortCodes);

        $message = new Message();
        $message->setFrom($this->emailFrom, $template->getFromName());
        $message->addTo($to);
        $message->setSubject($this->applyShortCodes($template->getSubject(), $shortCodes));
        $message->setHtmlBody($this->applyShortCodes($template->getHtmlMessage(), $shortCodes));
        $message->setBody($this->applyShortCodes($template->getTextMessage(), $shortCodes));
        return $message;
    }

    private function applyShortCodes($content, $shortCodes)
    {
        foreach($shortCodes as $shortCode => $replace){
            $content = str_replace('[' . $shortCode . ']', $replace, $content);
        }
        return $content;
    }

    private function checkRequiredShortCodes($templateId, $shortCodes)
    {
        Debugger::barDump($shortCodes);
        $templateShortCodes = $this->getShortCodesForEmailTemplate($templateId);
        $requiredShortCodes = [];
        foreach($templateShortCodes as $templateShortCode){
            $requiredShortCodes[$templateShortCode['key']] = true;
        }

        foreach($shortCodes as $shortCode => $data){
            unset($requiredShortCodes[$shortCode]);
        }

        if(!empty($requiredShortCodes)){
            throw new InvalidArgumentException(sprintf('Required shortcode [%s] data missing', implode(',', array_keys($requiredShortCodes))));
        }
        return;
    }
}