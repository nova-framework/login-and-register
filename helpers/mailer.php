<?php

class Mailer {

	public static function sendmail($to,$from,$subject,$textmail,$htmlmail = NULL, $attachment = NULL){
	    require DOCROOT.'/helpers/swiftmailer/swift_required.php';

	    //Mail
	    $transport = Swift_MailTransport::newInstance();
	    
	    //Create the Mailer using your created Transport
	    $mailer = Swift_Mailer::newInstance($transport);
	    
	    //Create the message
	    $message = Swift_Message::newInstance()
	    
	    //Give the message a subject
	    ->setSubject($subject)
	    
	    //Set the From address with an associative array
	    ->setFrom($from)
	    
	    //Set the To addresses with an associative array
	    ->setTo($to)
	    
	    //Give it a body
	    ->setBody($textmail);
	    
	    if($htmlmail !=''){
	    
	        //And optionally an alternative body
	        $message->addPart($htmlmail, 'text/html');
	    
	    }
	    
	    if($attachment !=''){
	    
	        //Optionally add any attachments
	        $message->attach(
	          Swift_Attachment::fromPath($attachment)->setDisposition('inline')
	        );
	    }
	    
	    //Send the message
	    $result = $mailer->send($message);
	    
	    return $result;
	}

}
