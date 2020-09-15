<?php

namespace App\Entity\Statement;

use App\Entity\Account;
use App\Repository\Statement\TrialBalanceRecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrialBalanceRecordRepository::class)
 */
class TrialBalanceRecord
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Account $account;

    /**
     * @ORM\Column(type="integer")
     */
    private int $openingBalance;

    /**
     * @ORM\Column(type="integer")
     */
    private int $debtorBalance;

    /**
     * @ORM\Column(type="integer")
     */
    private int $creditorBalance;

    /**
     * @ORM\Column(type="integer")
     */
    private int $closingBalance;

    /**
     * @ORM\ManyToOne(targetEntity=TrialBalance::class, inversedBy="records", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TrialBalance $trialBalance;

    public function __construct(
        Account $account,
        int $openingBalance,
        int $debtorBalance,
        int $creditorBalance,
        int $closingBalance
    ) {
        $this->account = $account;
        $this->openingBalance = $openingBalance;
        $this->debtorBalance = $debtorBalance;
        $this->creditorBalance = $creditorBalance;
        $this->closingBalance = $closingBalance;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getOpeningBalance(): int
    {
        return $this->openingBalance;
    }

    public function getDebtorBalance(): int
    {
        return $this->debtorBalance;
    }

    public function getCreditorBalance(): int
    {
        return $this->creditorBalance;
    }

    public function getClosingBalance(): int
    {
        return $this->closingBalance;
    }

    public function getTrialBalance(): ?TrialBalance
    {
        return $this->trialBalance;
    }

    public function setTrialBalance(TrialBalance $trialBalance): self
    {
        $this->trialBalance = $trialBalance;

        return $this;
    }

    public function getZaccount(): ?Account
    {
        return $this->zaccount;
    }

    public function setZaccount(?Account $zaccount): self
    {
        $this->zaccount = $zaccount;

        return $this;
    }
}
