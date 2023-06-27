<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $france = new Country();
        $france->setName('France');

        $allemagne = new Country;
        $allemagne->setName('Deutschland');

        $canada = new Country;
        $canada->setName('Canada');

        //France
        $paris = new City;
        $paris->setName('Paris');
        $paris->setCountry($france);

        $versailles = new City;
        $versailles->setName('Versailles');
        $versailles->setCountry($france);

        $toulouse = new City;
        $toulouse->setName('Toulouse');
        $toulouse->setCountry($france);

        //Allemagne
        $leipzip = new City;
        $leipzip->setName('Leipzig');
        $leipzip->setCountry($allemagne);

        $francfort = new City;
        $francfort->setName('Frankfurt');
        $francfort->setCountry($allemagne);

        $hanovre = new City;
        $hanovre->setName('Hannover');
        $hanovre->setCountry($allemagne);

        //Canada
        $montreal = new City;
        $montreal->setName('Montréal');
        $montreal->setCountry($canada);

        $toronto = new City;
        $toronto->setName('Toronto');
        $toronto->setCountry($canada);

        $calgary = new City;
        $calgary->setName('Calgary');
        $calgary->setCountry($canada);
        

       $manager->persist($france);
       $manager->persist($allemagne);
       $manager->persist($canada);
       $manager->persist($paris);
       $manager->persist($versailles);
       $manager->persist($toulouse);
       $manager->persist($leipzip);
       $manager->persist($francfort);
       $manager->persist($hanovre);
       $manager->persist($montreal);
       $manager->persist($toronto);
       $manager->persist($calgary);

       $manager->flush();
    }
}
