<?php

namespace CCMS\Email;

use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Tracy\Debugger;

class SendService
{
    /**
     * @var IMailer
     */
    private $mailer;

    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Message $message
     */
    public function sendMessage(Message $message){
        if(Debugger::$productionMode === Debugger::DEVELOPMENT){
            Debugger::log($message, 'email');
        }else{
            $this->mailer->send($message);
        }
    }
}