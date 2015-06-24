<?php namespace App\Libraries;
use App\Libraries\FileSettings;
use App\Libraries\FileSettingsFactory;

/**
 * Used to store and retrieve file settings
 *
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-1-2014
 */
class FileSettingsRepository
{	
	/**
	 * Filesettings factory
	 * @var Factory
	 */
	private $factory;

	/**
	 * Video information extractor
	 * @var VideoInformationExtractor
	 */
	private $extractor;

	/**
	 * Persistence gateway
	 * @var Persistence
	 */
	private $persistence;

	/**
	 * Fileformat
	 * @var string
	 */
	private $format;
	/**
	 * Videoquality
	 * @var string
	 */
	private $quality;
	/**
	 * Array with FileSettings objects
	 * @var array
	 */
	private $fileSettingsObjects;
	/**
	 * Keeps track of position (current key) in fileSettingsObjects array. 
	 * Key is used to get a FileSettingsObject from the array
	 * If false, array iteration is not started or reset.
	 * @var mixed
	 */
	private $pointer = false;

	/**
	 * Flag to automaticaly get the highest quality available
	 * @var boolean
	 */
	private $useHighestQualityAvailable = TRUE;

	/**
	 * Flag to automaticaly get the lowest quality available
	 * @var boolean
	 */
	private $useLowestQualityAvailable = TRUE;

	function __construct()
	{
		

	}
	/**
	 * Attach factory to create filesettings objects
	 * @param  [Factory] $factory
	 * @return  void
	 */
	public function attachFactory(Factory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * Attach extractor, which extracts video information from youtube-dl data
	 * @param  [VideoInformationExtractor] $extractor
	 * @return void
	 */
	public function attachExtractor(VideoInformationExtractor $extractor)
	{
		$this->extractor = $extractor;
	}

	/**
	 * Attach persistence gateway to store results
	 * @param  Persistence $persistence
	 * @return void
	 */
	public function attachPersistenceGateway(Persistence $persistence)
	{
		$this->persistence = $persistence;
	}

	/**
	 * Set array with FileSettings objects from extracted information. 
	 * Information provided by youtube-dl will be extracted by the extractor and stored in an array of FileSettings objects
	 * @param array  $information
	 * @param string $format
	 * @param string $quality
	 */
	public function setInformation(array $information, $format, $quality)
	{
		$this->format  = $format;
		$this->quality = $quality;
		$results = $this->extract($information);

		$fileSettingsObjects = array();

		foreach ($results as $key => $filesettingsData) {
			$fileSettingsObjects[] = $this->build($filesettingsData);
		}	
		
		$this->fileSettingsObjects = $fileSettingsObjects;
	}

	/**
	 * Loop trough the array with FileSettings objects and find key belonging to FileSettings object, based on provided parameters
	 * @param  mixed $quality
	 * @param  mixed $format
	 * @param  mixed $allowedModes collection of modes to look for
	 * @param  bool $debug get debug information
	 * @return int array key of FileSettings object
	 */
	public function findSettingsKey($quality = false, $format = false, $allowedModes = false, $debug = false)
	{
		if($quality == false && $format == false && $allowedModes == false  && array_key_exists(0, $this->fileSettingsObjects))
		{
			return $this->fileSettingsObjects[0];
		}
		$lastQuality = false;
		$lastKey = false;


		for($i = ($this->pointer === false ? 0 : $this->pointer + 1); $i < count($this->fileSettingsObjects); $i++) {

			$fileSettings = $this->fileSettingsObjects[$i];

			if($format != $fileSettings->getFormat() || !in_array($fileSettings->getMode(), $allowedModes)){
				continue;
			}

			if($quality == $fileSettings->getQuality()){
				return $i;
			}
			if($quality > $fileSettings->getQuality())
			{

				if($this->pointer === false && $lastQuality === false)
				{
					if(!$this->useHighestQualityAvailable)
					{
						return false;
					}
					return $i;
				}
				if(($this->pointer !== false && $lastQuality === false) || ($this->pointer === false && $lastQuality !== false)) 
				{	
					return $i;
				}


				
			}

			$lastQuality = $fileSettings->getQuality();
			$lastKey = $i;
		}
		

		if($lastQuality != false && $lastQuality > $quality && $this->useLowestQualityAvailable)
		{
			return $lastKey;
		}
		return false;
	}

	/**
	 * Get next available key from filesettings array
	 * returns false if there are no more keys
	 * @param  array  $allowedModes
	 * @return mixed returns false if no key, otherwise returns fileSettingsObject belonging to found array key
	 */
	public function getNextFileSettings(array $allowedModes = array('normal', 'dash')) {
		$key = $this->findSettingsKey($this->quality, $this->format, $allowedModes);
		if($key === false)
		{
			return false;
		}
		$this->setPointer($key);
		$this->useHighestQualityAvailable = false;
		$this->useLowestQualityAvailable = false;
		return $this->fileSettingsObjects[$key];
	}


	/**
	 * Get filesettings for audio files only
	 * @return mixed false if nog filesettings are found, otherwise returns found audio Filesettings object
	 */
	public function getAudioFileSettings()
	{
		//testen!!!
		$this->resetPointer();
		$this->useHighestQualityAvailable = true;
		$this->useLowestQualityAvailable = true;
		$key = $this->findSettingsKey($this->quality, "m4a", array('audio'));
		if($key === false)
		{
			return false;
		}
		$this->setPointer($key);		
		return $this->fileSettingsObjects[$key];
	}

	/**
	 * Set pointer to position in array
	 * @param mixed $value key in array or false
	 */
	public function setPointer($value)
	{
		if($value !== false)
		{
			$this->pointer = $value;
		}
	}

	/**
	 * Return pointer position
	 * @return mixed false or int (array key)
	 */
	public function getPointer()
	{
		return $this->pointer;
	}

	/**
	 * Reset pointer
	 * @return void
	 */
	public function resetPointer()
	{
		$this->pointer = false;
	}

	/**
	 * Flag to automaticaly get the highest quality available
	 * @param bool $value
	 */
	public function setAutoGetHighestQuality($value)
	{
		$this->useHighestQualityAvailable = $value;
	}
	/**
	 * Flag to automaticaly get the lowest quality available
	 * @param bool $value
	 */
	public function setAutoGetLowestQuality($value)
	{
		$this->useLowestQualityAvailable = $value;
	}

	/**
	 * Return true if system is allowed to download highest quality available
	 * @return bool
	 */
	public function canDownloadHighestQuality()
	{
		return $this->useHighestQualityAvailable;
	}
	/**
	 * Return true if system is allowed to download lowest quality available
	 * @return bool
	 */
	public function canDownloadLowestQuality()
	{
		return $this->useLowestQualityAvailable;
	}




	/**
	 * Save current filesttingsObjects array in persisentce layer
	 * @return  void
	 */
	public function save()
	{
		$this->add($this->fileSettingsObjects);
	}

	/**
	 * Extract useful video information from array.
	 * @param  array  $information array with lines (string) from youtube-dl output
	 * @return array  Video information
	 */
	private function extract(array $information)
	{
		return $this->extractor->extract($information);
	}

	/**
	 * Create a filesettings object from factory.
	 * @param  array  $filesettingsData - array with arguments
	 * @return FileSettings object
	 */
	private function build(array $filesettingsData)
	{
		return $this->factory->make($filesettingsData);
	}

	/**
	 * add filesettingsobjects to persistencelayer
	 * @param array $fileSettingsObjects
	 */
	public function add(array $fileSettingsObjects) {
		if (is_array($fileSettingsObjects)){
			foreach ($fileSettingsObjects as $fileSettingsObject){
				$this->addOne($fileSettingsObject);
			}
		}else{
			$this->addOne($fileSettingsObjects);
		}
	}

	/**
	 * Get all filesettingsdata from persistencelayer. Retrieves all data and passes data to the factory
	 * @return array with filesettings objects
	 */
	public function findAll() {
		$allFileSettings = $this->persistence->retrieveAll();
		$fileSettingsObjects = array();
		foreach ($allFileSettings as $FileSettingsData){
			$fileSettingsObjects[] = $this->build($FileSettingsData);
		}
		return $fileSettingsObjects;
	}
	/**
	 * Lookup a filesettings dataset based on format code
	 * @param  string $formatCode
	 * @return array filesettings data
	 */
	public function findByFormatCode($formatCode) {
		return array_values(
			array_filter($this->findAll(), function ($fileSettingsObject) use ($formatCode) {
				return $fileSettingsObject->getFormatCode() == $formatCode;
			})
		);
	}

	/**
	 * Add one filesettings object to persitence layer. Convert object to data array and stores the array in the persistence layer.
	 * @param FileSettings $fileSettings
	 */
	private function addOne(FileSettings $fileSettings) {
		$this->persistence->persist(array(
			$fileSettings->getFormatCode(),
			$fileSettings->getFormat(),
			$fileSettings->getQuality(),
			$fileSettings->getMode()
		));
	}
}