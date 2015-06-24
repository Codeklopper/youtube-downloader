<?php namespace App\Libraries;
interface FileManagerInterface
{
	/**
	 * Checks if file already exists in the filesystem
	 * @param  string $filename
	 * @return bool
	 */
	public static function fileExists($filename);
}
