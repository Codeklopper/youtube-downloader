<?php namespace App\Libraries;
use App\Libraries\Factory;
use App\Libraries\FileSettings;

/**
 * File object factory. Creates File objects
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class FileSettingsFactory implements Factory
{
	/**
	 * Create FileSettings object
	 * @param  array $data data array with FileSettings contructor arguments: string $formatCode, string $format, string $quality, string $mode
	 * @return FileSettings object
	 */
	public function make($data)
	{
		return new FileSettings($data[0],$data[1],$data[2], $data[3]);
	}
}

?>