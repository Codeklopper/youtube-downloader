<?php namespace App\Libraries;
/**
 * Extract file and video information from youtube-dl output
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */


class VideoInformationExtractor{

	/**
	 * array with format codes from youtube. Used as a format codes database
	 * Format codes reference: https://en.wikipedia.org/wiki/YouTube#Quality_and_codecs
	 * @var array
	 */
	private $dataset;

	/**
	 * predefined audio codes. Used to determine if a format code is used for audio only
	 * @var array flat array with audio codes
	 */
	private $audioCodes = array(139,140,141, 171, 172);


	/**
	 * predefined dash codes. Used to determine if a format code is used for dash video
	 * @var array flat array with dash codes
	 */
	private $dashCodes = array(133,134,135,136,137,160, 242, 243, 244, 247, 248, 264, 271, 278);

	public function __construct()
	{

	}


	/**
	 * set dataset 
	 * @param array $dataset
	 */
	public function setDataset($dataset)
	{
		$this->dataset = $dataset;
	}

	/**
	 * Extract information from youtube-dl output
	 * @param  array  $data array with output lines (string). An output line may contain format codes
	 * @param  boolean $sorted flag to determine if the result array needs to be sorted on formats (mp4 as most important) first and quality (highest first) second 
	 * @return array array with format data
	 */
	public function extract($data, $sorted = true)
	{

		$this->checkDataSet();
		$formatDataArray = array();
		foreach ($data as $line) {


			$formatCode = $this->extractLine($line);
		
			if($formatCode && $formatCode > 0)
			{

				$formatData = $this->getFormatData($formatCode);
				
				if($formatData)
				{
					$formatDataArray[] = $formatData;
				}
			}
		}
	
		if($sorted === true)
		{
			return $this->sortFormatDataArray($formatDataArray);
		}else{
			return $formatDataArray;
		}
	}

	/** 
	 * Extract format code from output line
	 * @param  string $line
	 * @return mixed returns formatcode if found, otherwise false
	 */
	public function extractLine($line)
	{
		$number = (int) $line[0];

		$lineExplode = explode(' ', $line);
		if($number > 0 && is_numeric($lineExplode[0]) && $lineExplode[0] > 0 && count($lineExplode) > 8)
		{
			return trim($lineExplode[0]);
		}
		return false;
	}

	/**
	 * Get formatdata from dataset
	 * @param  string $formatCode
	 * @return mixed array with format data if found, otherwise returns false
	 */
	public function getFormatData($formatCode)
	{
		
		if(!array_key_exists($formatCode,$this->dataset))
		{
			return false;
		}

		$data = $this->dataset[$formatCode];
		if(is_array($data) && $data[0] == $formatCode)
		{
			$quality =  ($data[2] == "N/A" ? $data[7] : $data[2]);
			$quality = str_replace("p","", $quality);
			$format = strtolower($data[1]);
			$format = ($data[2] == "N/A" ? "m4a" :$format);
			$mode = $this->getMode($formatCode);
			return array($data[0], $format, $quality, $mode);
		}else{
			return false;
		}
	}

	/**
	 * returns download mode
	 * @param  string $formatCode
	 * @return string
	 */
	public function getMode($formatCode)
	{
		if(in_array($formatCode, $this->audioCodes))
		{
			return "audio";
		}
		if(in_array($formatCode, $this->dashCodes))
		{
			return "dash";
		}
		return "normal";
	}
	/**
	 * Sort format data array
	 * @param  array $array
	 * @return array sorted array
	 */
	public function sortFormatDataArray(array $array)
	{
		uasort($array, array($this, 'compareFormats')); 
		return array_values($array);;
	}

	/**
	 * Compare two formats
	 * @param  string $a format to compare
	 * @param  string $b format to compare
	 * @return int result of comparison
	 */
	private function compareFormats($a, $b) {
	   
		    if ($a[2] == $b[2]) {
		    	
		    	return ($a[1] == "mp4") ? -1 : 1;
		    }else{
		    	return  ($a[2] < $b[2]) ? 1 : -1;
		    }
		   

	}
	
	/**
	 * Checks if dataset is not empty
	 * will raise exception if dataset is empty
	 * @return void
	 */
	private function checkDataSet()
	{
		if(empty($this->dataset)){
			throw new \Exception("No dataset found", 1);
			
		}
	}

}