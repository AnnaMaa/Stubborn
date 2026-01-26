<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:make-admin',
    description: 'Crée ou met à jour un utilisateur admin (email + mot de passe).'
)]
class MakeAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email admin')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (string) $input->getArgument('email');
        $plainPassword = (string) $input->getArgument('password');

        $repo = $this->em->getRepository(User::class);

        /** @var User|null $user */
        $user = $repo->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);

            
            if (method_exists($user, 'setUsername')) {
                $user->setUsername('admin');
            }

            
            if (method_exists($user, 'setDeliveryAddress')) {
                $user->setDeliveryAddress('Admin address');
            }

            $this->em->persist($user);
            $output->writeln("<info>Utilisateur créé.</info>");
        } else {
            $output->writeln("<comment>Utilisateur trouvé, mise à jour...</comment>");
        }

        $user->setRoles(['ROLE_ADMIN']);

        $hash = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hash);

        $this->em->flush();

        $output->writeln("<info>OK : {$email} est maintenant ROLE_ADMIN avec le mot de passe donné.</info>");

        return Command::SUCCESS;
    }
}
