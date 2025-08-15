<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityVin;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityVin
 */
class IDEntityVinTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_VIN;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        foreach ($this->getNormalizationCases() as [$src, $out]) {
            $this->assertSame($out, $entity::normalize($src));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityVin
    {
        return new IDEntityVin($value ?? $this->getValidValues()[0]);
    }

    /**
     * Получить тестовые случаи для нормализации VIN кодов
     *
     * @return array<array<string>>
     */
    public static function getNormalizationCases(): array
    {
        return [
            // Базовая нормализация - весь комплекс
            ['  Xw8ЗдZQiO.LG02-8617  ', 'XW83DZ010LG028617'],

            // Базовая нормализация - тримминг
            ['  XW8ZZZ61ZLG028617  ', 'XW8ZZZ61ZLG028617'],

            // Базовая нормализация - удаление разделителей
            ['XW8/Z_ZZ-61ZLG-028617', 'XW8ZZZ61ZLG028617'],

            // Базовая нормализация - приведение к верхнему регистру
            ['abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщъыьэюя', 'ABCDEFGH1JKLMN0P0RSTUVWXYZAБBГDEЁЖ3ИЙKЛMH0ПPCTYФXЦЧШЩЪЫЬЭЮЯ'],

            // Замена русских букв АВЕКМНРСТУХ по написанию, и замены З, Д, О на 3, D, 0
            ['АВЕКМНРСТУХЗДО', 'ABEKMHPCTYX3D0'],

            // Остальные русские буквы не заменяются
            ['БГЁЖИЙЛПФЦЧШЩЪЫЬЭЮЯ', 'БГЁЖИЙЛПФЦЧШЩЪЫЬЭЮЯ'],

            // Замена Q на 0
            ['AQ000000000000001', 'A0000000000000001'],

            // Замена O на 0
            ['AO000000000000001', 'A0000000000000001'],

            // Замена I на 1
            ['AI000000000000001', 'A1000000000000001'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '5UXFA13585LY08847',
            'WBAFW51040C245397',
            'Z94CB41ABDR105897',
            'XUUNF486J90008440',
            'Z94CT41DBFR411079',
            'X96ERB6X180001283',
            'KMHDN45D22U348878',
            'KMHE341CBFA025224',
            'XWB3L32EDCA218918',
            'WBASP81010C353098',
            'VF1UDC3K640850971',
            'W0LPE6DJ1BG069892',
            'WDD2120341A829457',
            'SALLMAMH4BA351652',
            'JN1TANT31U0023878',
            'JSAJTD54V00264939',
            'JS1GN7EA292101420',
            'WBANX71030CS59649',
            'JMZGJ426831122762',
            'XWEKU811CD0002591',
            'Z8NFEAC1353471091',
            'X9FLXXEEBLES67742',
            'WDD2050091R022890',
            'XW7BF4FK70S032612',
            '4T1BE32KX3U711658',
            'WV1ZZZ7HZ6H051189',
            'WBAVM910X0VT69817',
            'JTEHT05J602091383',
            'XTA21124070486756',
            'Z6F5XXEEC5FJ06505',
            'XW7BF4FK40S051361',
            'W0L0SDL68C4237601',
            'XTAKS015LG0916164',
            'JTHBK262362003591',
            '1L1FM81W6WY615157',
            'WAUZZZ4LX8D053327',
            'XW8ZZZ5NZCG126820',
            'WP0ZZZ97ZDL001414',
            'WBAUE11010E754323',
            'YV1CZ59H641113082',
            'X8UP8X40005308610',
            'X4XZV41140L454941',
            'Z9Z210410C0111778',
            'Y6DTF69Y070105307',
            'XTA111730C0232880',
            'WBAGN41000DM76101',
            'WDB2030461A550425',
            'XWEPC811DB0004120',
            'W0L0ZCF6961115174',
            'X4XVP99470VU67997',
            'NLAFD76708W094198',
            'WAUZZZ8K7DA072544',
            'JHMZF1D62BS008617',
            'XUUSA69WJA0013555',
            'JF1GRFLH38G038003',
            'TMBLD45L5B6032910',
            'JHMZF1D42BS010382',
            'WVWZZZ16ZDM067375',
            'JMZBK14Z281640355',
            'W0L0AHM7592033440',
            'X4XVA98487VB40475',
            'X9FDXXEEBDDG37057',
            'JTHBK1GG602112252',
            'W0L0SDL08D6008077',
            'JTMHV05J604147731',
            'KMHFC41DP7A241713',
            'XMCLRDA2A3F035517',
            'TSMEYB21S00610448',
            'SJNFAAE11U2214823',
            'WDD2452331J636818',
            'JTEES42A002174521',
            'XW8BK61Z7CK251257',
            'XTA21104060933233',
            'KMHSH81XDCU871439',
            'WF0PXXWPDP8G51214',
            '2T1KR32E43C162992',
            'SALLSAAF4BA268959',
            'X9FMXXEEBMCG05797',
            'WDC2923241A022925',
            'JTEBU29J805003909',
            'X4X5A79400D363203',
            'WAUZZZ4E35N002551',
            '4S4WX83C164401449',
            'YV1CM714681472368',
            'WAUZZZ8T4DA007398',
            'JMBSNCS3A7U004721',
            'XW8CA41Z0DK254353',
            'JMZGG12F661644496',
            'VF3BUAFZPCZ802138',
            'Z8NFAABD0F0001146',
            'X7LHSRDVN47285489',
            'X7MSB81VP8M009050',
            'WBAPA71070WC93119',
            'SALWA2EF8EA381025',
            'WF03XXGCD36Y43748',
            'XWF0AHM75B0002747',
            'A0000000000000001',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            'А123АА177',
            'JMZGG12F6616444962',
            '11АА112233',
            'FN15-002153',
            '38:49:924785:832907',

            '',
            Str::random(32),
        ];
    }
}
