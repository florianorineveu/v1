<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    protected $em;
    protected $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        string $name = null
    ) {
        parent::__construct($name);
        $this->em              = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new admin.')
            ->setHelp('This command allows you to create an admin.')
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail used to create the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io           = new SymfonyStyle($input, $output);
        $email        = $input->getArgument('email');

        $io->title(sprintf('You are about to create an user for %s.', $email));

        $user           = new User();
        $userRepository = $this->em->getRepository(User::class);

        $existUser      = $userRepository->findOneBy(['email' => $email]);

        if ($existUser) {
            $io->error('An user with this e-mail already exists.');

            return;
        }

        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);

        $user->setUsername($io->ask('Please enter an username for the user', $email, function ($value) use ($userRepository) {
            if ($userRepository->findOneBy(['username' => $value])) {
                throw new \Exception(sprintf('The username "%s" is already used by another user.', $value));
            }

            return $value;
        }));

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $io->askHidden('Please enter the password for the user', function ($value) {
                if (trim($value) === '') {
                    throw new \Exception('The password cannot be empty');
                }

                return $value;
            })
        ));

        $this->em->persist($user);
        $this->em->flush($user);

        $io->success('The user has been successfully created and can now log in.');
    }
}
