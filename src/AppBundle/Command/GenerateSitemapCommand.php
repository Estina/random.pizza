<?php

/**
 * This file is part of Boozt Platform
 * and belongs to BZT Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Generate Sitemap Command
 *
 * @package Boozt\Bundle\AppBundle\Command
 */
class GenerateSitemapCommand extends ContainerAwareCommand
{
    const URL_LIMIT = 50000;

    /** @var string */
    private $filename;

    /** @var \XMLWriter */
    private $writer;

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('generate:sitemap')
            ->setDescription('Generate XML sitemap');
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input  Input stream.
     * @param OutputInterface $output Output stream.
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        $output->writeln("Creating XML sitemap...");
        $results = $this->getContainer()->get('service.result')->getResults(self::URL_LIMIT);

        $this->startSitemap();
        $output->writeln(sprintf('Filename: %s', $this->filename));
        foreach ($results as $result) {
            $this->writeUrl($result['slug'], $result['date_created']);
        }
        $this->stopSitemap();
        $output->writeln("Done!" . PHP_EOL);

        $endTime = microtime(true);
        $output->writeln("Total time: " . round($endTime - $startTime, 2) . 's');
        $output->writeln("Memory usage (peak): " . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB');
        $output->writeln("Memory usage: " . round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB');
    }

    /**
     * @return void
     */
    private function startSitemap()
    {
        $this->filename = sprintf('%s/sitemap.xml', $this->getContainer()->get('kernel')->getRootDir() . '/../web/sitemap');

        $xmlWriter = new \XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElementNS(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        file_put_contents($this->filename, $xmlWriter->flush(true));

        $this->writer = $xmlWriter;
    }

    /**
     * @param string $slug
     * @param int    $lastModifiedTimeStamp
     */
    private function writeUrl($slug, $lastModifiedTimeStamp)
    {
        $this->writer->startElement('url');
        $this->writer->writeElement('loc', sprintf('http://random.pizza/%s', $slug));
        $this->writer->writeElement('lastmod', date('c', $lastModifiedTimeStamp));
        $this->writer->endElement();
    }

    /**
     * Close sitemap file.
     *
     * @return void
     */
    private function stopSitemap()
    {
        $this->writer->endElement(); // </urlset>.
        file_put_contents($this->filename, $this->writer->flush(true), FILE_APPEND);
    }
}
