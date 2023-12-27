<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use App\Service\RoleService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:makeAdmin', description: 'promotes user to admin')]
class MakeAdminCommand extends Command
{
    public function __construct(private readonly RoleService $roleService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('user-id', InputArgument::REQUIRED, 'User ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = (int) $input->getArgument('user-id');
        $this->roleService->grantAdmin($userId);

        return self::SUCCESS;
    }
}
