<?php

namespace App\Entity\Statement\Vat;

use App\Entity\Statement\Vat\Inspectional\Sheet;
use App\Repository\Statement\Vat\InspectionalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InspectionalRepository::class)
 * @ORM\Table(name="statements_vat_inspectional")
 */
class Inspectional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $coveringMonth;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private string $coveringYear;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Uskutečněná zdanitelná plnění v režimu přenesení daňové povinnosti,
     * u kterých je povinen přiznat daň příjemce plnění podle § 92a
     *
     * Lidským jazykem: Jsme firma v CZ a jiná firma v CZ nám vystaví fakturu s přenesenou daňovou povinnosti (Reverse Charge)
     * Typicky to jsou firmy typu PRE, které za výměnu jističe vystaví doklad a daň musíme již přiznat my.
     */
    private Sheet $sheetA1;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Přijatá zdanitelná plnění, u kterých je povinen přiznat daň příjemce dle § 108 odst. 2 a 3 (§ 24, § 25)
     * (v případě plnění podle § 108 odst. 3 písm. b) jde o plnění přijatá od 29.7.2016).
     *
     * Lidským jazykem: Podobná situace jako v A1, ALE. Jsme firma v CZ a jiná firma _mimo_ CZ nám vystaví fakturu.
     */
    private Sheet $sheetA2;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Uskutečněná plnění ve zvláštním režimu pro investiční zlato podle § 101c písm. c) bod 2
     *
     * Lidským jazykem: Sem by se hodil komentář od těch, kdo obchoduje s investičním zlatem :-)
     */
    private Sheet $sheetA3;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Uskutečněná zdanitelná plnění a přijaté úplaty s povinností přiznat daň dle § 108 odst. 1
     * s hodnotou nad 10.000,- Kč včetně daně a všechny provedené opravy v souvislosti s nedobytnými pohledávkami
     * bez ohledu na limit
     *
     * Lidským jazykem: Jsme firma v CZ a vystavíme fakturu firmě v CZ nad 10.000 CZK
     */
    private Sheet $sheetA4;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Ostatní uskutečněná zdanitelná plnění a přijaté úplaty s povinností přiznat daň dle § 108 odst. 1
     * s hodnotou do 10.000,- Kč včetně daně, nebo plnění, u nichž nevznikla povinnost vystavit daňový doklad
     *
     * Lidským jazykem: Jsme firma v CZ a vystavíme fakturu firmě v CZ do 10.000 CZK
     */
    private Sheet $sheetA5;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Přijatá zdanitelná plnění v režimu přenesení daňové povinnosti,
     * u kterých je povinen přiznat daň příjemce podle § 92a
     *
     * Lidským jazykem: Jsme firma v CZ a buď:
     *     - Vystavíme fakturu jiné firmě v CZ, ale v režimu Reverse Charge. Typicky PRE (viz. příklad nahoře), nebo
     *     - Vystavíme fakturu firmě mimo CZ v režimu Reverse Charge, tedy oni musí zaplatit daň ve své zemi.
     */
    private Sheet $sheetB1;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Přijatá zdanitelná plnění a poskytnuté úplaty, u kterých příjemce uplatňuje nárok na odpočet daně
     * dle § 73 odst. 1 písm. a) s hodnotou nad 10.000,- Kč včetně daně a všechny opravy odpočtu
     * v souvislosti s nedobytnými pohledávkami bez ohledu na limit
     *
     * Lidským jazykem: Jsme firma v CZ a nakoupíme u jiné firmy v CZ za 10.000 CZK s DPH a více
     */
    private Sheet $sheetB2;

    /**
     * @ORM\OneToOne(targetEntity=Sheet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * Přijatá zdanitelná plnění a poskytnuté úplaty, u kterých příjemce uplatňuje nárok na odpočet daně
     * dle § 73 odst. 1 písm. a) s hodnotou do 10.000,- Kč včetně daně
     *
     * Lidským jazykem: Jsme firma v CZ a nakoupíme u jiné firmy v CZ za 9.999 CZK s DPH a méně.
     */
    private Sheet $sheetB3;

    public function __construct(
        string $coveringMonth,
        string $coveringYear,
        Sheet $sheetA1,
        Sheet $sheetA2,
        Sheet $sheetA3,
        Sheet $sheetA4,
        Sheet $sheetA5,
        Sheet $sheetB1,
        Sheet $sheetB2,
        Sheet $sheetB3
    ) {
        $this->coveringMonth = $coveringMonth;
        $this->coveringYear = $coveringYear;
        $this->createdAt = new \DateTime();
        $this->sheetA1 = $sheetA1;
        $this->sheetA2 = $sheetA2;
        $this->sheetA3 = $sheetA3;
        $this->sheetA4 = $sheetA4;
        $this->sheetA5 = $sheetA5;
        $this->sheetB1 = $sheetB1;
        $this->sheetB2 = $sheetB2;
        $this->sheetB3 = $sheetB3;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCoveringMonth(): string
    {
        return $this->coveringMonth;
    }

    public function getCoveringYear(): string
    {
        return $this->coveringYear;
    }

    public function getSheetA1(): ?Sheet
    {
        return $this->sheetA1;
    }

    public function getSheetA2(): ?Sheet
    {
        return $this->sheetA2;
    }

    public function getSheetA3(): ?Sheet
    {
        return $this->sheetA3;
    }

    public function getSheetA4(): ?Sheet
    {
        return $this->sheetA4;
    }

    public function getSheetA5(): ?Sheet
    {
        return $this->sheetA5;
    }

    public function getSheetB1(): ?Sheet
    {
        return $this->sheetB1;
    }

    public function getSheetB2(): ?Sheet
    {
        return $this->sheetB2;
    }

    public function getSheetB3(): ?Sheet
    {
        return $this->sheetB3;
    }
}
