<?php namespace App\Libraries;
use App\Libraries\FileManager;

/**
 * File object. Contains information about file to download and how to store the file
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class File
{
	/**
	 * Given filename
	 * @var string
	 */
	public $filename;
	/**
	 * URL to download file from
	 * @var string
	 */
	public $url;
	/**
	 * File quality
	 * @var string
	 */
	public $quality;
	/**
	 * File format
	 * @var [type]
	 */
	public $format;
	/**
	 * The filemanager. Can be consulted to check if filename is valid (file is not already on filesystem)
	 * @var FileManager
	 */
	private $filemanager;

	/**
	 * Create File object
	 * @param string $url  URL to download file from
	 * @param string $quality File quality
	 * @param string $format File format
	 */
	public function __construct($url, $quality, $format)
	{
		$this->url 		= $url;
		$this->quality  = $quality;
		$this->format 	= $format;

	}

	/**
	 * Attach FileManager
	 * @param  FileManager $filemanager
	 * @return void
	 */
	public function attachFileManager($filemanager)
	{
		$this->filemanager = $filemanager;
	}
	/**
	 * Return full filename. Filename + extension
	 * @return string
	 */
	public function getFullFileName()
	{
		return $this->filename.".".$this->format;
	}
	/**
	 * Get temporary filename
	 * @return string
	 */
	public function getTempFileName()
	{
		return 'tmp/'.$this->filename."_tmp";
	}

	/**
	 * Get Filename
	 * @return string
	 */
	public function getFileName()
	{
		return $this->filename;
	}

	/**
	 * Checks if file does not exist on the filesystem.
	 * Creates a new filename with a random number appended and recursively checks the filename again
	 * @return void
	 */
	public function checkFileName()
	{
		if(FileManager::fileExists($this->getFullFileName()))
		{
			$this->filename = $this->filename . "_" .rand(0,1000);
			$this->checkFileName();
		}
	}
	/**
	 * Set FileName
	 * @param string $filename
	 */
	public function setFileName($filename)
	{
		$this->filename = $filename;
	}
}

