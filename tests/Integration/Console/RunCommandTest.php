<?php

namespace PhpFqcnParser\Tests\Integration\Console;

use PhpFqcnParser\Console\RunCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Process\Process;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  20.05.2018
 */
class RunCommandTest extends \PHPUnit_Framework_TestCase
{
    public function fileNamesPassedViaInputFileProvider()
    {
        $file1RelativePath = '/../sample-with-ns.php';
        $file2RelativePath = '/../sample-with-ns-two-classes.php';
        $file1AbsolutePath = realpath(__DIR__ . $file1RelativePath);
        $file2AbsolutePath = realpath(__DIR__ . $file2RelativePath);
        $basePath = __DIR__;

        return [
            'Absolute paths as input file via long option' => [
                [$file1AbsolutePath, $file2AbsolutePath],
                null,
                null,
                '--' . RunCommand::OPT_INPUTFILE
            ],
            'Absolute paths as input file via short option' => [
                [$file1AbsolutePath, $file2AbsolutePath],
                null,
                null,
                '-' . RunCommand::OPT_INPUTFILE_SHORT
            ],
            'Relative paths as input file via long base path and long input file options' => [
                [$file1RelativePath, $file2RelativePath],
                '--' . RunCommand::OPT_BASEPATH,
                $basePath,
                '--' . RunCommand::OPT_INPUTFILE
            ],
            'Relative paths as input file via long base path and short input file options' => [
                [$file1RelativePath, $file2RelativePath],
                '--' . RunCommand::OPT_BASEPATH,
                $basePath,
                '-' . RunCommand::OPT_INPUTFILE_SHORT
            ],
            'Relative paths as input file via short base base path and long input file options' => [
                [$file1RelativePath, $file2RelativePath],
                '-' . RunCommand::OPT_BASEPATH_SHORT,
                $basePath,
                '--' . RunCommand::OPT_INPUTFILE
            ],
            'Relative paths as input file via short base base path and short input file options' => [
                [$file1RelativePath, $file2RelativePath],
                '-' . RunCommand::OPT_BASEPATH_SHORT,
                $basePath,
                '-' . RunCommand::OPT_INPUTFILE_SHORT
            ]
        ];
    }

    /**
     * @dataProvider fileNamesPassedViaInputFileProvider
     *
     * @param string[]    $args
     * @param string|null $opt
     * @param string|null $optVal
     * @param string      $inputOpt
     */
    public function testFileNamesPassedViaInputFile($args, $opt, $optVal, $inputOpt)
    {
        $tmpFile = '/tmp/PhpFqcnParserRunCommandTest' . uniqid();
        file_put_contents($tmpFile, implode(PHP_EOL, $args));

        $command = new RunCommand();
        $tester = new CommandTester($command);
        $argsAndOpts = [$inputOpt => $tmpFile];
        if ($opt) {
            $argsAndOpts[$opt] = $optVal;
        }
        $tester->execute($argsAndOpts);

        unlink($tmpFile);

        $this->assertOutput($tester->getDisplay());
    }

    public function fileNamesPassedAsArgsProvider()
    {
        $file1RelativePath = '/../sample-with-ns.php';
        $file2RelativePath = '/../sample-with-ns-two-classes.php';
        $file1AbsolutePath = realpath(__DIR__ . $file1RelativePath);
        $file2AbsolutePath = realpath(__DIR__ . $file2RelativePath);
        $basePath = __DIR__;

        return [
            'Absolute paths as single arg delimited by comma' =>
                [["$file1AbsolutePath,$file2AbsolutePath"], null, null],
            'Relative paths as single arg delimited by comma via long option' =>
                [["$file1RelativePath,$file2RelativePath"], '--' . RunCommand::OPT_BASEPATH, $basePath],
            'Relative paths as single arg delimited by comma via short option' =>
                [["$file1RelativePath,$file2RelativePath"], '-' . RunCommand::OPT_BASEPATH_SHORT, $basePath],
            'Absolute paths as multiple args' =>
                [[$file1AbsolutePath, $file2AbsolutePath], null, null],
            'Relative paths as multiple args via long option' =>
                [[$file1RelativePath, $file2RelativePath], '--' . RunCommand::OPT_BASEPATH, $basePath],
            'Relative paths as multiple args via short option' =>
                [[$file1RelativePath, $file2RelativePath], '-' . RunCommand::OPT_BASEPATH_SHORT, $basePath]
        ];
    }

    /**
     * @dataProvider fileNamesPassedAsArgsProvider
     *
     * @param string[]    $args
     * @param string|null $opt
     * @param string|null $optVal
     */
    public function testFileNamesPassedAsArguments($args, $opt, $optVal)
    {
        $command = new RunCommand();
        $tester = new CommandTester($command);
        $argsAndOpts = [RunCommand::ARG_FILENAMES => $args];
        if ($opt) {
            $argsAndOpts[$opt] = $optVal;
        }
        $tester->execute($argsAndOpts);

        $this->assertOutput($tester->getDisplay());
    }

    /**
     * @param string $output
     */
    protected function assertOutput($output)
    {
        self::assertContains('Completely\Imaginary\Namespac\FooBarBazQuux', $output);
        self::assertContains('Completely\Imaginary\Namespac\FooBar', $output);
        self::assertContains('Completely\Imaginary\Namespac\BazQuux', $output);
    }

    public function testOutputIsEmptyIfNoArgsOrStdinAreProvided()
    {
        $command = new RunCommand();
        $tester = new CommandTester($command);
        $tester->execute([]);

        self::assertEmpty($tester->getDisplay());
    }

    public function processProvider()
    {
        $file1RelativePath = '/../sample-with-ns.php';
        $file2RelativePath = '/../sample-with-ns-two-classes.php';
        $file1AbsolutePath = realpath(__DIR__ . $file1RelativePath);
        $file2AbsolutePath = realpath(__DIR__ . $file2RelativePath);
        $basePath = __DIR__;
        $longOpt = '--' . RunCommand::OPT_BASEPATH;
        $shortOpt = '-' . RunCommand::OPT_BASEPATH_SHORT;

        return [
            'Absolute paths as single arg delimited by comma' =>
                ["$file1AbsolutePath,$file2AbsolutePath", ''],
            'Relative paths as single arg delimited by comma via long option' =>
                ["$file1RelativePath,$file2RelativePath", "$longOpt $basePath"],
            'Relative paths as single arg delimited by comma via short option' =>
                ["$file1RelativePath,$file2RelativePath", "$shortOpt $basePath"],
            'Absolute paths as multiple args' =>
                ["$file1AbsolutePath $file2AbsolutePath", ''],
            'Relative paths as multiple args via long option' =>
                ["$file1RelativePath $file2RelativePath", "$longOpt $basePath"],
            'Relative paths as multiple args via short option' =>
                ["$file1RelativePath $file2RelativePath", "$shortOpt $basePath"]
        ];
    }

    /**
     * @dataProvider processProvider
     * @group        slow
     *
     * @param string $args
     * @param string $opts
     */
    public function testFileNamesPassedAsStdin($args, $opts)
    {
        if (strpos($args, ' ') !== false) {
            $args = implode("\n", explode(' ', $args));
        }

        $tmpFile = '/tmp/PhpFqcnParserRunCommandTest' . uniqid();
        file_put_contents($tmpFile, $args);
        $binPath = realpath(__DIR__ . '/../../../bin/fqcnparser.php');
        $process = new Process("cat $tmpFile | php $binPath $opts");
        $process->run();
        unlink($tmpFile);

        $this->assertOutput($process->getOutput());
    }
}
