<?php

namespace PhpFqcnParser\Console;

use PhpFqcnParser\Options;
use PhpFqcnParser\PhpFqcnParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  20.05.2018
 */
class RunCommand extends Command
{
    const ARG_FILENAMES = 'filenames';
    const OPT_BASEPATH = 'basepath';
    const OPT_BASEPATH_SHORT = 'b';
    const OPT_INPUTFILE = 'inputfile';
    const OPT_INPUTFILE_SHORT = 'i';
    const FILENAMES_SINGLEARG_DELIMITER = ',';

    protected function configure()
    {
        $this->setName('run')
            ->addArgument(static::ARG_FILENAMES, InputArgument::IS_ARRAY)
            ->addOption(static::OPT_BASEPATH, static::OPT_BASEPATH_SHORT, InputOption::VALUE_REQUIRED)
            ->addOption(static::OPT_INPUTFILE, static::OPT_INPUTFILE_SHORT, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileNames = $this->getFileNames($input);
        $options = $this->getOptions($input);

        $parser = new PhpFqcnParser();
        foreach ($parser->getFqcnsFromFiles($fileNames, $options) as $resultLine) {
            $output->writeln($resultLine);
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    protected function getFileNames(InputInterface $input)
    {
        $fileNames = $this->getFileNamesFromInputFile($input);
        if (empty($fileNames)) {
            $fileNames = $this->getFileNamesFromArgs($input);
            if (empty($fileNames)) {
                $fileNames = $this->getFileNamesFromStdin();
            }
        }

        return $this->processFileNamesForSingleArgDelimiter($fileNames);
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    protected function getFileNamesFromInputFile(InputInterface $input)
    {
        $filenames = [];

        $inputFile = $input->getOption(static::OPT_INPUTFILE);
        if ($inputFile && file_exists($inputFile)) {
            $filenames = explode(PHP_EOL, file_get_contents($inputFile));
        }

        return $filenames;
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    protected function getFileNamesFromArgs(InputInterface $input)
    {
        return $input->getArgument(static::ARG_FILENAMES);
    }

    /**
     * @return string[]
     */
    protected function getFileNamesFromStdin()
    {
        $fileNames = [];
        $stdin = fopen('php://stdin', 'r');
        if ($stdin !== false) {
            stream_set_blocking($stdin, 0);
            while ($line = fgets($stdin)) {
                $fileNames[] = rtrim($line, PHP_EOL);
            }
        }

        return $fileNames;
    }

    /**
     * @param string[] $fileNames
     *
     * @return string[]
     */
    protected function processFileNamesForSingleArgDelimiter($fileNames)
    {
        if (count($fileNames) == 1 && strpos($fileNames[0], static::FILENAMES_SINGLEARG_DELIMITER) !== false) {
            $fileNames = explode(static::FILENAMES_SINGLEARG_DELIMITER, $fileNames[0]);
        }
        return $fileNames;
    }

    /**
     * @param InputInterface $input
     *
     * @return Options
     */
    protected function getOptions(InputInterface $input)
    {
        $options = (new Options())->setPathTypeAbsolute();
        $basePath = $input->getOption(static::OPT_BASEPATH);
        if ($basePath) {
            $options->setPathTypeRelative($basePath);
        }
        return $options;
    }
}
