<?php namespace App\Libraries;

/**
 * File settings object. Contains information about the quality of a file
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class FileSettings
{
	/**
	 * Youtube format code
	 * @var string
	 */
	private $formatCode;
	/**
	 * file format
	 * @var string
	 */
	private $format;

	/**
	 * File quality
	 * @var string
	 */
	private $quality;

	/**
	 * Download mode
	 * @var string
	 */
	private $mode;
	
	public function __construct($formatCode, $format, $quality, $mode)
	{
		$this->formatCode 	= $formatCode;
		$this->format 		= $format;
		$this->quality		= $quality;
		$this->mode			= $mode;
	}
	
	/**
	 * Return youtube format code
	 * @return string
	 */
	public function getFormatcode()
	{
		return $this->formatCode;
	}

	/**
	 * return file format
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}
	
	/**
	 * Return quality
	 * @return string
	 */
	public function getQuality()
	{
		return $this->quality;
	}	

	/**
	 * return download mode
	 * @return string
	 */
	public function getMode()
	{
		return $this->mode;
	}	
}