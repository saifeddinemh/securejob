<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:debug-user',
    description: 'Debugs user existence and password validation',
)]
class DebugUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email to check')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password to verify')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error(sprintf('User "%s" not found in database.', $email));
            return Command::FAILURE;
        }

        $io->success(sprintf('User "%s" found (ID: %d).', $email, $user->getId()));
        $io->text('Roles: ' . implode(', ', $user->getRoles()));
        $io->text('Password Hash: ' . $user->getPassword());

        if ($password) {
            $isValid = $this->userPasswordHasher->isPasswordValid($user, $password);
            if ($isValid) {
                $io->success('Password validation PASSED at service level.');
            } else {
                $io->error('Password validation FAILED at service level.');
            }
        }

        return Command::SUCCESS;
    }
}
