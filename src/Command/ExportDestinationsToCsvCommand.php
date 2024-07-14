<?php

namespace App\Command;

use App\Service\CsvExporter;
use App\Service\DestinationFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'app:export-destinations',description: 'Exports all destinations to a CSV file')]
class ExportDestinationsToCsvCommand extends Command
{
    private $destinationFetcher;
    private $params;
    private $logger;
    private $csvExporter;

    public function __construct(DestinationFetcher $destinationFetcher, ParameterBagInterface $params, LoggerInterface $logger, CsvExporter $csvExporter)
    {
        parent::__construct();
        $this->destinationFetcher = $destinationFetcher;
        $this->params = $params;
        $this->logger = $logger;
        $this->csvExporter = $csvExporter;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $exportsDirectory = $this->params->get('exports_directory');
        $fileExport = $exportsDirectory . "/export_destinations_" . date('Y_m_d') . ".csv";
        try {
            $destinations = $this->destinationFetcher->fetchAll($this->params->get('url_api_list_destinations'));
            $this->csvExporter->exportToFile($fileExport, $destinations['hydra:member']);
            $this->logger->info('Destinations exported successfully to ' . $fileExport);
            $io->success('Destinations exported successfully to ' . $fileExport);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('An error occurred during the export process', [
                'exception' => $e->getMessage()
            ]);
            $io->error('An error occurred during the export process');
            
            return Command::FAILURE;
        }
    }
}
