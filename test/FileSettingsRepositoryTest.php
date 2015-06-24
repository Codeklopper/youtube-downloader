<?php
use \Mockery as m;
use App\Libraries\FileSettingsFactory;
use App\Libraries\FileSettingsRepository;
use App\Libraries\VideoInformationExtractor;
use App\Libraries\Factory;
use App\Libraries\Persistence;
class FileSettingsRepositoryTest extends PHPUnit_Framework_TestCase {


  public function setUp()
  {

    // information, format, quality
    // lijst met qualities
    // pointer op 0
    // geef kwaliteit terug eerste keer (pointer = 1)
    // geef nog een keer kwaliteit terug (pointer = 2)
    // geen kwaliteit meer over, geef false terug


    // formatcode
    // format
    // quality

    $data = 'YToxNDp7aTowO3M6MjY6Ilt5b3V0dWJlXSBTZXR0aW5nIGxhbmd1YWdlIjtpOjE7czo0MjoiW3lvdXR1YmVdIGlWMlZpTkpGWkM4OiBEb3dubG9hZGluZyB3ZWJwYWdlIjtpOjI7czo1MzoiW3lvdXR1YmVdIGlWMlZpTkpGWkM4OiBEb3dubG9hZGluZyB2aWRlbyBpbmZvIHdlYnBhZ2UiO2k6MztzOjUxOiJbeW91dHViZV0gaVYyVmlOSkZaQzg6IEV4dHJhY3RpbmcgdmlkZW8gaW5mb3JtYXRpb24iO2k6NDtzOjE4OiJBdmFpbGFibGUgZm9ybWF0czoiO2k6NTtzOjE4OiIxOAk6CW1wNAlbNjQweDM2MF0iO2k6NjtzOjE5OiI0Mwk6CXdlYm0JWzY0MHgzNjBdIjtpOjc7czoxNzoiNQk6CWZsdglbNDAweDI0MF0iO2k6ODtzOjE4OiIzNgk6CTNncAlbMzIweDI0MF0iO2k6OTtzOjE4OiIxNwk6CTNncAlbMTc2eDE0NF0iO2k6MTA7czoyOToiMTMzCToJbXA0CVsyNDBwXSAoREFTSCBWaWRlbykiO2k6MTE7czoyOToiMTYwCToJbXA0CVsxOTJwXSAoREFTSCBWaWRlbykiO2k6MTI7czoyOToiMTQwCToJbTRhCVsxMjhrXSAoREFTSCBBdWRpbykiO2k6MTM7czoyODoiMTM5CToJbTRhCVs0OGtdIChEQVNIIEF1ZGlvKSI7fQ==';
    $this->youtubeInformation = unserialize(base64_decode($data));
    $this->vimeoInformation = unserialize('a:8:{i:0;s:37:"[vimeo] 78029639: Downloading webpage";i:1;s:40:"[vimeo] 78029639: Extracting information";i:2;s:37:"[vimeo] 78029639: Downloading webpage";i:3;s:38:"[info] Available formats for 78029639:";i:4;s:41:"format code    extension resolution  note";i:5;s:44:"h264-mobile    mp4       480x270     (worst)";i:6;s:32:"h264-sd        mp4       640x360";i:7;s:43:"h264-hd        mp4       1280x720    (best)";}');
    
    $this->realFactory = new FileSettingsFactory();

    $this->settingsData = array(
      array(18,"mp4",  "360", "normal"),
      array(43,"webm", "360", "normal"),
      array(5,"flv",   "240", "normal"),
      array(160,"mp4", "192", "dash"),
      array(140,"m4a", "128", "audio")
    );

     $this->objects = array();
      $this->objects[] = $this->realFactory->make($this->settingsData[0]);
      $this->objects[] = $this->realFactory->make($this->settingsData[1]);
      $this->objects[] = $this->realFactory->make($this->settingsData[2]);
      $this->objects[] = $this->realFactory->make($this->settingsData[3]);
      $this->objects[] = $this->realFactory->make($this->settingsData[4]);


    $this->fileSettingsRepository = new FileSettingsRepository;
    
    $this->factory = m::mock('App\Libraries\Factory');
    $this->extractor = m::mock('App\Libraries\VideoInformationExtractor');
    $this->fileSettingsRepository->attachFactory($this->factory);
    $this->fileSettingsRepository->attachExtractor($this->extractor);
    $this->persistanceGateway = \Mockery::mock('App\Libraries\Persistence');
    $this->fileSettingsRepository->attachPersistenceGateway($this->persistanceGateway);
   
  }
  public function tearDown()
  {
      m::close();
  }

  public function testSetInformation()
  {

       for($i = 0; $i <= 4; $i++)
      {
        $this->factory->shouldReceive('make')->with($this->settingsData[$i])->andReturn($this->objects[$i]);
      }

      $this->extractor->shouldReceive('extract')->with($this->youtubeInformation)->andReturn($this->settingsData);
      $this->persistanceGateway->shouldReceive('persist')->times(5);
      $this->persistanceGateway->shouldReceive('retrieveAll')->once()->andReturn($this->settingsData);


      $this->fileSettingsRepository->setInformation($this->youtubeInformation, "mp4", "360");
      
      $this->fileSettingsRepository->save();

      $this->assertEquals($this->objects,  $this->fileSettingsRepository->findAll());
  }


  public function testSetPointerAndResetPointertoFalse()
  {
    $this->fileSettingsRepository->setPointer(3);
    $this->assertEquals(3,  $this->fileSettingsRepository->getPointer());
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);
    $this->assertFalse($this->fileSettingsRepository->getPointer());
  }

  public function testFindSettingsKeyAndSetPointerToCorrectValue()
  {
    
    $this->setData();

    $key = $this->fileSettingsRepository->findSettingsKey(360, "mp4", array("normal", "dash"));
    $this->assertEquals(0, $key);
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);
    $key = $this->fileSettingsRepository->findSettingsKey(192, "mp4", array("normal", "dash"));
    $this->assertEquals(3, $key);
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $key = $this->fileSettingsRepository->findSettingsKey(200, "mp4", array("normal", "dash"));
    $this->assertEquals(3, $key);

    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);



    $key = $this->fileSettingsRepository->findSettingsKey(720, "mp4", array("normal", "dash"));
    $this->assertEquals(0, $key);
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);
    
    $key = $this->fileSettingsRepository->findSettingsKey(191, "mp4", array("normal", "dash"));
    $this->assertEquals(3, $key);
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $this->fileSettingsRepository->setAutoGetHighestQuality(false);
    $key = $this->fileSettingsRepository->findSettingsKey(720, "mp4", array("normal", "dash"));
    $this->assertFalse($key);
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $this->fileSettingsRepository->setAutoGetLowestQuality(false);
    $key = $this->fileSettingsRepository->findSettingsKey(191, "mp4", array("normal", "dash"));
    $this->assertFalse($key);
  }

  public function setData()
  {

      for($i = 0; $i <= 4; $i++)
      {
        $this->factory->shouldReceive('make')->with($this->settingsData[$i])->andReturn($this->objects[$i]);
      }

      $this->extractor->shouldReceive('extract')->with($this->youtubeInformation)->andReturn($this->settingsData);

      $this->fileSettingsRepository->setInformation($this->youtubeInformation, "mp4", "360");
  }

  public function testFindSettingsForAudio()
  {

    $this->setData();
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);
    $key = $this->fileSettingsRepository->findSettingsKey(1080, "m4a", array("audio"));
    $this->assertEquals(4, $key);
  }

  public function testGetNextKey()
  {

    $this->setData();
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $key = $this->fileSettingsRepository->findSettingsKey(1080, "mp4", array("normal", "dash"));

    $this->fileSettingsRepository->setPointer($key);
    $nextKey = $this->fileSettingsRepository->findSettingsKey(1080, "mp4", array("normal", "dash"));
    $this->assertEquals(3, $nextKey);
  }

  public function testGetNextFileSettingsFromFirstRequest()
  {
    $this->setData();
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $fileSettings = $this->fileSettingsRepository->getNextFileSettings();

    $this->assertEquals($this->objects[0], $fileSettings);
    $this->assertFalse($this->fileSettingsRepository->canDownloadHighestQuality());
   }

   public function testGetNextFileSettingsSecondTime()
   {
    $this->setData();
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $this->fileSettingsRepository->getNextFileSettings( array('normal', 'dash'));
    $fileSettings = $this->fileSettingsRepository->getNextFileSettings( array('normal', 'dash'));
    $this->assertEquals($this->objects[3], $fileSettings);

   }

  public function testGetNextFileSettingsWithNoResults()
  {
    $this->setData();
    $this->fileSettingsRepository->resetPointer();
    $this->fileSettingsRepository->setAutoGetHighestQuality(true);

    $a = $this->fileSettingsRepository->getNextFileSettings(array('normal', 'dash'));

    $b = $this->fileSettingsRepository->getNextFileSettings(array('normal', 'dash'));

    $fileSettings = $this->fileSettingsRepository->getNextFileSettings(array('normal', 'dash'), true);
    $this->assertFalse($fileSettings);
  }
  
}