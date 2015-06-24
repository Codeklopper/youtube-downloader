<?php namespace App\Libraries;
use App\Libraries\FileManagerInterface;

/**
 * Class checks if file exists in the filesystem for a given path
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class FileManager implements FileManagerInterface
{
	/**
	 * Initial path in filesystem
	 * @var string
	 */
	private static $path = "downloads/";

	/**
	 * Checks if file already exists in the filesystem
	 * @param  string $filename
	 * @return bool
	 */
	public static function fileExists($filename)
	{
		if(file_exists(self::$path.$filename))
		{
			return true;
		}else{
			return false;
		}
	}
}