<?php

namespace App\Command;

use App\Service\DataFormatter;
use App\Service\InputHandler\InputHandlerFactory;
use App\Service\OutputHandler\OutputHandlerFactory;
use App\Validator\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseHotelsCommand extends Command
{
    protected static $defaultName = 'app:parse-hotels';

    /** @var InputHandlerFactory $inputHandlerFactory */
    private $inputHandlerFactory;

    /** @var OutputHandlerFactory $outputHandlerFactory */
    private $outputHandlerFactory;

    public function __construct(string $name = null)
    {
        $this->outputHandlerFactory = new OutputHandlerFactory();
        $this->inputHandlerFactory = new InputHandlerFactory();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Parse csv file hotels.')
            ->setHelp('Parse csv file hotels and write output to chosen formats.')
            ->addArgument('input-path', InputArgument::OPTIONAL, 'Set path to the file', 'inputFiles/hotels.csv')
            ->addArgument('output-path', InputArgument::OPTIONAL, 'Set path to the folder containing output files', 'outputFiles')
            ->addArgument('sort-by', InputArgument::OPTIONAL, 'Set field for sorting output', 'stars')
            ->addArgument('output-formats', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Output file formats. Available for selection: json, html, xml, yaml', ['json', 'html']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputs = $input->getArgument('output-formats');
        $inputPath = $input->getArgument('input-path');
        $outputPath = $input->getArgument('output-path');
        $sortBy = $input->getArgument('sort-by');

        $inputHandler = $this->inputHandlerFactory->createInputHandler('csv');
        $array = $inputHandler->handle($inputPath);
        $output->writeln('Input file parsed');

        $resultArray = [];

        foreach ($array as $row) {
            $keys = array_keys($row);
            $isValid = true;

            foreach ($keys as $key) {
                if (!$isValid) {
                    continue;
                }

                try {
                    $isValid = Validator::$key($row[$key]);
                } catch (\Throwable $exception) {
                    $isValid = false;
                }
            }

            if ($isValid) {
                $resultArray[] = $row;
            }
        }

        $output->writeln('Input file validated');

        if ($resultArray != []) {
            DataFormatter::sortByKey($resultArray, $sortBy);
            $output->writeln('Input file sorted');
        }


        foreach ($outputs as $type) {
            $outputHandler = $this->outputHandlerFactory->createOutputHandler($type);
            $outputHandler->handle($resultArray, $outputPath);
            $output->writeln('Output file created');
        }

        $output->writeln('Finish!');
        return 0;
    }
}