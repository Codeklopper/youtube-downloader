<?php
use \Mockery as m;
use App\Libraries\Downloader;
use App\Libraries\Executer;
use App\Libraries\File;
use App\Libraries\FileSettings;
use App\Libraries\VideoInformationExtractor;
class DownloaderTest extends PHPUnit_Framework_TestCase {


	public function setUp()
	{


		// Youtube File
		$this->youtubeUrl = "http://www.youtube.com/watch?v=8LzaZ9zXJOc&list=UU-W7zrMCQlDTmCCW8Ff-fgw";
		$this->expectedOutput = unserialize('a:15:{i:0;s:26:"[youtube] Setting language";i:1;s:48:"[youtube] iV2ViNJFZC8: Downloading video webpage";i:2;s:53:"[youtube] iV2ViNJFZC8: Downloading video info webpage";i:3;s:51:"[youtube] iV2ViNJFZC8: Extracting video information";i:4;s:18:"Available formats:";i:5;s:18:"18	:	mp4	[360x640]";i:6;s:19:"43	:	webm	[360x640]";i:7;s:17:"5	:	flv	[240x400]";i:8;s:18:"36	:	3gp	[240x320]";i:9;s:18:"17	:	3gp	[144x176]";i:10;s:29:"133	:	mp4	[240p] (DASH Video)";i:11;s:29:"160	:	mp4	[192p] (DASH Video)";i:12;s:29:"141	:	m4a	[256k] (DASH Audio)";i:13;s:29:"140	:	m4a	[128k] (DASH Audio)";i:14;s:28:"139	:	m4a	[48k] (DASH Audio)";}');
		$this->youtubeFile = new File($this->youtubeUrl, "1080", "mp4");
		
		// Vimeo File
		$this->vimeoUrl = "http://vimeo.com/78029639";
		$this->vimeoExpectedOutput = unserialize('a:8:{i:0;s:37:"[vimeo] 78029639: Downloading webpage";i:1;s:40:"[vimeo] 78029639: Extracting information";i:2;s:37:"[vimeo] 78029639: Downloading webpage";i:3;s:38:"[info] Available formats for 78029639:";i:4;s:41:"format code    extension resolution  note";i:5;s:44:"h264-mobile    mp4       480x270     (worst)";i:6;s:32:"h264-sd        mp4       640x360";i:7;s:43:"h264-hd        mp4       1280x720    (best)";}');
		$this->vimeoFile = new File($this->vimeoUrl, "1080", "mp4");



		$this->command = "youtube-dl -F ";

		$this->youtubeFile->setFilename('video1');
		//$mockedYoutubeFile = m::mock($youtubeFile);

		$this->vimeoFile->setFilename('video1');
		//$mockedVimeoFile = m::mock($youtubeFile);


		$this->downloader = new Downloader();
		$this->downloader->setFile($this->youtubeFile);

		$this->executer = m::mock('Executer');
		$this->downloader->attachExecuter($this->executer);

		$this->fileSettingsRepository = m::mock('FileSettingsRepository');
		$this->downloader->attachfileSettingsRepository($this->fileSettingsRepository);	


		$this->mockedFileSettings =  m::mock('FileSettings');


	}
	public function tearDown()
	{
		m::close();
	}

	public function getVideoInformationWithoutErrors()
	{
		//arrange
     	$this->executer->shouldReceive('execute')->with($this->command.$this->youtubeUrl)->andReturn($this->expectedOutput);
		
		//act
		$information = $this->downloader->getVideoInformation();

		//assert
		$this->assertInternalType('array', $information);
		$this->assertGreaterThan(5, count($information));
	}


	public function testDetectYoutubeInUrlAndVideoInformation()
	{
		//arrange
     	$this->executer->shouldReceive('execute')->with($this->command.$this->youtubeUrl)->andReturn($this->expectedOutput);
		$information = $this->downloader->getVideoInformation();

		//act
		$service = $this->downloader->extractService($information[0]);

		//assert
		$this->assertEquals('youtube', $service);
	}

	public function getVideoInformationFromInvalidUrlAndReturnError()
	{
		//arrange
     	$this->executer->shouldReceive('execute')->with($this->command .'http://youtube.com/novalidvideourl')->andReturn(false);
		
		//act
		$information = $this->downloader->getVideoInformation();

		//assert
		$this->assertSame(false, $information);
	
	}

	public function testVimeoUrl()
	{
		//arrange
		$this->downloader->setFile($this->vimeoFile);
     	$this->executer->shouldReceive('execute')->with($this->command.$this->vimeoUrl)->andReturn($this->vimeoExpectedOutput);
		$information = $this->downloader->getVideoInformation();

		//act
		$service = $this->downloader->extractService($information[0]);

		//assert
		$this->assertEquals('vimeo', $service);
		$this->downloader->setFile($this->youtubeFile);
	}

	public function testSetPossibleFileFormats()
	{
		//arrange
     	$this->executer->shouldReceive('execute')->with($this->command.$this->youtubeUrl)->andReturn($this->expectedOutput);
		$information = $this->downloader->getVideoInformation();

		
		//act
		$this->fileSettingsRepository->shouldReceive('setInformation')->with($information, $this->downloader->file->format, $this->downloader->file->quality);
		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);
		$this->downloader->setFileSettings($information);

		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(18);
		$this->mockedFileSettings->shouldReceive('getFormat')->andReturn('mp4');

		$fileSettings = $this->downloader->getNextFileSettings();
		//assert
		$this->assertInstanceOf('FileSettings', $fileSettings);
		$this->assertEquals(18, $fileSettings->getFormatCode());
		$this->assertEquals("mp4", $fileSettings->getFormat());
	}

	// public function testSetPossibleQualities()
	// {
	// 	//arrange
 	//     	$this->executer->shouldReceive('execute')->with($this->youtubeUrl)->andReturn($this->expectedOutput);
	// 	$information = $this->downloader->getVideoInformation();

	// 	//act
	// 	$this->downloader->setPossibleQualities($information);

	// 	//assert
	// 	$this->assertInternalType('array', $this->downloader->qualities);
	// 	$this->assertEquals(10, count($this->downloader->qualities));
	// 	$this->assertEquals(133, $this->downloader->fileFormats[5]);
	// }


	public function setDownloadModeToNormal()
	{
		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);
		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(18);
		$fileSettings = $this->downloader->getNextFileSettings();
		$this->downloader->setDownloadMode($fileSettings);

		$this->assertEquals('normal', $this->downloader->getDownloadMode());
	}

	public function setDownloadModeToDash()
	{
		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);
		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(137);
		$fileSettings = $this->downloader->getNextFileSettings();
		$this->downloader->setDownloadMode($fileSettings);
		$this->assertEquals('dash', $this->downloader->getDownloadMode());
	}

	public function setDownloadModeToAudio()
	{
		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);
		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(141);
		$fileSettings = $this->downloader->getNextFileSettings();
		$this->downloader->setDownloadMode($fileSettings);
		$this->assertEquals('audio', $this->downloader->getDownloadMode());
	}


	public function testPreparedDownloadCorrectly()
	{
		$expectedCommand = 'youtube-dl --no-mtime  -f 18 '.$this->youtubeUrl.' -o '.$this->downloader->file->getFullFileName().'';

		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);
		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(18);

		$command = $this->downloader->getCommand($this->mockedFileSettings);

		$this->assertEquals($expectedCommand, $command);

	}

	public function testExecuteDownloadWithoutErrors()
	{

		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);

		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(18);
		$this->mockedFileSettings->shouldReceive('getMode')->andReturn('normal');

		$this->executer->shouldReceive('execute')->andReturn(true);
		$this->downloader->setDownloadMode($this->mockedFileSettings);
		$command = $this->downloader->getCommand($this->mockedFileSettings);

		$result = $this->downloader->downloadFile($command);

		$this->assertNotEquals(false, $result);

	}

	public function testExecuteDownloadWithInvalidCodeAndReturnError()
	{
		$this->fileSettingsRepository->shouldReceive('getNextFileSettings')->andReturn($this->mockedFileSettings);

		$this->mockedFileSettings->shouldReceive('getFormatCode')->andReturn(137);
		$this->mockedFileSettings->shouldReceive('getMode')->andReturn('normal');

			$this->downloader->setDownloadMode($this->mockedFileSettings);
		$this->executer->shouldReceive('execute')->andReturn(false);

		$command = $this->downloader->getCommand($this->mockedFileSettings);
		$result = $this->downloader->downloadFile($command);
		$this->assertSame(false, $result);
	}

	public function testRaiseErrorIfIncorrectPrepared()
	{

	}

	

	public function testDownloadOfFileWith1080()
	{

	}

	public function testDownloadOfFileWith480()
	{

	}

	public function testDownloadOfFileWithBestQualityAvailable()
	{

	}

	public function testDownloadOfFileWith720ButOnly480Available()
	{

	}

	public function testDownloadAudioFile()
	{

	}

	public function testUnsuccessfulFileDownload()
	{

	}

	public function testConvertFileToFlv()
	{
		
	}

	public function testReturnCorrectDownloadUrl()
	{
		
	}

	public function testFileAvailableOnFilesystem()
	{
		
	}
	
}	