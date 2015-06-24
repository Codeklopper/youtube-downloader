<?php namespace App\Libraries;
use App\Libraries\Factory;
use App\Libraries\File;

/**
 * File object factory. Creates File objects
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class FileFactory implements Factory
{

	/**
	 * Create File object with arguments from $data: string $url, string $quality, string $format
	 * @param  array $data
	 * @return File object
	 */
	public function make($data)
	{
		return new File($data[0],$data[1],$data[2]);
	}
}