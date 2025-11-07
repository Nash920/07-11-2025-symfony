<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Engine;
use App\Entity\Driver;
use App\Entity\Infraction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teams = [];

        $teamNames = ['Ferrari', 'Mercedes', 'RedBull'];
        $engineBrands = ['Ferrari Engine', 'Mercedes Power Unit', 'Honda RBPT'];

        foreach ($teamNames as $index => $name) {
            $team = new Team();
            $team->setName($name);

            $engine = new Engine();
            $engine->setBrand($engineBrands[$index]);
            $engine->setTeam($team);

            $team->setEngine($engine);

            $manager->persist($team);
            $manager->persist($engine);

            $teams[] = $team;
        }

        foreach ($teams as $team) {
            for ($i = 1; $i <= 3; $i++) {
                $driver = new Driver();
                $driver->setFirstName("Driver{$i}_{$team->getName()}")
                    ->setLastName("Lastname{$i}")
                    ->setTeam($team);

                if ($i === 3) {
                    $driver->setStatus(Driver::STATUS_RESERVE);
                }

                $manager->persist($driver);
            }
        }

        $drivers = $manager->getRepository(Driver::class)->findAll();

        foreach ($drivers as $index => $driver) {
            if ($index % 3 === 0) {
                $infraction = new Infraction();
                $infraction->setType(Infraction::TYPE_POINTS)
                    ->setPoints(3)
                    ->setRaceName('Grand Prix Test')
                    ->setDescription('Contact sur la piste')
                    ->setDriver($driver);
                $manager->persist($infraction);
            }

            if ($index % 4 === 0) {
                $infraction = new Infraction();
                $infraction->setType(Infraction::TYPE_FINE)
                    ->setAmount('5000')
                    ->setRaceName('Grand Prix Test')
                    ->setDescription('Infraction d’équipe')
                    ->setTeam($driver->getTeam());
                $manager->persist($infraction);
            }
        }

        $manager->flush();
    }
}
