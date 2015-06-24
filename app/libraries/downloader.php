<?php
namespace App\Libraries;
use App\Libraries\Executer;
use App\Libraries\File;
use App\Libraries\FileSettingsRepository;

/**
 * Downloader class. Downloads a file and returns file location
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class Downloader
{
	/**
	 * File object with information about download url and how to store the downloaded file
	 * @var File
	 */
	public $file;
	/**
	 * Executer, executes given commands
	 * @var Executer
	 */
	public $executer;
	/**
	 * File settings manager. Create filesettingsobjects from youtube-dl output and stores results
	 * @var FileSettingsRepository
	 */
	public $fileSettingsRepository;
	/**
	 * Video service (Youtube, Vimeo etc.)
	 * @var string
	 */
	private $service;
	/**
	 * Information about what and how to download a file (video, dash, audio)
	 * @var string
	 */
	public $downloadMode;

	/**
	 * Command to execute
	 * @var string
	 */
	private $command = "youtube-dl -F";

	/**
	 * Initialize downloader, accepts a file, not required (also possible via the setFile method)
	 * @param File $file File object
	 */
	public function __construct($file = NULL){
		$this->setFile($file);
	}

	/**
	 * Set File object. Downloader will use this object as subject
	 * @param File $file
	 */
	public function setFile($file)
	{
		if(is_object($file))
		{
			$file->checkFileName();
			$this->file = $file;
		}
	}

	/**
	 * Attach an executer to the downloader
	 * @param  Executer $executer
	 * @return void
	 */
	public function attachExecuter($executer){
		$this->executer = $executer;
	}

	/**
	 * Attach filesettings repository to the downloader
	 * @param  FileSettingsRepository $fileSettingsRepository
	 * @return void
	 */
	public function attachfileSettingsRepository($fileSettingsRepository){
		$this->fileSettingsRepository = $fileSettingsRepository;
	}

	/**
	 * Get information about the to download video. 
	 * Executes a youtube-dl command that will return available formats and qualities
	 * @return void
	 */
	public function getVideoInformation()
	{
		
		$information = $this->executer->execute('youtube-dl -F '.$this->file->url);
		if($information)
		{
			return $information;
		}else{
			return false;
		}
	}

	/**
	 * Use regular expression to find out which service is used
	 * @param  string $informationString
	 * @return string extracted service
	 */
	public function extractService($informationString)
	{

		preg_match("@\[(.*)\]@", $informationString[0], $matches);
		
		return ($matches[1] ? $matches[1] : false);
	}
	/**
	 * Create the filesettings in the filesettings repository
	 * Uses the information gained from the youtube-dl output in the getVideoInformation method
	 * @param array $information array with output lines (string) from youtube-dl. Contains information about the video
	 */
	public function setFileSettings($information)
	{
		return $this->fileSettingsRepository->setInformation($information, ($this->file->format == "mp3" ? "mp4" : $this->file->format)  , $this->file->quality);
	}

	/**
	 * Get next available filesettings. Returns a FileSettingsObject if available, otherwise returns false
	 * @return mixed false if no more settings are available, otherwise return found FileSettingsObject
	 */
	public function getNextFileSettings()
	{
		$settings = $this->fileSettingsRepository->getNextFileSettings();
	
		return $settings;
	}

	/**
	 * Get next available audio filesettings. Returns a FileSettingsObject if available, otherwise returns false
	 * @return mixed false if no more settings are available, otherwise return found FileSettingsObject
	 */
	public function getNextAudioSettings()
	{
		$settings = $this->fileSettingsRepository->getAudioFileSettings();
	
		return $settings;
	}

	/**
	 * Set download mode
	 * @param FileSettings $fileSettings
	 */
	public function setDownloadMode($fileSettings)
	{
		
		if($this->file->format == "mp3")
		{
			$this->downloadMode = "extractAudio";
		}else{
			if(is_string($fileSettings))
			{
				$this->downloadMode = $fileSettings;
			}else{
				$this->downloadMode = $fileSettings->getMode();
			}
		}		
	}

	/**
	 * Get download mode
	 * @return string
	 */
	public function getDownloadMode()
	{
		return $this->downloadMode;
	}

	/**
	 * Get command 
	 * @param  FileSettings $fileSettings
	 * @return string command
	 */
	public function getCommand($fileSettings)
	{
		if($this->downloadMode == "extractAudio")
		{	
			return 'youtube-dl --no-mtime   --extract-audio --audio-format '.$this->file->format.' '.$this->file->url.' -o "'.$this->file->getFileName().'.%(ext)s"';
		}
		if($this->downloadMode == "dash")
		{	
			return 'youtube-dl --no-mtime  --retries 20 -f '.$fileSettings->getFormatCode().' '.$this->file->url.' -o '.$this->file->getTempFileName();
		}
		if($this->downloadMode == "best")
		{
			return 'youtube-dl --no-mtime  '.$this->file->url.' -o "'.$this->file->getFileName().'.%(ext)s"';
		}
		return 'youtube-dl --no-mtime  -f '.$fileSettings->getFormatCode().' '.$this->file->url.' -o '.$this->file->getFullFileName();

	}

	/**
	 * Download the file
	 * Checks if the filename is valid (if not, the file will set a new filename and checks if it is valid. Repeats this till it has a valid filename)
	 * Based on the download mode the downloader will provide a command to the executer. The executer will start the download and return the result
	 * If download mode is dash, a video file and a audio file will be download seperately. Afterwards it will merge both files with ffmpeg and remove the temporary files
	 * If an error occures the downloader will return false
	 * @param  FileSettings $fileSettings the FileSettings object with download parameters for youtube-dl
	 * @return bool
	 */
	public function downloadFile($fileSettings)
	{
		$command = $this->getCommand($fileSettings);

		$unsafe = false;
		if($this->downloadMode != "dash")
		{	 
			$this->executer->unsafe = true;

			//$command = 'youtube-dl --no-mtime  -f '.$fileSettings->getFormatCode().' '.$this->file->url.' -o '.$this->file->getFullFileName();
						 
			return $this->executer->execute($command);
			
		}
		if($this->downloadMode == "dash")
		{	
			
			$command_video = $command.".vid";

			$vid_result = $this->executer->execute($command_video);
		
			if($vid_result)
			{	
			
				$aud_result = false;
				$settings = true;
				while(!$aud_result && $settings)
				{
					$settings = $this->getNextAudioSettings();
					$command_audio = $this->getCommand($settings);
					$command_audio = $command_audio.'.aud';
					
					$aud_result = $this->executer->execute($command_audio);
				}
				if($aud_result && $vid_result){
					
					$merge_command ='ffmpeg -i '.$this->file->getTempFileName().'.vid -i '.$this->file->getTempFileName().'.aud -c copy tmp/'.$this->file->getFullFileName().'';
					$merge_result = $this->executer->execute($merge_command); 

					$this->executer->execute('rm '.$this->file->getTempFileName().'.vid');
					$this->executer->execute('rm '.$this->file->getTempFileName().'.aud');
			
					return true;;
					// test if file exists
				}			
			}else{
			
				return false;
			}
		}
	}

	/** 
	 * Download command. Downloader will gather all required information.
	 * It will try to download the file based on the FileSettings information
	 * If the download fails, it will get the next available FileSettings object and try again
	 * Method echos the result
	 * @return void
	 */
	public function download()
	{
		$information = $this->getVideoInformation();

		$service = $this->extractService($information);
		
		if($service != "youtube")
		{	

			$this->setDownloadMode('best');
			
			$result = $this->downloadFile(null);
		}else{
			$this->setFileSettings($information);
			$result = false;
			$settings = true;
			while(!$result && $settings)
			{
				$settings = $this->getNextFileSettings();
				if(!$settings)
				{
					$this->sendErrorMail( $this->file->url);
					return false;
					break;
				}
				$this->setDownloadMode($settings);
				$result = $this->downloadFile($settings);
			}
		}
		
		if($result)
		{

			$this->moveFile('./downloads/', $this->file->getFullFileName());
			return true;
		}else{
			$this->sendErrorMail( $this->file->url);
			return false;
		}
		//echo "<br /><br />" . $this->file->getFullFileName();
		
	}

	public function moveFile($path, $filename)
	{
		rename(('tmp/'.$filename), ($path.$filename));
	}

	public function sendErrorMail($file)
	{


		$subject = 'youtube-dl fail';
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: Youtube Downloader <youtube@samhoud.com>";
		$headers[] = "Reply-To: Recipient Name <b.vliet@samhoud.com>";
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		$message = "URL:" . $file. " <br /><br />debug message: <br /> " . $this->executer->message;

		print_r($message);
		die();
		mail("b.vliet@samhoud.com", $subject , $message, implode("\r\n", $headers));
	}

}