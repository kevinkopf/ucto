<?php

namespace App\DataFixtures;

use App\Contacts\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $contactLocal = new Contact(
            "Local Company",
            "Some street 12345",
            "012377854",
            false,
            "CZ",
            "012377854");

        $manager->persist($contactLocal);
        $manager->flush();

        $this->addReference('company-local', $contactLocal);

        $contactForeign = new Contact(
            "Foreign Company",
            "Some foreign street 12345",
            "875411982466854",
            true,
            "EU",
            "123456789885");

        $manager->persist($contactForeign);
        $manager->flush();

        $this->addReference('company-foreign', $contactForeign);
    }
}
