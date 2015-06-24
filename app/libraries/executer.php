<?php namespace App\Libraries;
use App\Libraries\ExecuterInterface;

/**
 * Execute shell commands
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class Executer implements ExecuterInterface
{
	/**
	 * flag for returning debugger information
	 * @var boolean
	 */
	public $debugger = false;
	/**
	 * Allow unescaped commands
	 * @var boolean
	 */
	public $unsafe = false;

	public $message = null;

	/**
	 * Executes command. Returns false on error, returns result if command succeeded
	 * prints extra information when debugger is activated
	 * unsafe commands are accepted if flag is explicitly set 
	 * @param  string $command shell command
	 * @return mixed returns result or false (if error)
	 */
	public function execute($command){
		if(!$this->unsafe)
		{
			$command = escapeshellcmd($command);
		}
		exec($command, $result, $error);
		if($error)
		{
			$this->message = "<br /><br />".  $command . "<br /><br />";
			$this->message .= "Error: ". $error;
			if($this->debugger)
			{
				echo $this->message;
			}
			return false;
		}else{

			if($this->debugger)
			{	
				echo "<br /><br />" . $command . "<br /><br />";
				echo "<pre>";
				print_r($result);
			}
			return $result;
		}
	}

}