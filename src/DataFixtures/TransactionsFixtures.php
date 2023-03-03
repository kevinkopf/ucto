<?php

namespace App\DataFixtures;

use App\Accounts\Repository\AccountRepository;
use App\Contacts\Entity\Contact;
use App\Transactions\Entity\Transaction;
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
                "AF159987448",
                (new \DateTime())->setDate(2020, 1, 1),
                $contactLocal
            ))
                ->addRow(new Transaction\Row(
                    "Pokladna",
                    $this->accountRepository->findOneBy(['numeral' => '221']),
                    $this->accountRepository->findOneBy(['numeral' => '701']),
                    20000000
                ))
                ->addRow(new Transaction\Row(
                    "Základní kapitál",
                    $this->accountRepository->findOneBy(['numeral' => '701']),
                    $this->accountRepository->findOneBy(['numeral' => '411']),
                    20000000
                )),
            (new Transaction(
                "Umytí automobilu",
                "FVUM20200201",
                (new \DateTime())->setDate(2020, 2, 1),
                $contactLocal
            ))
                ->addRow(new Transaction\Row(
                    "Umytí automobilu",
                    $this->accountRepository->findOneBy(['numeral' => '518']),
                    $this->accountRepository->findOneBy(['numeral' => '221']),
                    100000
                ))
                ->addRow(new Transaction\Row(
                    "DPH 21% // VAT 21%",
                    $this->accountRepository->findOneBy(['numeral' => '343']),
                    $this->accountRepository->findOneBy(['numeral' => '221']),
                    21000
                )),
            (new Transaction(
                "Nákup drobných služeb v zahraničí",
                "FOREIGNFV202031",
                (new \DateTime())->setDate(2020, 3, 1),
                $contactForeign
            ))
                ->addRow(new Transaction\Row(
                    "Nákup drobných služeb v zahraničí",
                    $this->accountRepository->findOneBy(['numeral' => '518']),
                    $this->accountRepository->findOneBy(['numeral' => '221']),
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

        for ($i = 0; $i < 1000; $i++) {
            foreach ($transactions as $transaction) {
                $manager->persist($transaction);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            ContactsFixtures::class,
        );
    }
}
