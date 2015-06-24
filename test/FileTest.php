<?php
use \Mockery as m;
use App\Libraries\File;
use App\Libraries\FileManager;
class FileTest extends PHPUnit_Framework_TestCase {


  public function setUp()
  {
    $this->file = new File("http://www.youtube.com/watch?v=iV2ViNJFZC8", "1080", "mp4");
    $this->filemanager = m::mock('FileManager');
   
  }
  public function tearDown()
    {
        m::close();
    }
  public function testCorrectFullFilename()
  {
     $this->file->setFilename("video1");
     $this->assertEquals("video1.mp4", $this->file->getFullFilename());
  }
  
   public function testFileNotInFileSystem ()
  {
     
     // $this->filemanager->shouldReceive('fileExists')->with('video2.mp4')->times(1)->andReturn(false);

     // $this->file->setFilename("video2");
     // $this->file->checkFileName();
     // $this->assertEquals("video2.mp4", $this->file->getFullFilename());
  }
   public function testFileAlreadyExistsSoChangeName()
  {
      // $this->filemanager->shouldReceive('fileExists')->times(2)->andReturn(true, false);

      // $this->file->setFilename("video2");
      // $this->file->checkFileName();

      // $this->assertNotEquals("video2.mp4", $this->file->getFullFilename());
  }
}