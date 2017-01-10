<?php
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 */
class Mail{
	private $to;
	/**
	* @Assert\Length(min=4,max=30)
	* @Assert\Regex("/[[:alpha:][:space:]]/", message="This value should be based only on letters.")
	* @Assert\NotBlank(message="Please insert a title of your mail.")
	*/
	private $subject;
	/**
	* @Assert\Length(min=4, max=1000)
	* @Assert\NotBlank(message="Please insert a content of your mail.")
	*/
	private $message;
	/**
	* @Assert\Email
	* @Assert\NotBlank(message="Please insert a content of your mail.")
	*/
	private $from;
	private $headers;

	public function __construct($subject,$from,$message){
		$this->subject=$subject;
		$this->from=$from;
		$this->message=$message;
	}
	private function getTo(){
		//our mail
		return $this->to="junior@localhost.com";
	}
	public function createMail(){
		$t=$this->getTo();
		$headers = "From: <".$this->from.">\r\n";
		$mail=mail($t, $this->subject, $this->message,$headers);
		return $mail;
	}
}