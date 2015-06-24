<?php namespace App\Libraries;

/**
 * Execute a command
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
interface ExecuterInterface
{
	/**
	 * Execute given command
	 * @param  string $command
	 * @return mixed false on error, result on succes
	 */
	public function execute($command);	
}
