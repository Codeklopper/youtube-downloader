<?php
use \Mockery as m;
use App\Libraries\VideoInformationExtractor;
use App\Libraries\FileSettingsFactory;

class ExtractorTest extends PHPUnit_Framework_TestCase {


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


	//     Array
	// (
	//     [0] => [youtube] Setting language
	//     [1] => [youtube] iV2ViNJFZC8: Downloading webpage
	//     [2] => [youtube] iV2ViNJFZC8: Downloading video info webpage
	//     [3] => [youtube] iV2ViNJFZC8: Extracting video information
	//     [4] => Available formats:
	//     [5] => 18 : mp4 [640x360]
	//     [6] => 43 : webm  [640x360]
	//     [7] => 5  : flv [400x240]
	//     [8] => 36 : 3gp [320x240]
	//     [9] => 17 : 3gp [176x144]
	//     [10] => 133 : mp4 [240p] (DASH Video)
	//     [11] => 160 : mp4 [192p] (DASH Video)
	//     [12] => 140 : m4a [128k] (DASH Audio)
	//     [13] => 139 : m4a [48k] (DASH Audio)
	// )

		$dataset = 'YTozNDp7aTo1O2E6ODp7aTowO2k6NTtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czo0OiIwLjI1IjtpOjY7czozOiJNUDMiO2k6NztzOjI6IjY0Ijt9aTo2O2E6ODp7aTowO2k6NjtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI3MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjgiO2k6NjtzOjM6Ik1QMyI7aTo3O3M6MjoiNjQiO31pOjEzO2E6ODp7aTowO2k6MTM7aToxO3M6MzoiM0dQIjtpOjI7czozOiJOL0EiO2k6MztzOjEzOiJNUEVHLTQgVmlzdWFsIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiJOL0EiO31pOjE3O2E6ODp7aTowO2k6MTc7aToxO3M6MzoiM0dQIjtpOjI7czo0OiIxNDRwIjtpOjM7czoxMzoiTVBFRy00IFZpc3VhbCI7aTo0O3M6NjoiU2ltcGxlIjtpOjU7czo0OiIwLjA1IjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjI0Ijt9aToxODthOjg6e2k6MDtpOjE4O2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjg6IkJhc2VsaW5lIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjIyO2E6ODp7aTowO2k6MjI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTkyIjt9aTozNDthOjg6e2k6MDtpOjM0O2k6MTtzOjM6IkZMViI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6Ik1haW4iO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxMjgiO31pOjM1O2E6ODp7aTowO2k6MzU7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6NToiMC44LTEiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aTozNjthOjg6e2k6MDtpOjM2O2k6MTtzOjM6IjNHUCI7aToyO3M6NDoiMjQwcCI7aTozO3M6MTM6Ik1QRUctNCBWaXN1YWwiO2k6NDtzOjY6IlNpbXBsZSI7aTo1O3M6NDoiMC4xNyI7aTo2O3M6MzoiQUFDIjtpOjc7czoyOiIzOCI7fWk6Mzc7YTo4OntpOjA7aTozNztpOjE7czozOiJNUDQiO2k6MjtzOjU6IjEwODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NzoiM+KAkzUuOSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxOTIiO31pOjM4O2E6ODp7aTowO2k6Mzg7aToxO3M6MzoiTVA0IjtpOjI7czo1OiIzMDcycCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6IkhpZ2giO2k6NTtzOjU6IjMuNS01IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE5MiI7fWk6NDM7YTo4OntpOjA7aTo0MztpOjE7czo0OiJXZWJNIjtpOjI7czo0OiIzNjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiMC41IjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6NDQ7YTo4OntpOjA7aTo0NDtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI0ODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MToiMSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxMjgiO31pOjQ1O2E6ODp7aTowO2k6NDU7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiNzIwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czozOiJOL0EiO2k6NTtzOjE6IjIiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aTo0NjthOjg6e2k6MDtpOjQ2O2k6MTtzOjQ6IldlYk0iO2k6MjtzOjU6IjEwODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjE5MiI7fWk6ODI7YTo4OntpOjA7aTo4MjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjI6Ijk2Ijt9aTo4MzthOjg6e2k6MDtpOjgzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjI6IjNEIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjg0O2E6ODp7aTowO2k6ODQ7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MjoiM0QiO2k6NTtzOjU6IjItMi45IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE1MiI7fWk6ODU7YTo4OntpOjA7aTo4NTtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjUyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTUyIjt9aToxMDA7YTo4OntpOjA7aToxMDA7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiMzYwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTAxO2E6ODp7aTowO2k6MTAxO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MjoiM0QiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO31pOjEwMjthOjg6e2k6MDtpOjEwMjtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI3MjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjI6IjNEIjtpOjU7czozOiJOL0EiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aToxMjA7YTo4OntpOjA7aToxMjA7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6OToiTWFpbkBMMy4xIjtpOjU7czoxOiIyIjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjEyOCI7fWk6MTMzO2E6ODp7aTowO2k6MTMzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6NzoiMC4yLTAuMyI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNDthOjg6e2k6MDtpOjEzNDtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjc6IjAuMy0wLjQiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxMzU7YTo4OntpOjA7aToxMzU7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czo1OiIwLjUtMSI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNjthOjg6e2k6MDtpOjEzNjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjEtMS41IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM3O2E6ODp7aTowO2k6MTM3O2k6MTtzOjM6Ik1QNCI7aToyO3M6NToiMTA4MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjItMi45IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM5O2E6ODp7aTowO2k6MTM5O2k6MTtzOjM6Ik1QNCI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjQ4Ijt9aToxNDA7YTo4OntpOjA7aToxNDA7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aToxNDE7YTo4OntpOjA7aToxNDE7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMjU2Ijt9aToxNjA7YTo4OntpOjA7aToxNjA7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIxNDRwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjEiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxNzE7YTo4OntpOjA7aToxNzE7aToxO3M6NDoiV2ViTSI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTcyO2E6ODp7aTowO2k6MTcyO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjM6Ik4vQSI7aTozO3M6NjoiTi9BWzRdIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO319';

		$data = 'YToxNDp7aTowO3M6MjY6Ilt5b3V0dWJlXSBTZXR0aW5nIGxhbmd1YWdlIjtpOjE7czo0MjoiW3lvdXR1YmVdIGlWMlZpTkpGWkM4OiBEb3dubG9hZGluZyB3ZWJwYWdlIjtpOjI7czo1MzoiW3lvdXR1YmVdIGlWMlZpTkpGWkM4OiBEb3dubG9hZGluZyB2aWRlbyBpbmZvIHdlYnBhZ2UiO2k6MztzOjUxOiJbeW91dHViZV0gaVYyVmlOSkZaQzg6IEV4dHJhY3RpbmcgdmlkZW8gaW5mb3JtYXRpb24iO2k6NDtzOjE4OiJBdmFpbGFibGUgZm9ybWF0czoiO2k6NTtzOjE4OiIxOAk6CW1wNAlbNjQweDM2MF0iO2k6NjtzOjE5OiI0Mwk6CXdlYm0JWzY0MHgzNjBdIjtpOjc7czoxNzoiNQk6CWZsdglbNDAweDI0MF0iO2k6ODtzOjE4OiIzNgk6CTNncAlbMzIweDI0MF0iO2k6OTtzOjE4OiIxNwk6CTNncAlbMTc2eDE0NF0iO2k6MTA7czoyOToiMTMzCToJbXA0CVsyNDBwXSAoREFTSCBWaWRlbykiO2k6MTE7czoyOToiMTYwCToJbXA0CVsxOTJwXSAoREFTSCBWaWRlbykiO2k6MTI7czoyOToiMTQwCToJbTRhCVsxMjhrXSAoREFTSCBBdWRpbykiO2k6MTM7czoyODoiMTM5CToJbTRhCVs0OGtdIChEQVNIIEF1ZGlvKSI7fQ==';
		$this->youtubeInformation = unserialize(base64_decode($data));
		$this->vimeoInformation = unserialize('a:8:{i:0;s:37:"[vimeo] 78029639: Downloading webpage";i:1;s:40:"[vimeo] 78029639: Extracting information";i:2;s:37:"[vimeo] 78029639: Downloading webpage";i:3;s:38:"[info] Available formats for 78029639:";i:4;s:41:"format code    extension resolution  note";i:5;s:44:"h264-mobile    mp4       480x270     (worst)";i:6;s:32:"h264-sd        mp4       640x360";i:7;s:43:"h264-hd        mp4       1280x720    (best)";}');


		$this->factory = m::mock('FileSettingsFactory');
		$this->extractor = new VideoInformationExtractor();
		$this->extractor->setDataset(unserialize(base64_decode($dataset)));


	}
	public function tearDown()
	{
		m::close();
	}

	public function testExtractSingleFormatCodeFromInformationline()
	{
		$result = $this->extractor->extractLine("18  : mp4 [360x640]");

		$this->assertEquals(18, $result);
	}

	public function testExtractSingleDataArrayFromFormatCode()
	{
		$result = $this->extractor->getFormatData(18);


		$this->assertEquals(18, $result[0]);
		$this->assertEquals("mp4", $result[1]);
		$this->assertEquals("360", $result[2]);
		$this->assertEquals("normal", $result[3]);
	}

	public function testIfFalseReturnedOnInvalidCode()
	{
		$result = $this->extractor->getFormatData(999);
		$this->assertFalse($result);
	}

	public function testGetCorrectMode()
	{
		$result1 = $this->extractor->getMode(133);
		$result2 = $this->extractor->getMode(5);
		$result3 = $this->extractor->getMode(140);

		$this->assertEquals('dash',$result1);
		$this->assertEquals('normal',$result2);
		$this->assertEquals('audio',$result3);

	}

	public function testExtractDataFromInformation()
	{


		

	//     [5] => 18 : mp4 [640x360]
	//     [6] => 43 : webm  [640x360]
	//     [7] => 5  : flv [400x240]
	//     [8] => 36 : 3gp [320x240]
	//     [9] => 17 : 3gp [176x144]
	//     [10] => 133 : mp4 [240p] (DASH Video)
	//     [11] => 160 : mp4 [192p] (DASH Video)
	//     [12] => 140 : m4a [128k] (DASH Audio)
	//     [13] => 139 : m4a [48k] (DASH Audio)

		// not sorted, we will do this in an other test
		$result = $this->extractor->extract($this->youtubeInformation, false);

		$this->assertEquals(18, $result[0][0]);
		$this->assertEquals("mp4", $result[0][1]);
		$this->assertEquals("360", $result[0][2]);
		$this->assertEquals("normal", $result[0][3]);

		$this->assertEquals(133, $result[5][0]);
		$this->assertEquals("mp4", $result[5][1]);
		$this->assertEquals("240", $result[5][2]);
		$this->assertEquals("dash", $result[5][3]);

		$this->assertEquals(139, $result[8][0]);
		$this->assertEquals("m4a", $result[8][1]);
		$this->assertEquals("48", $result[8][2]);
		$this->assertEquals("audio", $result[8][3]);
		$this->assertEquals(9, count($result));
	}

	public function testSortDataArray()
	{
		$settingsData = array(
			array(18,"mp4",  "360", "normal"),
			array(43,"webm", "360", "normal"),
			array(5,"flv",   "240", "normal"),
			array(36,"3gp",  "240", "normal"),
			array(88,"mp4",  "240", "normal"),
			array(17,"3gp",  "240", "normal"),
			array(133,"mp4", "240", "dash"),
			array(136,"mp4", "720", "dash"),
			array(160,"mp4", "192", "dash"),
			array(140,"m4a", "128", "audio"),
			array(139,"m4a", "48", "audio")
		);

		$result = $this->extractor->sortFormatDataArray($settingsData);


		$this->assertEquals(136, $result[0][0]);
		$this->assertEquals(18, $result[1][0]);
		$this->assertEquals(139, $result[10][0]);

	}
}