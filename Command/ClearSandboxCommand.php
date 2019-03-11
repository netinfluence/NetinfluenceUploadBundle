<?php

namespace Netinfluence\UploadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClearSandboxCommand
 */
class ClearSandboxCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('netinf:upload:clear')
            ->setDescription('Clear files in the upload sandbox and their thumbnails')
            ->addOption('grace', 'g', InputOption::VALUE_OPTIONAL, '"Days of grace": number of days not to clear. By default, 2, we won\'t clear files stored yesterday or today in sandbox. 0 will clear everything.', 2)
        ;
    }

    /**
     * Clear sandbox (temporary files)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('netinfluence_upload.sandbox_manager');

        $output->writeln('<comment>Starting clearing sandbox and thumbnails cache</comment>');

        // May throw an exception, then the command will fail (we want)
        $count = $manager->clear($input->getOption('grace'));

        $output->writeln("<info>Successfully removed ${count} temporary files and their thumbnails from sandbox</info>");
    }
}
