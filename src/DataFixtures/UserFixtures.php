<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10,'users',function (int $i){
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([User::ROLE_USER]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'user1234'
                )
            );
            return $user;
        });

        $this->createMany(3,'admins', function (int $i){
            $admin = new User();
            $admin->setEmail(sprintf('admin%d@example.com', $i));
            $admin->setRoles([User::ROLE_USER,User::ROLE_ADMIN]);
            $admin->setPassword(
                $this->passwordHasher->hashPassword(
                    $admin,
                    'admin1234'
                )
            );
            return $admin;
        });

        $this->manager->flush();
    }


}