<?php

namespace App\Tests\Service;

use App\Service\CsvExporter;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
    public function testExport(): void
    {
        $exporter = new CsvExporter();
        $filePath = 'var/exports/test_output.csv';
        $data = [
            ['name' => 'Destination 1', 'description' => 'Description 1', 'price' => 100, 'duration' => '2'],
        ];

        $exporter->exportToFile($filePath, $data);

        $this->assertFileExists($filePath);

        $csvData = array_map('str_getcsv', file($filePath));
        $this->assertCount(2, $csvData); // 1 header + 1 row
        $this->assertEquals(['Name', 'Description', 'Price', 'Duration'], $csvData[0]);
        $this->assertEquals(['Destination 1', 'Description 1', '100', '2 days'], $csvData[1]);

        unlink($filePath);
    }

}
