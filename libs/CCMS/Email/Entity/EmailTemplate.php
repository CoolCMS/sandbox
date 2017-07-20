<?php

namespace CCMS\Email\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Utils\DateTime;
use CCMS\Parameters\Parameters;

/**
 * @ORM\Entity
 */
class EmailTemplate
{
    use Identifier;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $templateKey;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $fromName;

    /**
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @ORM\Column(type="text")
     */
    protected $htmlMessage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $textMessage;

    /**
     * @ORM\Column(type="datetimetz")
     * @var \Nette\Utils\DateTime
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetimetz")
     * @var DateTime
     */
    protected $created;

    public function __construct($data = []) {
        $this->created = new DateTime();
        $this->updated = new DateTime();
        $this->loadData($data);
    }

    public function update($data = []){
        unset($data['key']);
        $newData = $data + $this->toArray();
        $this->loadData($newData);
        $this->updated = new DateTime();
    }

    private function loadData($data)
    {
        $parameters = Parameters::from($data);
        $this->templateKey = $parameters->getString('templateKey');
        $this->fromName = $parameters->getString('fromName');
        $this->subject = $parameters->getString('subject');
        $this->htmlMessage = $parameters->getString('htmlMessage');
        $this->textMessage = $parameters->getString('textMessage');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'templateKey' => $this->templateKey,
            'fromName' => $this->fromName,
            'subject' => $this->subject,
            'htmlMessage' => $this->htmlMessage,
            'textMessage' => $this->textMessage
        ];
    }

    /**
     * @return string
     */
    public function getTemplateKey()
    {
        return $this->templateKey;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getHtmlMessage()
    {
        return $this->htmlMessage;
    }

    /**
     * @return mixed
     */
    public function getTextMessage()
    {
        return $this->textMessage;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}