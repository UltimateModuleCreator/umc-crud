<?php

/**
 * Umc_Crud extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Umc
 * @package   Umc_Crud
 * @copyright 2020 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */

declare(strict_types=1);

namespace Umc\Crud\Console\Command;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Module\Dir\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Deploy extends Command
{
    public const DI_FOLDER = 'crud';
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var File
     */
    private $ioFile;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * Deploy constructor.
     * @param Reader $reader
     * @param File $ioFile
     * @param DirectoryList $directoryList
     * @param null $name
     */
    public function __construct(Reader $reader, File $ioFile, DirectoryList $directoryList, $name = null)
    {
        $this->reader = $reader;
        $this->ioFile = $ioFile;
        $this->directoryList = $directoryList;
        parent::__construct($name);
    }

    /**
     * configure command
     */
    protected function configure()
    {
        $this->setName('umc:crud:deploy');
        $this->setDescription('Copies the required di.xml file for Umc_Crud module to app/etc');
        $this->addOption("force", "-f", InputOption::VALUE_NONE, "Force deploy config");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($output->isVerbose()) {
            $output->writeln('Copying CRUD generators config to root etc folder');
        }
        $source = $this->reader->getModuleDir('etc', 'Umc_Crud') . '/' . self::DI_FOLDER . '/di.xml';
        $destination = $this->directoryList->getPath('etc') . '/crud';
        $destinationFile = $destination . '/di.xml';

        $isForce = $input->getOption('force');
        if (!$isForce && $this->ioFile->fileExists($destinationFile)) {
            $output->writeln($destinationFile . " already exists. Use -f to overwrite it.");
        } else {
            $this->ioFile->checkAndCreateFolder($destination);
            $this->ioFile->cp($source, $destinationFile);
        }
    }
}
