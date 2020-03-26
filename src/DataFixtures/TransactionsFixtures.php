<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionsFixtures extends Fixture
{
    /**
     * @var AccountRepository
     */
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function load(ObjectManager $manager)
    {
        /** @var Contact $contactLocal */
        $contactLocal = $this->getReference('company-local');
        /** @var Contact $contactForeign */
        $contactForeign = $this->getReference('company-foreign');

        $transactions = [
            (new Transaction(
                "Převod zůstatku účtu při otevírání účetních knih",
                (new \DateTime())->setDate(2020,1,1),
                $contactLocal
            ))
            ->addRow(new Transaction\Row(
                "Pokladna",
                $this->accountRepository->findOneBy(['numeral' => '211']),
                $this->accountRepository->findOneBy(['numeral' => '701']),
                20000000
            ))
            ->addRow(new Transaction\Row("Základní kapitál",
                $this->accountRepository->findOneBy(['numeral' => '701']),
                $this->accountRepository->findOneBy(['numeral' => '411']),
                20000000
            )),
            (new Transaction(
                "Umytí automobilu",
                (new \DateTime())->setDate(2020,1,1),
                $contactLocal
            ))
            ->addRow(new Transaction\Row(
                null,
                $this->accountRepository->findOneBy(['numeral' => '518']),
                $this->accountRepository->findOneBy(['numeral' => '211']),
                100000
            ))
            ->addRow(new Transaction\Row(
                "DPH 21% // VAT 21%",
                $this->accountRepository->findOneBy(['numeral' => '343']),
                $this->accountRepository->findOneBy(['numeral' => '211']),
                21000
            )),
            (new Transaction(
                "Nákup drobných služeb v zahraničí",
                (new \DateTime())->setDate(2020,1,1),
                $contactForeign
            ))
            ->addRow(new Transaction\Row(
                null,
                $this->accountRepository->findOneBy(['numeral' => '518']),
                $this->accountRepository->findOneBy(['numeral' => '211']),
                10000000
            ))
            ->addRow(new Transaction\Row(
                "DPH 21% // VAT 21% -- Samovyměření",
                $this->accountRepository->findOneBy(['numeral' => '343']),
                $this->accountRepository->findOneBy(['numeral' => '349']),
                2100000
            ))
            ->addRow(new Transaction\Row(
                "DPH 21% // VAT 21% -- Nárok na odpočet",
                $this->accountRepository->findOneBy(['numeral' => '349']),
                $this->accountRepository->findOneBy(['numeral' => '343']),
                2100000
            )),
        ];

        foreach($transactions as $transaction)
        {
            $manager->persist($transaction);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ContactsFixtures::class,
        );
    }
}
