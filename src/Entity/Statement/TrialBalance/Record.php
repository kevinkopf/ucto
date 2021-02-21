<?php

namespace App\Entity\Statement\TrialBalance;

use App\Entity\Account;
use App\Entity\Statement\TrialBalance as TrialBalanceStatement;
use App\Repository\Statement\TrialBalance\RecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 * @ORM\Table(name="statements_trial_balances_records")
 */
class Record
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
     * @ORM\Column(type="string")
     */
    private int $openingBalance;

    /**
     * @ORM\Column(type="string")
     */
    private int $debtorBalance;

    /**
     * @ORM\Column(type="string")
     */
    private int $creditorBalance;

    /**
     * @ORM\Column(type="string")
     */
    private int $closingBalance;

    /**
     * @ORM\ManyToOne(targetEntity=TrialBalanceStatement::class, inversedBy="records", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TrialBalanceStatement $trialBalance;

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

    public function getOpeningBalance(): string
    {
        return $this->openingBalance;
    }

    public function getDebtorBalance(): string
    {
        return $this->debtorBalance;
    }

    public function getCreditorBalance(): string
    {
        return $this->creditorBalance;
    }

    public function getClosingBalance(): string
    {
        return $this->closingBalance;
    }

    public function getTrialBalance(): ?TrialBalanceStatement
    {
        return $this->trialBalance;
    }

    public function setTrialBalance(TrialBalanceStatement $trialBalance): self
    {
        $this->trialBalance = $trialBalance;

        return $this;
    }
}
