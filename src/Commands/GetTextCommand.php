<?php

namespace Likemusic\PhpParser\CLI\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Smalot\PdfParser\Parser as PdfParser;
use Symfony\Component\Console\Input\InputOption;

class GetTextCommand extends Command
{
    protected static $defaultName = 'get-text';

    const ARGUMENT_NAME_FILENAME = 'filename';
    const ARGUMENT_NAME_PAGES = 'pages';

    const ARGUMENT_PAGES_ALL_VALUE = 'all';

    const OUTPUT_FILE_OPTION_NAME = 'of';


    protected function configure()
    {
        parent::configure();

        $this->setDescription('Get text from pdf-file.');

        $this->addArgument(self::ARGUMENT_NAME_FILENAME, InputArgument::REQUIRED, 'Pdf file name');
        $this->addArgument(self::ARGUMENT_NAME_PAGES, InputArgument::OPTIONAL, 'Comma separated page numbers', 'all');

        $this->addOption(self::OUTPUT_FILE_OPTION_NAME, 'o', InputOption::VALUE_REQUIRED, 'Output filename. By default just print text to console.');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $pdfParser = new PdfParser();

        $pdfFile = $pdfParser->parseFile($filename);
        $pages = $pdfFile->getPages();
        $pagesCount = count($pages);

        $pageNumbers = $this->getPageNumbersByArguments($input, $pagesCount);

        foreach ($pageNumbers as $pageNumber) {
            $page = $pages[$pageNumber];

            $ret[] = $page->getText();
        }

        $pagesSeparator = "\n" . chr(0xC);
        $text = implode($pagesSeparator, $ret);

        if ($outputFilename = $input->getOption(self::OUTPUT_FILE_OPTION_NAME)) {
           file_put_contents($outputFilename, $text);
        } else {
            $output->write($text);
        }

        return 0;
    }

    /**
     * @param InputInterface $input
     * @param int $pagesCount
     * @return array
     */
    private function getPageNumbersByArguments(InputInterface $input, int $pagesCount): array
    {
        $pagesArgumentValue = $input->getArgument(self::ARGUMENT_NAME_PAGES);

        if ($pagesArgumentValue === self::ARGUMENT_PAGES_ALL_VALUE) {
            return range(0, $pagesCount - 1);
        }

        return $this->getPageNumbersByCommaSeparatedValues($pagesArgumentValue);

        throw new InvalidArgumentException('Invalid pages argument value.');
    }

    private function getPageNumbersByCommaSeparatedValues(string $commaSeparatedString): array
    {
        $chunks = explode(',', $commaSeparatedString);
        $chunks = array_map('trim', $chunks);
        $chunks = array_filter($chunks);

        $invValues = array_map('intval', $chunks);

        return array_map(function($val) {
            return --$val;
        }, $invValues);
    }
}
