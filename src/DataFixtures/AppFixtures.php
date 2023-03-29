<?php

namespace App\DataFixtures;

use App\Entity\Carpool;
use App\Entity\Category;
use App\Entity\Message;
use App\Entity\UserChild;
use App\Entity\Event;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasherm
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // USERS
        $basicUserList = [];
        for ($i = 0; $i < 100; $i++) {
            $basicUser = new User();
            $manager->persist($basicUser);

            $basicUser->setEmail($faker->email());
            $basicUser->setRoles(['ROLE_USER']);
            $basicUser->setFirstName($faker->firstName());
            $basicUser->setLastName($faker->lastName());
            $basicUser->setPhone($faker->phoneNumber());
            $basicUser->setAddress($faker->address());
            $basicUser->setPostalCode($faker->postcode());
            $basicUser->setCity($faker->city());
            $basicUser->setAddedAt(new DateTimeImmutable());

            $hashedPassword = $this->passwordHasherm->hashPassword($basicUser, 'user');
            $basicUser->setPassword($hashedPassword);
            $basicUserList[] = $basicUser;
        }

        //ADMINS
        for ($i = 0; $i < 3; $i++) {
            $adminUser = new User();
            $manager->persist($adminUser);

            $adminUser->setEmail($faker->email());
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setFirstName($faker->firstName());
            $adminUser->setLastName($faker->lastName());
            $adminUser->setPhone($faker->phoneNumber());
            $adminUser->setAddress($faker->address());
            $adminUser->setPostalCode($faker->postcode());
            $adminUser->setCity($faker->city());
            $adminUser->setAddedAt(new DateTimeImmutable('now'));

            $hashedPassword = $this->passwordHasherm->hashPassword($adminUser, 'admin');
            $adminUser->setPassword($hashedPassword);
            $manager->persist($adminUser);
        }

        // CATEGORIES
        $categoryList = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $manager->persist($category);

            $category->setName($faker->word());
            $category->setAddedAt(new DateTimeImmutable('now'));
            $categoryList[] = $category;
        }

        // CHILDREN
        $childList = [];
        for ($i = 0; $i < 200; $i++) {
            $child = new UserChild();
            $manager->persist($child);

            $child->setFirstName($faker->firstName());
            $child->setLastName($faker->lastName());
            $child->setBirthday(new DateTimeImmutable('now'));
            $child->setAddedAt(new DateTimeImmutable('now'));
            $child->setUser($basicUserList[array_rand($basicUserList)]);
            $child->setCategory($categoryList[array_rand($categoryList)]);
            $childList[] = $child;
        }

        // EVENTS
        $eventList = [];
        for ($i = 0; $i < 100; $i++) {
            $event = new Event();
            $manager->persist($event);

            $event->setStatus($faker->randomElement(['1', '2', '3', '4']));
            $event->setTitle($faker->word());
            $event->setAddress($faker->address());
            $event->setPostalCode($faker->postcode());
            $event->setCity($faker->city());
            $event->setDescription($faker->text(200));
            $event->setNbMinus($faker->numberBetween(10, 15));
            $event->setNbRegistrant($faker->numberBetween(0, 10));
            $event->setDate(new DateTimeImmutable('now'));
            $event->setAddedAt(new DateTimeImmutable('now'));
            $eventList[] = $event;
        }

        // EVENTS - USER_CHILD
        for ($i = 0; $i < 100; $i++) {
            $event = $eventList[array_rand($eventList)];
            $child = $childList[array_rand($childList)];
            $event->addChild($child);
            $child->addEvent($event);
        }

        // CARPOOLS
        $carpoolList = [];
        for ($i = 0; $i < 100; $i++) {
            $carpool = new Carpool();
            $manager->persist($carpool);

            $carpool->setStatus($faker->randomElement(['1', '2']));
            $carpool->setAddress($faker->address());
            $carpool->setPostalCode($faker->postcode());
            $carpool->setCity($faker->city());
            $carpool->setComment($faker->text(200));
            $carpool->setDate(new DateTimeImmutable('now'));
            $carpool->setNbPlace($faker->numberBetween(1, 5));
            $carpool->setAddedAt(new DateTimeImmutable('now'));
            $carpool->setEvent($eventList[array_rand($eventList)]);
            $carpoolList[] = $carpool;
        }

        // USERS - CARPOOLS
        for ($i = 0; $i < 100; $i++) {
            $user = $basicUserList[array_rand($basicUserList)];
            $carpool = $carpoolList[array_rand($carpoolList)];
            $user->addCarpool($carpool);
            $carpool->addUser($user);
        }

        // CHILDREN - CARPOOLS
        for ($i = 0; $i < 100; $i++) {
            $child = $childList[array_rand($childList)];
            $carpool = $carpoolList[array_rand($carpoolList)];
            $child->addCarpool($carpool);
            $carpool->addChild($child);
        }

        // EVENT - CATEGORY
        for ($i = 0; $i < 100; $i++) {
            $event = $eventList[array_rand($eventList)];
            $category = $categoryList[array_rand($categoryList)];
            $event->addCategory($category);
            $category->addEvent($event);
        }

        // MESSAGES
        for ($i = 0; $i < 3; $i++) {
            $message = new Message();
            $manager->persist($message);

            $message->setTitle($faker->word());
            $message->setDescription($faker->text(200));
            $message->setColor($faker->randomElement(['info', 'warning', 'danger', 'success']));
            $message->setStartAt(new DateTimeImmutable('now'));
            $message->setEndAt(new DateTimeImmutable('now'));
            $message->setAddedAt(new DateTimeImmutable('now'));
        }

        $manager->flush();
    }
}