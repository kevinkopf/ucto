<?php

namespace App\Command;

use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitAccountsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    protected static $defaultName = 'app:init:accounts';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Inject default accounts into the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $balanceKind = new Entity\Account\Kind("Rozvahový");
        $incomeStatementKind = new Entity\Account\Kind("Výsledkový");

        $assetsType = new Entity\Account\Type("Aktivní");
        $liabilitiesType = new Entity\Account\Type("Pasivní");
        $bothAssetsAndLiabilitiesType = new Entity\Account\Type("Aktivní i Pasivní");
        $expensesTaxableType = new Entity\Account\Type("Nákladový daňový");
        $expensesNonTaxableType = new Entity\Account\Type("Nákladový nedaňový");
        $revenueTaxableType = new Entity\Account\Type("Výnosový daňový");
        $statementType = new Entity\Account\Type("Závěrkový");

        $accounts = [
            ["numeral" => "012", "name" => "Nehmotné výsledky výzkumu a vývoje", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "013", "name" => "Software", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "014", "name" => "Ocenitelná práva", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "015", "name" => "Goodwill", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "019", "name" => "Jiný dlouhodobý nehmotný majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "021", "name" => "Stavby", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "022", "name" => "Samostatné movité věci a soubory movitých věcí", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "025", "name" => "Pěstitelské celky trvalých porostů", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "026", "name" => "Dospělá zvířata a jejich skupiny", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "029", "name" => "Jiný dlouhodobý hmotný majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "031", "name" => "Pozemky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "032", "name" => "Umělecká díla a sbírky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "041", "name" => "Pořízení dlouhodobého nehmotného majetku", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "042", "name" => "Pořízení dlouhodobého hmotného majetku", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "051", "name" => "Poskytnuté zálohy na dlouhodobý nehmotný majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "052", "name" => "Poskytnuté zálohy na dlouhodobý hmotný majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "053", "name" => "Poskytnuté zálohy na dlouhodobý finanční majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "061", "name" => "Podíly v ovládaných a řízených osobách", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "062", "name" => "Podíly v účetních jednotkách pod podstatným vlivem", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "063", "name" => "Ostatní cenné papíry a podíly", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "065", "name" => "Dluhové cenné papíry držené do splatnosti", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "066", "name" => "Půjčky a úvěry - ovládající a řídící osoby, podstatný vliv", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "067", "name" => "Ostatní půjčky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "069", "name" => "Jiný dlouhodobý finanční majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "072", "name" => "Oprávky k nehmotným výsledkům výzkumu a vývoje", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "073", "name" => "Oprávky k softwaru", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "074", "name" => "Oprávky k ocenitelným právům", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "075", "name" => "Oprávky ke goodwillu", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "079", "name" => "Oprávky k jinému dlouhodobému nehmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "081", "name" => "Oprávky ke stavbám", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "082", "name" => "Oprávky k samost. movitým věcem a souborům movitých věcí", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "085", "name" => "Oprávky k pěstitelským celkům trvalých porostů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "086", "name" => "Oprávky k základnímu stádu a tažným zvířatům", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "089", "name" => "Oprávky k jinému dlouhodobému hmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "091", "name" => "Opravná položka k dlouhodobému nehmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "092", "name" => "Opravná položka k dlouhodobému hmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "093", "name" => "Opravná položka k dlouhodobému nedokončenému nehmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "094", "name" => "Opravná položka k dlouhodobému nedokončenému hmotnému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "095", "name" => "Opravná položka k poskytnutým zálohám na dlouhodobý majetek", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "096", "name" => "Opravná položka k dlouhodobému finančnímu majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "097", "name" => "Oceňovací rozdíl k nabytému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "098", "name" => "Oprávky k oceňovacímu rozdílu k nabytému majetku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "111", "name" => "Pořízení materiálu", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "112", "name" => "Materiál na skladě", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "119", "name" => "Materiál na cestě", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "121", "name" => "Nedokončená výroba", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "122", "name" => "Polotovary vlastní výroby", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "123", "name" => "Výrobky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "124", "name" => "Mladá a ostatní zvířata a jejich skupiny", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "131", "name" => "Pořízení zboží", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "132", "name" => "Zboží na skladě a v prodejnách", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "139", "name" => "Zboží na cestě", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "151", "name" => "Poskytnuté zálohy na materiál", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "152", "name" => "Poskytnuté zálohy na zvířata", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "153", "name" => "Poskytnuté zálohy na zboží", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "191", "name" => "Opravná položka k materiálu", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "192", "name" => "Opravná položka k nedokončené výrobě", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "193", "name" => "Opravná položka k polotovarům vlastní výroby", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "194", "name" => "Opravná položka k výrobkům", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "195", "name" => "Opravná položka ke zvířatům", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "196", "name" => "Opravná položka ke zboží", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "197", "name" => "Opravná položka k zálohám na materiál", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "198", "name" => "Opravná položka k zálohám na zboží", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "199", "name" => "Opravná položka k zálohám na zvířata", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "211", "name" => "Pokladna", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "213", "name" => "Ceniny", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "221", "name" => "Bankovní účty", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "231", "name" => "Krátkodobé bankovní úvěry", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "232", "name" => "Eskontní úvěry", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "241", "name" => "Emitované krátkodobé dluhopisy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "249", "name" => "Ostatní krátkodobé finanční výpomoci", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "251", "name" => "Registrované majetkové cenné papíry k obchodování", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "252", "name" => "Vlastní akcie a vlastní obchodní podíly", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "253", "name" => "Registrované dluhové cenné papíry k obchodování", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "255", "name" => "Vlastní dluhopisy", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "256", "name" => "Dluhové cenné papíry se splat. do 1 roku držené do splatnosti", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "257", "name" => "Ostatní cenné papíry k obchodování", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "258", "name" => "Krátkodobý finanční majetek", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "261", "name" => "Peníze na cestě", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "291", "name" => "Opravná položka ke krátkodobému finančnímu majetku", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "311", "name" => "Pohledávky z obchodních vztahů", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "312", "name" => "Směnky k inkasu", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "313", "name" => "Pohledávky za eskontované cenné papíry", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "314", "name" => "Poskytnuté zálohy - dlouhodobé a krátkodobé", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "315", "name" => "Ostatní pohledávky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "321", "name" => "Závazky z obchodních vztahů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "322", "name" => "Směnka k úhradě", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "324", "name" => "Přijaté provozní zálohy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "325", "name" => "Ostatní závazky", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "331", "name" => "Zaměstnanci", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "333", "name" => "Ostatní závazky vůči zaměstnancům", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "335", "name" => "Pohledávky za zaměstnanci", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "336", "name" => "Zúčtování s institucemi sociál. zabezpečení a zdravot. pojištění", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "341", "name" => "Daň z příjmů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "342", "name" => "Ostatní přímé daně", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "343", "name" => "Daň z přidané hodnoty", "type" => $bothAssetsAndLiabilitiesType , "kind" => $balanceKind],
            ["numeral" => "345", "name" => "Ostatní daně a poplatky", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "346", "name" => "Dotace ze státního rozpočtu", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "347", "name" => "Ostatní dotace", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "349", "name" => "Vyrovnávací účet pro DPH", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "351", "name" => "Pohledávky - ovládající a řídící osoba", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "352", "name" => "Pohledávky - podstatný vliv", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "353", "name" => "Pohledávky za upsaný základní kapitál", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "354", "name" => "Pohledávky za společníky při úhradě ztráty", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "355", "name" => "Ostatní pohledávky za společníky a členy družstva", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "358", "name" => "Pohledávky za účastníky sdružení", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "361", "name" => "Závazky - ovládající a řídící osoba", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "362", "name" => "Závazky - podstatný vliv", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "364", "name" => "Závazky ke společníkům při rozdělování zisku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "365", "name" => "Ostatní závazky ke společníkům a členům družstva", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "366", "name" => "Závazky ke společníkům a členům družstva ze závislé činnosti", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "367", "name" => "Závazky z upsaných nesplacených cenných papírů a vkladů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "368", "name" => "Závazky k účastníkům sdružení", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "371", "name" => "Pohledávky z prodeje podniku", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "372", "name" => "Závazky z koupě podniku", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "373", "name" => "Pohledávky a závazky z pevných termínových operací", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "374", "name" => "Pohledávky z pronájmu", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "375", "name" => "Pohledávky z emitovaných dluhopisů", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "376", "name" => "Nakoupené opce", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "377", "name" => "Prodané opce", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "378", "name" => "Jiné pohledávky", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "379", "name" => "Jiné závazky", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "381", "name" => "Náklady příštích období", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "382", "name" => "Komplexní náklady příštích období", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "383", "name" => "Výdaje příštích období", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "384", "name" => "Výnosy příštích období", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "385", "name" => "Příjmy příštích období", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "388", "name" => "Dohadné účty aktivní", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "389", "name" => "Dohadné účty pasivní", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "391", "name" => "Opravná položka k pohledávkám", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "395", "name" => "Vnitřní zúčtování", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "398", "name" => "Spojovací účet při sdružení", "type" => $assetsType , "kind" => $balanceKind],
            ["numeral" => "411", "name" => "Základní kapitál", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "412", "name" => "Emisní ažio", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "413", "name" => "Ostatní kapitálové fondy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "414", "name" => "Oceňovací rozdíly z přecenění majetku a závazků", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "417", "name" => "Rozdíly z přeměn společností", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "418", "name" => "Oceňovací rozdíly z přecenění při přeměnách společností", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "419", "name" => "Změny základního kapitálu", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "421", "name" => "Zákonný rezervní fond", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "422", "name" => "Nedělitelný fond", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "423", "name" => "Statutární fondy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "426", "name" => "Jiný výsledek hospodaření minulých let", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "427", "name" => "Ostatní fondy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "428", "name" => "Nerozdělený zisk minulých let", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "429", "name" => "Neuhrazená ztráta minulých let", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "431", "name" => "Výsledek hospodaření ve schvalovacím řízení", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "451", "name" => "Rezervy podle zvláštních právních předpisů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "452", "name" => "Rezerva na důchody a podobné závazky", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "453", "name" => "Rezerva na daň z příjmů", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "459", "name" => "Ostatní rezervy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "461", "name" => "Bankovní úvěry", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "471", "name" => "Dlouhodobé závazky - ovládající a řídící osoba", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "472", "name" => "Dlouhodobé závazky - podstatný vliv", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "473", "name" => "Emitované dluhopisy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "474", "name" => "Závazky z pronájmu", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "475", "name" => "Dlouhodobé přijaté zálohy", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "478", "name" => "Dlouhodobé směnky k úhradě", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "479", "name" => "Jiné dlouhodobé závazky", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "481", "name" => "Odložený daňový závazek a pohledávka", "type" => $bothAssetsAndLiabilitiesType , "kind" => $balanceKind],
            ["numeral" => "491", "name" => "Účet individuálního podnikatele", "type" => $liabilitiesType , "kind" => $balanceKind],
            ["numeral" => "501", "name" => "Spotřeba materiálu", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "502", "name" => "Spotřeba energie", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "503", "name" => "Spotřeba ostatních neskladovatelných dodávek", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "504", "name" => "Prodané zboží", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "511", "name" => "Opravy a udržování", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "512", "name" => "Cestovné", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "513", "name" => "Náklady na reprezentaci", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "518", "name" => "Ostatní služby", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "521", "name" => "Mzdové náklady", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "522", "name" => "Příjmy společníků a členů družstva ze závislé činnosti", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "523", "name" => "Odměny členům orgánů společnosti a družstva", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "524", "name" => "Zákonné sociální pojištění", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "525", "name" => "Ostatní sociální pojištění", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "526", "name" => "Sociální náklady individuálního podnikatele", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "527", "name" => "Zákonné sociální náklady", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "528", "name" => "Ostatní sociální náklady", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "531", "name" => "Daň silniční", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "532", "name" => "Daň z nemovitostí", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "538", "name" => "Ostatní daně a poplatky", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "541", "name" => "Zůstatková cena prodaného dlouhodobého nehmotného a hmotného majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "542", "name" => "Prodaný materiál", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "543", "name" => "Dary", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "544", "name" => "Smluvní pokuty a úroky z prodlení", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "545", "name" => "Ostatní pokuty a penále", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "546", "name" => "Odpis pohledávky", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "548", "name" => "Ostatní provozní náklady", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "549", "name" => "Manka a škody z provozní činnosti", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "551", "name" => "Odpisy dlouhodobého nehmotného a hmotného majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "552", "name" => "Tvorba a zúčtování rezerv podle zvláštních právních předpisů", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "554", "name" => "Tvorba a zúčtování ostatních rezerv", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "555", "name" => "Tvorba a zúčtování komplexních nákladů příštích období", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "557", "name" => "Zúčtování oprávky k oceňovacímu rozdílu k nabytému majetku", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "558", "name" => "Tvorba a zúčtování zákonných opravných položek v provozní činnosti", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "559", "name" => "Tvorba a zúčtování opravných položek v provozní činnosti", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "561", "name" => "Prodané cenné papíry a podíly", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "562", "name" => "Úroky", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "563", "name" => "Kurzové ztráty", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "564", "name" => "Náklady z přecenění cenných papírů", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "566", "name" => "Náklady z finančního majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "567", "name" => "Náklady z derivátových operací", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "568", "name" => "Ostatní finanční náklady", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "569", "name" => "Manka a škody na finančním majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "574", "name" => "Tvorba a zúčtování finančních rezerv", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "579", "name" => "Tvorba a zúčtování opravných položek ve finanční činnosti", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "581", "name" => "Změna stavu nedokončené výroby", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "582", "name" => "Změna stavu polotovarů vlastní výroby", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "583", "name" => "Změna stavu výrobků", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "584", "name" => "Změna stavu zvířat", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "585", "name" => "Aktivace materiálu a zboží", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "586", "name" => "Aktivace vnitropodnikových služeb", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "587", "name" => "Aktivace dlouhodobého nehmotného majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "588", "name" => "Aktivace dlouhodobého hmotného majetku", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "591", "name" => "Daň z příjmů z běžné činnosti - splatná", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "592", "name" => "Daň z příjmů z běžné činnosti - odložená", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "595", "name" => "Dodatečné odvody daně z příjmů", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "596", "name" => "Převod podílu na výsledku hospodaření společníkům", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "597", "name" => "Převod provozních nákladů", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "598", "name" => "Převod finančních nákladů", "type" => $expensesTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "599", "name" => "Rezerva na daň z příjmu", "type" => $expensesNonTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "601", "name" => "Tržby za vlastní výrobky", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "602", "name" => "Tržby z prodeje služeb", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "604", "name" => "Tržby za zboží", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "641", "name" => "Tržby z prodeje dlouhodobého nehmotného a hmotného majetku", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "642", "name" => "Tržby z prodeje materiálu", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "644", "name" => "Smluvní pokuty a úroky z prodlení", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "646", "name" => "Výnosy z odepsaných pohledávek", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "648", "name" => "Ostatní provozní výnosy", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "661", "name" => "Tržby z prodeje cenných papírů a podílů", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "662", "name" => "Úroky", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "663", "name" => "Kursové zisky", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "664", "name" => "Výnosy z přecenění cenných papírů", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "665", "name" => "Výnosy z dlouhodobého finančního majetku", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "666", "name" => "Výnosy z krátkodobého finančního majetku", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "667", "name" => "Výnosy z derivátových operací", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "668", "name" => "Ostatní finanční výnosy", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "697", "name" => "Převod provozních výnosů", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "698", "name" => "Převod finančních výnosů", "type" => $revenueTaxableType, "kind" => $incomeStatementKind],
            ["numeral" => "701", "name" => "Počáteční účet rozvažný", "type" => $statementType, "kind" => null],
            ["numeral" => "702", "name" => "Konečný účet rozvažný", "type" => $statementType, "kind" => null],
            ["numeral" => "710", "name" => "Účet zisků a ztrát", "type" => $statementType, "kind" => null],
        ];

        foreach($accounts as $account)
        {
            $this->entityManager->persist(new Entity\Account(
                $account['numeral'],
                $account['name'],
                $account['type'],
                $account['kind']
            ));

            $io->success($account['numeral'] . " -- " . $account['name'] . " has been created.");
        }

        $this->entityManager->flush();

        $io->success('All done.');

        return 0;
    }
}
