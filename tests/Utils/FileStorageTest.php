<?php
use PHPUnit\Framework\TestCase;
use App\Utils\FileStorage;

class FileStorageTest extends TestCase
{
    private $testFilePath;

    protected function setUp(): void
    {
        $this->testFilePath = __DIR__ . '/test_storage.json';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testInitializationCreatesFile()
    {
        $storage = new FileStorage($this->testFilePath);
        $this->assertFileExists($this->testFilePath);

        $data = file_get_contents($this->testFilePath);
        $this->assertEquals('[]', $data);
    }

    public function testReadReturnsEmptyArrayForEmptyFile()
    {
        file_put_contents($this->testFilePath, '[]');
        $storage = new FileStorage($this->testFilePath);

        $data = $storage->read();
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function testWriteOverwritesFile()
    {
        $storage = new FileStorage($this->testFilePath);

        $data = ['key' => 'value'];
        $storage->write($data);

        $contents = json_decode(file_get_contents($this->testFilePath), true);
        $this->assertEquals($data, $contents);
    }

    public function testAppendMergesData()
    {
        file_put_contents($this->testFilePath, json_encode([['id' => 1, 'name' => 'John']]));

        $storage = new FileStorage($this->testFilePath);
        $storage->append([['id' => 2, 'name' => 'Doe']]);

        $data = json_decode(file_get_contents($this->testFilePath), true);

        $this->assertCount(2, $data);
        $this->assertEquals([['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Doe']], $data);
    }

    public function testReadHandlesMalformedJson()
    {
        file_put_contents($this->testFilePath, '{malformed json');
        $storage = new FileStorage($this->testFilePath);

        $data = $storage->read();
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }
}
