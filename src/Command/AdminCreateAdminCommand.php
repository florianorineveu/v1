<?php

namespace App\Command;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:admin:create-admin',
    description: 'Create admin and enable it.',
)]
class AdminCreateAdminCommand extends Command
{
    public function __construct(
        private AdminRepository $adminRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
        private ValidatorInterface $validator,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail used to create the user.')
            ->addOption('super', 's', InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io              = new SymfonyStyle($input, $output);
        $email           = $input->getArgument('email');

        if ($this->validator->validate($email, new Email())->count()) {
            $io->error(sprintf('%s is not a valid email address.', $email));

            return Command::INVALID;
        }

        if ($this->adminRepository->findOneBy(['email' => $email])) {
            $io->error(sprintf('An administrator already exists for the email address: %s.', $email));

            return Command::INVALID;
        }

        $isSuper = false;

        if ($input->getOption('super')) {
            $isSuper = true;
        }

        $io->note(sprintf('You are about to create %s administrator for: %s', $isSuper ? 'a super' : 'an', $email));

        $admin = new Admin();
        $admin
            ->setEmail($email)
            ->setRoles($isSuper ? ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'] : ['ROLE_ADMIN', 'ROLE_USER'])
            ->setEnabled(true)
            ->setPassword($this->userPasswordHasher->hashPassword($admin, $io->askHidden('Please enter the password for the administrator', function ($value) use ($io) {
                if (trim($value) === '') {
                    throw new \InvalidArgumentException('The password cannot be empty.');
                }

                return $value;
            })))
        ;

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success(sprintf('%s administrator for %s has been successfully created and can now log in (id: %s).', $isSuper ? 'A super' : 'An', $email, $admin->getId()));

        return Command::SUCCESS;
    }
}
