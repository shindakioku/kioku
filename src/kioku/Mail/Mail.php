<?php

namespace Kioku\Mail;

class Mail
{
    /**
     * @var \Swift_SmtpTransport
     */
    protected $transport;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Mail constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;

        $this->transport = (new \Swift_SmtpTransport(
            $settings['smtp'], $settings['port'], 'ssl'
        ))->setUsername(
            $settings['login']
        )->setPassword(
            $settings['password']
        );

        $this->mailer = new \Swift_Mailer($this->transport);
    }

    /**
     * @param string $to
     * @param string $body
     * @param string $subject
     * @param array $view
     * @param array $from
     * @return int
     */
    public function send(string $to, string $body, string $subject = '', array $view = [], array $from = []): int
    {
        if (!is_null($view)) {
            $loader = new \Twig_Loader_Filesystem(__DIR__.'/../../resources/views/');
            $twig = new \Twig_Environment($loader);

            $body = $twig->render(
                $view['view'], $view['params']
            );
        }

        if (!count($from)) {
            $from = [
                $this->settings['from']['mail'] => $this->settings['from']['nameSurname'],
            ];
        }

        $message = (new \Swift_Message($subject))->setFrom($from)->setTo($to)->setBody($body);

        return $this->mailer->send($message);
    }
}