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

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Scaffolds a complete Autocondat module (folders, translation, route import).
 */
#[AsCommand(
    name: 'make:autocondat:module',
    description: 'Creates a new Autocondat module'
)]
final class MakeAutocondatModuleCommand extends Command
{
    /** @var array<string,string[]> */
    private const OPTIONAL_MAP = [
        'e' => ['Event', 'EventSubscriber', 'EventListener'],
        'c' => ['Contract'],
        'm' => ['Message'],
        't' => ['Traits'],
        'o' => ['ValueObject'],
    ];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly Filesystem $fs = new Filesystem()
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the module')
            ->addOption('regenerate', null, InputOption::VALUE_NONE, 'Overwrite existing module')
            ->addOption('e', null, InputOption::VALUE_NONE, 'Add Event, Subscriber, Listener')
            ->addOption('c', null, InputOption::VALUE_NONE, 'Add Contract folder')
            ->addOption('m', null, InputOption::VALUE_NONE, 'Add Message folder')
            ->addOption('t', null, InputOption::VALUE_NONE, 'Add Traits folder')
            ->addOption('o', null, InputOption::VALUE_NONE, 'Add ValueObject folder')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Generate every optional folder (-ecnmto)')
            ->addOption('silent', null, InputOption::VALUE_NONE, 'Suppress CLI output');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $name     = (string) $input->getArgument('name');
        $rootDir  = $this->kernel->getProjectDir();
        $silent   = $input->getOption('silent');
        $regen    = $input->getOption('regenerate');

        if (!$this->prepareWorkspace($io, $rootDir, $name, $regen, $silent)) {
            return Command::FAILURE;
        }

        $this->fs->mkdir($this->collectDirectories($rootDir, $name, $input));
        $this->createTranslation($rootDir, $name);
        $this->createRouteImport($rootDir, $name);

        if (!$silent) {
            $msg = $regen ? "Module '{$name}' patched successfully." : "Module '{$name}' scaffolded successfully.";
            $io->success($msg);
        }

        return Command::SUCCESS;
    }

    /**
     * Validates the workspace.  Never deletes anything.
     */
    private function prepareWorkspace(
        SymfonyStyle $io,
        string $root,
        string $name,
        bool $regen,
        bool $silent
    ): bool {
        $modulePath = "{$root}/src/{$name}";

        if ($this->fs->exists($modulePath) && !$regen) {
            if (!$silent) {
                $io->error("Module '{$name}' already exists. Use --regenerate to patch it.");
            }
            return false;
        }

        return true;
    }

    /**
     * Builds the folder list based on CLI flags.
     *
     * @return string[]
     */
    private function collectDirectories(string $root, string $name, InputInterface $input): array
    {
        $src   = "{$root}/src/{$name}";
        $tests = "{$root}/tests/{$name}";

        $dirs = [
            "{$src}/Controller",
            "{$src}/Entity",
            "{$src}/Form",
            "{$src}/Repository",
            "{$src}/Service",
            "{$root}/templates/{$name}",
            "{$tests}/Unit",
            "{$tests}/Integration",
            "{$tests}/Functional",
        ];

        $selectedFlags = $input->getOption('all')
            ? array_keys(self::OPTIONAL_MAP)
            : array_filter(
                array_keys(self::OPTIONAL_MAP),
                static fn (string $flag): bool => $input->getOption($flag)
            );

        foreach ($selectedFlags as $flag) {
            foreach (self::OPTIONAL_MAP[$flag] as $folder) {
                $dirs[] = "{$src}/{$folder}";
            }
        }

        return $dirs;
    }

    /**
     * Creates a stub translation file if it does not exist.
     */
    private function createTranslation(string $root, string $name): void
    {
        $snakeCaseName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        $file = "{$root}/translations/{$snakeCaseName}.en.yaml";
        if (!$this->fs->exists($file)) {
            $this->fs->dumpFile($file, "# English translations for {$name} module\n");
        }
    }

    /**
     * Writes the YAML route import (snake_case file name) only if it does not exist.
     */
    private function createRouteImport(string $root, string $name): void
    {
        $snakeCaseName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        $filePath      = "{$root}/config/routes/{$snakeCaseName}.yaml";

        if ($this->fs->exists($filePath)) {
            return;
        }

        $yaml = <<<YAML
    controllers:
        resource: ../../src/{$name}/Controller/
        type: attribute
        prefix: /{$name}

    YAML;

        $this->fs->dumpFile($filePath, $yaml);
    }
}