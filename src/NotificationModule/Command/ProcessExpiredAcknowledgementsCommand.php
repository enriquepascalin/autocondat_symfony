<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders.  If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 *
 * @author Enrique Pascalin, Erparom Technologies
 *
 * @version 1.0.0
 *
 * @since 2025-06-01
 *
 * @license license.md
 */

declare(strict_types=1);

namespace App\NotificationModule\Command;

use App\NotificationModule\Service\AcknowledgementService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to process expired acknowledgements (e.g., snoozed notifications that have expired).
 * 
 * This command should be scheduled to run periodically (e.g., every 5 minutes) to handle
 * acknowledgements that have reached their expiration time.
 */
#[AsCommand(
    name: 'notifications:process-expired-acks',
    description: 'Processes expired acknowledgements (snoozed notifications)',
    aliases: ['notifs:expired-acks']
)]
final class ProcessExpiredAcknowledgementsCommand extends Command
{
    /**
     * Constructor.
     *
     * @param AcknowledgementService $ackService Acknowledgement service
     */
    public function __construct(
        private readonly AcknowledgementService $ackService
    ) {
        parent::__construct();
    }

    /**
     * Executes the command to process expired acknowledgements.
     *
     * @param InputInterface $input Console input
     * @param OutputInterface $output Console output
     * @return int Command exit status
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $processedCount = $this->ackService->processExpiredAcknowledgements();
        
        if ($processedCount > 0) {
            $output->writeln(sprintf(
                '<info>Processed %d expired acknowledgements</info>',
                $processedCount
            ));
        } else {
            $output->writeln('<comment>No expired acknowledgements found</comment>');
        }
        
        return Command::SUCCESS;
    }
}