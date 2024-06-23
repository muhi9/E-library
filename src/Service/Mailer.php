<?php 

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer {
	
	private $container;
	private $mailer;

	public function __construct(ContainerInterface $container, MailerInterface $mailer) {
		$this->container = $container;
		$this->mailer = $mailer;
	}

	
	public function sendEmailMessage($from, $to, $subject = '', $body = null, $text = null) {
		
		if (null == $body && null == $text) {
			throw new \Exception("Body OR Text MUST BET SET!");	
		} 

		
		$email = (new Email())
            ->from($from)
	        ->to($to);
         
	    $email
            ->subject($subject);
            if($body) {
            	$email->html($body);
            }

            if($text) {
            	$email->text($text);
         	 }
         
        try {
        	$this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
		    dump($e->getMessage());
		}
		
	}
}