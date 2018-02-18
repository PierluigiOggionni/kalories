<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meal;
use AppBundle\Entity\User;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use Craue\ConfigBundle\Entity\Setting;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the sample data to load in the database when running the unit and
 * functional tests. Execute this command to load the data:
 *
 *   $ php app/console doctrine:fixtures:load
 *
 * See http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
       $this->loadUsers($manager);

        $this->loadMeals($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $johnUser = new User();
        $johnUser->setUsername('john_user');
        $johnUser->setEmail('john_user@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($johnUser, 'kitten');
        $johnUser->setPassword($encodedPassword);
        $manager->persist($johnUser);

        $annaAdmin = new User();
        $annaAdmin->setUsername('anna_admin');
        $annaAdmin->setEmail('anna_admin@symfony.com');
        $annaAdmin->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword($annaAdmin, 'kitten');
        $annaAdmin->setPassword($encodedPassword);
        $manager->persist($annaAdmin);

        $markUser = new User();
        $markUser->setUsername('mark_user');
        $markUser->setEmail('mark_user@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($markUser, 'kitten');
        $markUser->setPassword($encodedPassword);
        $manager->persist($markUser);



        $manager->flush();
    }

    private function loadMeals(ObjectManager $manager) {

        for ($i = 1; $i <= 5; $i++) {
            $meal = new Meal();
            $user = $manager->getRepository('AppBundle:User')->findOneBy(array('email' => 'john_user@symfony.com'));

            $meal->setMealDate(new \DateTime('yesterday'));
            $time = $meal->getMealDate();
            $minutes_to_add = 5;

            $meal->setMealDateTime($time->add(new \DateInterval('PT' . rand(1, 900) . 'M')));
            $meal->setText("text");
            $meal->setUser($user);
            $meal->setCalories(rand(10,500));
            $manager->persist($meal);


        }

        for ($i = 1; $i <= 5; $i++) {
            $meal = new Meal();
            $user = $manager->getRepository('AppBundle:User')->findOneBy(array('email' => 'john_user@symfony.com'));

            $meal->setMealDate(new \DateTime('-2 days'));
            $time = $meal->getMealDate();
            $minutes_to_add = 5;

            $meal->setMealDateTime($time->add(new \DateInterval('PT' . rand(1, 900) . 'M')));
            $meal->setText("text");
            $meal->setUser($user);
            $meal->setCalories(rand(10,500));
            $manager->persist($meal);


        }

        for ($i = 1; $i <= 5; $i++) {
            $meal = new Meal();
            $user = $manager->getRepository('AppBundle:User')->findOneBy(array('email' => 'john_user@symfony.com'));

            $meal->setMealDate(new \DateTime('-3 days'));
            $time = $meal->getMealDate();
            $minutes_to_add = 5;

            $meal->setMealDateTime($time->add(new \DateInterval('PT' . rand(1, 900) . 'M')));
            $meal->setText("text");
            $meal->setUser($user);
            $meal->setCalories(rand(10,500));
            $manager->persist($meal);


        }
        for ($i = 1; $i <= 5; $i++) {
            $meal = new Meal();
            $user = $manager->getRepository('AppBundle:User')->findOneBy(array('email' => 'mark_user@symfony.com'));

            $meal->setMealDate(new \DateTime('-3 days'));
            $time = $meal->getMealDate();
            $minutes_to_add = 5;

            $meal->setMealDateTime($time->add(new \DateInterval('PT' . rand(1, 900) . 'M')));
            $meal->setText("text");
            $meal->setUser($user);
            $meal->setCalories(rand(10,500));
            $manager->persist($meal);


        }
        $manager->flush();

    }

    private function loadSettings(ObjectManager $manager)
    {
        $setting = new Setting();
        $setting->setName("calories_per_day");
        $setting->setSection("calories_settings");
        $setting->setValue("1300");
        $manager->persist($setting);
    }


}
