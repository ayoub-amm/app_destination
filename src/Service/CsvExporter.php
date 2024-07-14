<?php 
namespace App\Service;

class CsvExporter
{
    public function exportToFile(string $filePath, array $destinations): void
    {
        $csvContent = "Name,Description,Price,Duration\n";
        foreach ($destinations as $destination) {
            $csvContent .= sprintf(
                "%s,%s,%d,%s\n",
                $destination['name'],
                $destination['description'],
                $destination['price'],
                $destination['duration'].' days'
            );
        }
        file_put_contents($filePath, $csvContent);
    }
}
