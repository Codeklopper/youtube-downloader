<?php
use App\Libraries\FileSettingsFactory;

class FileSettingsFactoryTest extends PHPUnit_Framework_TestCase {

        function testAFilesettingssHasAllItsComposingParts() {
                $formatCode = 1;
                $filesettingsFormat = "mp4";
                $filesettingsQuality = "480";
                $filesettingsMode = "normal";

                $filesettingsData = array($formatCode, $filesettingsFormat,  $filesettingsQuality, $filesettingsMode);

                $filesettings = (new FileSettingsFactory())->make($filesettingsData);

                $this->assertEquals($formatCode, $filesettings->getFormatCode());
                $this->assertEquals($filesettingsFormat, $filesettings->getFormat());
                $this->assertEquals($filesettingsQuality, $filesettings->getQuality());
                $this->assertEquals($filesettingsMode, $filesettings->getMode());
        }
}
