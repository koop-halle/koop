<?php

namespace Application\Util;

/**
 * Class Mailer
 *
 * @package Application\Util
 */
class Mailer
{
    /**
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    protected $instance;

    /**
     * @var bool
     */
    protected $skipSend = false;

    /**
     * @var string
     */
    protected $mailFrom = '';


    /**
     * Mailer constructor.
     */
    private final function __construct()
    {
        $instance       = $this
            ->setInstance(new \PHPMailer\PHPMailer\PHPMailer(true))
            ->getInstance()
        ;
        $configuration  = require ROOT_DIR . DIRECTORY_SEPARATOR . 'mailConfig.php';
        $requiredFields = [
            'skipSend',
            'host',
            'userName',
            'password',
            'port',
            'from',
            'fromName',
            'replyTo',
            'replyToName',
            'debug',
            'secure',
        ];
        if (false === is_array($configuration)) {
            throw new \Exception('mailConfig.php did not return an array');
        }
        foreach ($requiredFields as $requiredField) {
            if (false === array_key_exists($requiredField, $configuration)) {
                throw new \Exception('missing field "' . $requiredField . '" in mailConfig.php');
            }
        }
        $mailSkipSend      = $configuration['skipSend'];
        $mailHost          = $configuration['host'];
        $mailUsername      = $configuration['userName'];
        $mailPassword      = $configuration['password'];
        $debug             = $configuration['debug'];
        $mailPort          = $configuration['port'];
        $this->mailFrom    = $configuration['from'];
        $mailFromName      = $configuration['fromName'];
        $address           = $configuration['replyTo'];
        $name              = $configuration['replyToName'];
        $mailAddBcc        = $configuration['bcc'];
        $secure            = $configuration['secure'];
        $this->skipSend    = $mailSkipSend;
        $instance->CharSet = 'UTF-8';
        $instance->isSMTP();
        $instance->Host          = $mailHost;
        $instance->Username      = $mailUsername;
        $instance->Password      = $mailPassword;
        $instance->SMTPKeepAlive = true;
        $instance->SMTPSecure    = $secure;
        $instance->SMTPAuth      = true;
        $instance->SMTPOptions   = ['ssl' => ['allow_self_signed' => true]];
        $instance->Timeout       = 10;
        $instance->SMTPDebug     = 0;
        if (true === $debug) {
            $instance->SMTPDebug = 4;
        }
        $instance->Port = $mailPort;
        $instance->setLanguage('de');
        $instance->setFrom($this->mailFrom, $mailFromName);
        $instance->addReplyTo($address, $name);
        foreach ($mailAddBcc as $bcc) {
            $instance->addBCC($bcc);
        }
        $instance->isHTML(true);
        $instance->addCustomHeader('foo', 'bar');
        $instance->addCustomHeader('bar', 'foo');
    }


    /**
     * @return \PHPMailer\PHPMailer\PHPMailer
     */
    protected function getInstance()
    {
        return $this->instance;
    }


    /**
     * @param \PHPMailer\PHPMailer\PHPMailer $mailer
     *
     * @return $this
     */
    protected function setInstance(\PHPMailer\PHPMailer\PHPMailer $mailer)
    {
        $this->instance = $mailer;

        return $this;
    }


    /**
     * @return \Application\Util\Mailer
     */
    public static function Factory()
    {
        return new static();
    }


    /**
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send()
    {
        $directoryName = ROOT_DIR . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'mail' . DIRECTORY_SEPARATOR;
        if (false === is_dir($directoryName)) {
            mkdir($directoryName, 0777, true);
        }
        file_put_contents($directoryName . uniqid() . '.html', $this->getInstance()->Body);
        if (true === $this->skipSend) {
            return true;
        }
        set_time_limit(10);
        $result = $this->getInstance()->send();
        if (false === $result) {
            DependencyContainer::getInstance()
                               ->getLogger()
                               ->emerg(
                                   'can not send email!',
                                   [
                                       'recipientAddresses' => serialize($this->getInstance()->getAllRecipientAddresses()),
                                       'fromAddresses'      => $this->getInstance()->Sender,
                                       'subject'            => $this->getInstance()->Subject,
                                       'body'               => $this->getInstance()->Body,
                                       'altBody'            => $this->getInstance()->AltBody,
                                   ]
                               )
            ;
            if (DependencyContainer::getInstance()->getConfiguration()->isDevelopment()) {
                throw new \Exception($this->getInstance()->ErrorInfo);
            }
        }

        return true;
    }


    /**
     * @param $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->getInstance()->Subject = $subject;

        return $this;
    }


    /**
     * @param string        $address
     * @param string | null $name
     *
     * @return $this
     */
    public function addRecipient($address, $name = null)
    {
        $this->getInstance()->addAddress($address, $name);

        return $this;
    }


    /**
     * @param string        $address
     * @param string | null $name
     *
     * @return $this
     */
    public function addCc($address, $name = null)
    {
        $this->getInstance()->addCC($address, $name);

        return $this;
    }


    /**
     * @param string        $address
     * @param string | null $name
     *
     * @return $this
     */
    public function addBcc($address, $name = null)
    {
        $this->getInstance()->addBCC($address, $name);

        return $this;
    }


    /**
     * @param string $html
     *
     * @return $this
     */
    public function setHtml($html)
    {

        $scss = new \Leafo\ScssPhp\Compiler();
        $scss->addImportPath(ROOT_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR);
        $css        = $scss->compile(file_get_contents(ROOT_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . 'mail.scss'));
        $emogrifier = new \Pelago\Emogrifier($html, $css);
        $html       = $emogrifier->emogrify();
        $this
            ->getInstance()
            ->msgHTML($html, __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR)
        ;

        return $this;
    }


    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->getInstance()->Body = $body;

        return $this;
    }


    /**
     * @param string $body
     *
     * @return $this
     */
    public function setAlternativeBody($body)
    {
        $this->getInstance()->AltBody = $body;

        return $this;
    }


    /**
     * @return bool
     */
    public function isSkipSend(): bool
    {
        return $this->skipSend;
    }


    /**
     * @return string
     */
    public function getMailFrom()
    {
        return $this->mailFrom;
    }
}