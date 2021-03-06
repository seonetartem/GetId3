<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class AudioVideoTest extends \PHPUnit_Framework_TestCase
{
    protected static $quicktimeFile;
    protected static $flvFile;
    protected static $realFile;
    protected static $asfFile;
    protected static $class;

    protected function setUp()
    {
        self::$quicktimeFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'sample_iTunes.mov';
        self::$flvFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'flvsample.flv';
        self::$realFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'realsample.rm';
        self::$asfFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'asfsample.asf';
        self::$class = 'GetId3\\GetId3Core';
    }

    public function testClass()
    {
        if (!class_exists(self::$class)) {
            $this->markTestSkipped(self::$class . ' is not available.');
        }
        $this->assertTrue(class_exists(self::$class));
        $this->assertClassHasAttribute('option_md5_data', self::$class);
        $this->assertClassHasAttribute('option_md5_data_source', self::$class);
        $this->assertClassHasAttribute('encoding', self::$class);
        $rc = new \ReflectionClass(self::$class);
        $this->assertTrue($rc->hasMethod('analyze'));
        $rm = new \ReflectionMethod(self::$class, 'analyze');
        $this->assertTrue($rm->isPublic());
    }

    public function testQuicktimeFile()
    {
        $this->assertFileExists(self::$quicktimeFile);
        $this->assertTrue(is_readable(self::$quicktimeFile));
    }

    /**
     * @depends testClass
     * @depends testQuicktimeFile
     */
    public function testReadQuicktime()
    {
        $getId3 = new GetId3Core();
        $properties = $getId3
            ->setOptionMD5Data(true)
            ->setOptionMD5DataSource(true)
            ->setEncoding('UTF-8')
            ->analyze(self::$quicktimeFile)
            ;

        $this->assertArrayNotHasKey('error', $properties);
        $this->assertArrayHasKey('mime_type', $properties);
        $this->assertEquals('video/quicktime', $properties['mime_type']);
        $this->assertArrayHasKey('encoding', $properties);
        $this->assertEquals('UTF-8', $properties['encoding']);
        $this->assertArrayHasKey('filesize', $properties);
        $this->assertSame(3284257, $properties['filesize']);
        $this->assertArrayHasKey('fileformat', $properties);
        $this->assertEquals('quicktime', $properties['fileformat']);
        $this->assertArrayHasKey('audio', $properties);
        $this->assertArrayHasKey('dataformat', $properties['audio']);
        $this->assertEquals('mp4', $properties['audio']['dataformat']);
        $this->assertArrayHasKey('video', $properties);
        $this->assertArrayHasKey('dataformat', $properties['video']);
        $this->assertEquals('mpeg4', $properties['video']['dataformat']);
    }

    public function testFlvFile()
    {
        $this->assertFileExists(self::$flvFile);
        $this->assertTrue(is_readable(self::$flvFile));
    }

    /**
     * @depends testClass
     * @depends testFlvFile
     */
    public function testReadFlv()
    {
        $getId3 = new GetId3Core();
        $properties = $getId3
            ->setOptionMD5Data(true)
            ->setOptionMD5DataSource(true)
            ->setEncoding('UTF-8')
            ->analyze(self::$flvFile)
            ;

        $this->assertArrayNotHasKey('error', $properties);
        $this->assertArrayNotHasKey('warning', $properties);
        $this->assertArrayHasKey('mime_type', $properties);
        $this->assertEquals('video/x-flv', $properties['mime_type']);
        $this->assertArrayHasKey('encoding', $properties);
        $this->assertEquals('UTF-8', $properties['encoding']);
        $this->assertArrayHasKey('filesize', $properties);
        $this->assertSame(88722, $properties['filesize']);
        $this->assertArrayHasKey('fileformat', $properties);
        $this->assertEquals('flv', $properties['fileformat']);
        $this->assertArrayHasKey('audio', $properties);
        $this->assertArrayHasKey('dataformat', $properties['audio']);
        $this->assertEquals('flv', $properties['audio']['dataformat']);
        $this->assertArrayHasKey('video', $properties);
        $this->assertArrayHasKey('dataformat', $properties['video']);
        $this->assertEquals('flv', $properties['video']['dataformat']);
        $this->assertArrayHasKey('flv', $properties);
        $this->assertArrayHasKey('framecount', $properties['flv']);
        $this->assertArrayHasKey('total', $properties['flv']['framecount']);
        $this->assertSame(236, $properties['flv']['framecount']['total']);
        $this->assertArrayHasKey('bitrate', $properties);
        $this->assertArrayHasKey('playtime_seconds', $properties);
    }

    public function testRealFile()
    {
        $this->assertFileExists(self::$realFile);
        $this->assertTrue(is_readable(self::$realFile));
    }

    /**
     * @depends testClass
     * @depends testRealFile
     */
    public function testReadReal()
    {
        $getId3 = new GetId3Core();
        $properties = $getId3
            ->setOptionMD5Data(true)
            ->setOptionMD5DataSource(true)
            ->setEncoding('UTF-8')
            ->analyze(self::$realFile)
            ;

        $this->assertArrayNotHasKey('error', $properties);
        $this->assertArrayNotHasKey('warning', $properties);
        $this->assertArrayHasKey('mime_type', $properties);
        $this->assertEquals('audio/x-realaudio', $properties['mime_type']);
        $this->assertArrayHasKey('encoding', $properties);
        $this->assertEquals('UTF-8', $properties['encoding']);
        $this->assertArrayHasKey('filesize', $properties);
        $this->assertSame(236939, $properties['filesize']);
        $this->assertArrayHasKey('fileformat', $properties);
        $this->assertEquals('real', $properties['fileformat']);
        $this->assertArrayHasKey('video', $properties);
        $this->assertArrayHasKey('dataformat', $properties['video']);
        $this->assertEquals('real', $properties['video']['dataformat']);
        $this->assertArrayHasKey('real', $properties);
        $this->assertArrayHasKey('frame_rate', $properties['video']);
        $this->assertEquals(15, $properties['video']['frame_rate']);
        $this->assertArrayHasKey('bitrate', $properties);
        $this->assertArrayHasKey('playtime_seconds', $properties);
        $this->assertArrayHasKey('resolution_x', $properties['video']);
        $this->assertSame(528, $properties['video']['resolution_x']);
        $this->assertArrayHasKey('resolution_y', $properties['video']);
        $this->assertSame(352, $properties['video']['resolution_y']);
    }

    public function testAsfFile()
    {
        $this->assertFileExists(self::$asfFile);
        $this->assertTrue(is_readable(self::$asfFile));
    }

    /**
     * @depends testClass
     * @depends testAsfFile
     */
    public function testReadAsf()
    {
        $getId3 = new GetId3Core();
        $properties = $getId3
            ->setOptionMD5Data(true)
            ->setOptionMD5DataSource(true)
            ->setEncoding('UTF-8')
            ->analyze(self::$asfFile)
            ;

        $this->assertArrayNotHasKey('error', $properties);
        $this->assertArrayNotHasKey('warning', $properties);
        $this->assertArrayHasKey('mime_type', $properties);
        $this->assertEquals('video/x-ms-asf', $properties['mime_type']);
        $this->assertArrayHasKey('encoding', $properties);
        $this->assertEquals('UTF-8', $properties['encoding']);
        $this->assertArrayHasKey('filesize', $properties);
        $this->assertSame(269671, $properties['filesize']);
        $this->assertArrayHasKey('fileformat', $properties);
        $this->assertEquals('asf', $properties['fileformat']);
        $this->assertArrayHasKey('audio', $properties);
        $this->assertArrayHasKey('dataformat', $properties['audio']);
        $this->assertEquals('wma', $properties['audio']['dataformat']);
        $this->assertArrayHasKey('encoder', $properties['audio']);
        $this->assertEquals('Windows Media Audio V2', $properties['audio']['encoder']);
        $this->assertArrayHasKey('video', $properties);
        $this->assertArrayHasKey('dataformat', $properties['video']);
        $this->assertEquals('asf', $properties['video']['dataformat']);
        $this->assertArrayHasKey('encoder', $properties['video']);
        $this->assertEquals('Microsoft MPEG-4 Video Codec V3', $properties['video']['encoder']);
        $this->assertArrayHasKey('encoder_options', $properties['audio']);
        $this->assertEquals('32 kbps, 22 kHz, stereo', $properties['audio']['encoder_options']);
        $this->assertArrayHasKey('compression_ratio', $properties['audio']);
        $this->assertEquals(0.045408163265306, $properties['audio']['compression_ratio']);
        $this->assertArrayHasKey('asf', $properties);
        $this->assertArrayHasKey('bitrate', $properties['video']);
        $this->assertEquals(237907, $properties['video']['bitrate']);
        $this->assertArrayHasKey('bitrate', $properties);
        $this->assertArrayHasKey('playtime_seconds', $properties);
        $this->assertArrayHasKey('resolution_x', $properties['video']);
        $this->assertSame(320, $properties['video']['resolution_x']);
        $this->assertArrayHasKey('resolution_y', $properties['video']);
        $this->assertSame(240, $properties['video']['resolution_y']);
    }
}
