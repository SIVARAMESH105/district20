<?php
namespace App\Helpers;

use Auth;
use DB;
use App\Models\States;
use App\Models\Chapter;
/**
 * Class MapHelper
 * namespace App\Helpers
 * @package Auth
 * @package DB
 */
class MapHelper
{    
    /**
     * This function is used to get states map by chapterId
     *
     * @return bool
     * @author vinothcl
     */
    public static function getStatesMapByChapterId($chapter_id) {
        $states = (new States)->getChapterStates($chapter_id)->get();
        $statesMaps = '';
        foreach($states as $state){
            $goToChapterMap = '<span style="float:left" onclick="goToChapterMap();"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>';
            $resetStateMap = '<span style="float:right" onclick="resetStateMap();"><i class="fa fa-refresh" aria-hidden="true"></i></span>';
            $statesMaps .= '<div class="col-xs-12 col-sm-8 stateMaps" id="state-'.$state->id.'" style="display:none">
                        <p class="text-center p-2">'.$goToChapterMap.$state->state_name.$resetStateMap.'</p>
                        <img class="img-thumbnail thumbnailNew" src="'.asset($state->state_image) .'" title="Image Map" usemap="#'.str_replace(" ", "", $state->state_name).'" >
                        '.self::getMapCoordsStateName($state->state_name).'
                      </div>';
        }
        return $statesMaps;
    }
    public static function getChapterMapByChapterId($chapter_id) {
        $chapter = Chapter::where('id', $chapter_id)->first();
        $chapterMaps = '<div>
                        <p class="text-center p-2">'.$chapter->chapter_name.'</p>
                        <img class="img-thumbnail thumbnailNew" src="'.asset($chapter->chapter_image) .'" title="Image Map" usemap="#'.str_replace(" ", "", $chapter->chapter_name).'" >
                        '.self::getMapCoordsChapterName($chapter->chapter_name).'
                      </div>';
        return $chapterMaps;
    }
    
    public static function getMapCoordsChapterName($chapter_name) {
        switch ($chapter_name) {
            case 'Southwestern Line':
                return self::SouthwesternLineChapterMapCoords($chapter_name);
                break;
            case 'Missouri Valley Line':
                return self::MissouriValleyLineChapterMapCoords($chapter_name);
                break; 
            case 'Southeastern Line':
                return self::SouthEasternLineChapterMapCoords($chapter_name);
                break;
            case 'American Line Builders':
                return self::AmericanLineBuildersChapterMapCoords($chapter_name);
                break;
                               
            default:
                return '';
            }
    }
    public static function getMapCoordsStateName($state_Name) {
        switch ($state_Name) {
            //Southwestern Line Unions
            case 'Texas':
                return self::TexasStateMapCoords($state_Name);
                break;
            case 'Kansas':
                return self::KansasStateMapCoords($state_Name);
                break;
            case 'New Mexico':
                return self::NewMexicoStateMapCoords($state_Name);
                break;
            case 'Arizona':
                return self::ArizonaStateMapCoords($state_Name);
                break;
            case 'Oklahoma':
                return self::OklahomaStateMapCoords($state_Name);
                break;
            //Missouri Line Unions
            case 'North Dakota':
                return self::NorthDakotaStateMapCoords($state_Name);
                break;
            case 'South Dakota':
                return self::SouthDakotaStateMapCoords($state_Name);
                break;
            case 'Missouri':
                return self::MissouriStateMapCoords($state_Name);
                break;
            case 'Iowa':
                return self::IowaStateMapCoords($state_Name);
                break;
            case 'Minnesota':
                return self::MinnesotaStateMapCoords($state_Name);
                break;
            case 'Wisconsin':
                return self::WisconsinStateMapCoords($state_Name);
                break;
            case 'Nebraska':
                return self::NebraskaStateMapCoords($state_Name);
                break;              
            //South Eastern Line Unions 
            case 'Alabama':
                return self::AlabamaStateMapCoords($state_Name);
                break; 
            case 'Arkansas':
                return self::ArkansasStateMapCoords($state_Name);
                break;
            case 'Florida':
                return self::FloridaStateMapCoords($state_Name);
                break;
            case 'Georgia':
                return self::GeorgiaStateMapCoords($state_Name);
                break;
            case 'Louisiana':
                return self::LouisianaStateMapCoords($state_Name);
                break; 
            case 'Mississippi':
                return self::MississippiStateMapCoords($state_Name);
                break;
            case 'Carolinas':
                return self::CarolinasStateMapCoords($state_Name);
                break;
            case 'Tennessee':
                return self::TennesseeStateMapCoords($state_Name);
                break;
            //American Line Builders Unions Illinois
             case 'Illinois':
                return self::IllinoisStateMapCoords($state_Name);
                break;
             case 'Indiana':
                return self::IndianaStateMapCoords($state_Name);
                break;
             case 'Ohio':
                return self::OhioStateMapCoords($state_Name);
                break;
            case 'West Virginia':
                return self::WestVirginiaStateMapCoords($state_Name);
                break;
            case 'Virginia':
                return self::VirginiaStateMapCoords($state_Name);
                break;
            case 'Kentucky':
                return self::KentuckyStateMapCoords($state_Name);
                break;
            case 'Michigan':
                return self::MichiganStateMapCoords($state_Name);
                break;
            case 'Maryland':
                return self::MarylandStateMapCoords($state_Name);
                break;
            case 'Washington, D.C.':
                return self::WashingtonStateMapCoords($state_Name);  
                break;

            default:
                return '';
            }
    }
    public static function MissouriValleyLineChapterMapCoords($chapter_name){
        return '
                <map name="'.str_replace(" ", "", $chapter_name).'">
                    <area onclick="chapterMapStateClicked(6);" state-id="6" href="#1" coords="60,391,435,401,830,397,878,626,904,818,922,910,407,912,38,903" shape="poly">
                    <area onclick="chapterMapStateClicked(10);" state-id="10" href="#2" coords="832,399,1064,384,1063,325,1099,329,1116,397,1118,425,1166,434,1195,439,1216,441,1225,458,1262,447,1301,432,1347,447,1373,458,1378,475,1389,491,1399,475,1430,476,1439,489,1467,495,1482,510,1496,517,1531,491,1555,478,1600,491,1644,487,1668,506,1694,499,1714,502,1681,526,1607,563,1567,602,1502,674,1445,737,1428,755,1423,816,1426,849,1386,890,1363,938,1380,951,1389,980,1384,1036,1391,1088,1428,1110,1463,1134,1502,1167,1568,1206,1591,1276,1264,1305,955,1317,937,1010,895,966,924,905,876,611,843,530,847,454" shape="poly">
                    <area onclick="chapterMapStateClicked(11);" state-id="11" href="#3" coords="1443,739,1544,713,1591,691,1624,694,1618,713,1609,735,1640,753,1683,787,1805,809,1869,818,1925,838,1934,849,1965,859,1965,905,1964,920,1989,916,1997,962,1978,1006,1967,1051,1967,1062,1988,1040,2006,1006,2030,984,2045,942,2063,920,2085,890,2085,910,2067,942,2058,968,2054,993,2036,1032,2032,1084,2032,1121,2015,1149,2013,1187,2008,1252,2002,1276,2012,1324,2032,1392,2034,1407,1818,1425,1681,1442,1631,1407,1602,1366,1616,1318,1596,1296,1587,1226,1543,1193,1496,1156,1448,1119,1387,1086,1387,1001,1365,929,1424,860,1417,759" shape="poly">
                    <area onclick="chapterMapStateClicked(9);" state-id="9" href="#4" coords="939,1324,1295,1304,1583,1283,1616,1318,1624,1390,1681,1440,1712,1483,1755,1510,1746,1581,1729,1612,1655,1630,1652,1666,1670,1704,1642,1769,1618,1786,1626,1804,1613,1817,1578,1778,1299,1800,1055,1817,1029,1669,994,1581,970,1529,957,1492,931,1448,954,1398,941,1366" shape="poly">
                    <area onclick="chapterMapStateClicked(8);" state-id="8" href="#5" coords="1061,1811,1406,1793,1580,1778,1620,1813,1615,1870,1631,1911,1676,1957,1727,2011,1742,2050,1766,2037,1816,2057,1797,2111,1794,2157,1831,2197,1884,2227,1930,2286,1930,2323,1954,2351,1975,2358,1980,2371,1976,2406,1969,2436,1932,2463,1938,2484,1928,2508,1917,2530,1827,2543,1853,2502,1853,2469,1845,2454,1561,2480,1245,2502,1219,2053,1184,2031,1158,1972,1138,1922,1107,1898,1079,1859,1070,1821" shape="poly">
                    <area onclick="chapterMapStateClicked(7);" state-id="7" href="#6" coords="38,901,385,912,778,910,922,912,898,971,933,1001,952,1211,952,1313,952,1379,937,1449,954,1483,944,1492,909,1459,859,1437,786,1431,760,1457,699,1414,429,1418,49,1403,12,1403,30,1065" shape="poly">
                    <area onclick="chapterMapStateClicked(12);" state-id="12" href="#7" coords="18,1401,690,1411,771,1457,835,1435,959,1496,987,1570,1015,1651,1048,1769,1094,1878,1116,1909,693,1926,254,1922,256,1754,3,1747,10,1542" shape="poly">
                </map>';
    }
    public static function SouthwesternLineChapterMapCoords($chapter_name){
        return '
                <map name="'.str_replace(" ", "", $chapter_name).'">
                    <area onclick="chapterMapStateClicked(4);" state-id="4" href="#1" coords="148,574,584,634,520,1258,328,1242,4,1067,38,1024,26,968,56,906,95,878,64,809,70,767,70,692,72,666,116,680,137,657,146,585" shape="poly">
                    <area onclick="chapterMapStateClicked(3);" state-id="3" href="#2" coords="586,632,1114,666,1093,1217,759,1203,766,1231,610,1222,602,1268,526,1256" shape="poly">
                    <area onclick="chapterMapStateClicked(2);" state-id="2" href="#3" coords="1208,329,1778,321,1812,344,1828,408,1844,417,1854,655,1522,668,1195,664" shape="poly">
                    <area onclick="chapterMapStateClicked(5);" state-id="5" href="#4" coords="1119,668,1354,670,1739,661,1854,661,1883,832,1891,1030,1821,996,1784,1007,1722,1026,1686,1012,1653,1018,1642,1035,1610,1019,1564,1005,1517,991,1467,980,1444,959,1407,961,1383,945,1384,894,1381,724,1268,724,1116,721" shape="poly">
                    <area onclick="chapterMapStateClicked(1);" state-id="1" href="#5" coords="1112,722,1377,721,1379,940,1443,956,1561,1010,1616,1014,1672,1016,1722,1024,1821,1000,1888,1028,1928,1039,1941,1197,1976,1268,1996,1319,1981,1404,1976,1436,1974,1462,1913,1487,1745,1609,1670,1678,1639,1750,1648,1805,1663,1854,1669,1885,1644,1902,1607,1883,1554,1881,1478,1849,1437,1771,1413,1710,1379,1664,1345,1630,1312,1549,1243,1473,1190,1469,1158,1466,1123,1475,1105,1517,1077,1538,1072,1552,1031,1537,967,1507,944,1475,930,1420,913,1367,854,1326,817,1286,782,1242,762,1208,803,1205,1017,1212,1080,1213,1093,1213,1105,943" shape="poly">
                </map>';
    }
    public static function SouthEasternLineChapterMapCoords($chapter_name){
        return '
                <map name="'.str_replace(" ", "", $chapter_name).'">
                    <area onclick="chapterMapStateClicked(13);" state-id="13" href="#1" coords="115,1116,316,1100,524,1082,568,1206,529,1291,498,1422,550,1420,759,1407,753,1461,784,1507,805,1533,787,1577,831,1554,820,1621,800,1649,903,1693,851,1750,777,1685,743,1716,710,1734,674,1701,653,1742,555,1719,501,1654,488,1672,442,1670,413,1688,303,1659,179,1670,200,1603,210,1489,213,1430,166,1361,125,1291" shape="poly">
                    <area onclick="chapterMapStateClicked(14);" state-id="14" href="#2" coords="522,1075,516,964,581,815,617,727,692,727,898,706,918,792,923,1075,954,1389,967,1487,810,1534,746,1464,756,1402,491,1431,509,1325,558,1214,571,1193" shape="poly">
                    <area onclick="chapterMapStateClicked(15);" state-id="15" href="#3" coords="903,706,1091,678,1251,660,1359,953,1410,1069,1405,1180,1428,1283,1441,1304,1235,1343,1075,1366,1117,1440,1099,1487,983,1500,947,1361" shape="poly">
                    <area onclick="chapterMapStateClicked(16);" state-id="16" href="#4" coords="1253,652,1434,626,1591,606,1578,657,1647,688,1696,755,1828,861,1951,989,1995,1038,1967,1178,1956,1250,1889,1265,1879,1306,1887,1335,1861,1348,1853,1317,1676,1335,1464,1353,1426,1278,1421,1103,1346,933" shape="poly">
                    <area onclick="chapterMapStateClicked(17);" state-id="17" href="#5" coords="1094,1485,1091,1420,1065,1394,1174,1356,1426,1320,1459,1346,1763,1327,1846,1312,1856,1346,1887,1346,1877,1297,1900,1263,1944,1266,1969,1343,2062,1495,2163,1626,2178,1688,2294,1879,2317,2044,2371,2090,2392,1967,2410,1892,2480,1876,2526,1912,2565,1951,2603,1992,2714,2049,2809,2131,2832,2237,2866,2273,2894,2299,2894,2332,2853,2343,2789,2294,2760,2245,2698,2175,2611,2121,2523,2093,2459,2072,2407,2049,2387,2070,2356,2116,2315,2170,2294,2216,2178,2286,2070,2325,2106,2348,2181,2358,2328,2366,2413,2361,2613,2358,2717,2353,2773,2389,2750,2469,2637,2492,2588,2569,2601,2621,2642,2624,2745,2624,2861,2611,2887,2608,2907,2649,2905,2693,2884,2722,2812,2714,2652,2724,2528,2734,2480,2768,2366,2750,2240,2768,2199,2773,2036,2801,1969,2737,1908,2688,1820,2724,1712,2763,1712,2819,1704,3020,1712,3118,1709,3152,1109,3139,944,3149,957,2577,1117,2575,1408,2567,1552,2562,1586,2510,1655,2459,1851,2425,1954,2435,2075,2466,2142,2515,2158,2459,2150,2389,2036,2332,2036,2296,2088,2265,2178,2219,2245,2191,2193,2175,2183,2159,2178,2134,2132,2072,2067,2079,2047,1997,1998,1979,2008,1994,1964,1930,1884,1835,1905,1786,1905,1768,1879,1801,1853,1781,1846,1744,1859,1654,1841,1587,1789,1559,1732,1533,1673,1484,1580,1448,1565,1484,1498,1515,1454,1536,1423,1533,1385,1484,1310,1446,1199,1453" shape="poly">
                    <area onclick="chapterMapStateClicked(18);" state-id="18" href="#6" coords="120,1111,308,1100,462,1090,532,1082,516,966,547,876,589,770,632,711,643,631,671,572,679,551,589,564,612,513,612,490,601,479,326,502,151,518,9,526,35,711,48,1013,107,1023" shape="poly">
                    <area onclick="chapterMapStateClicked(19);" state-id="19" href="#7" coords="620,724,723,716,944,696,1215,660,1423,624,1431,577,1457,569,1472,523,1524,508,1580,474,1598,430,1624,407,1637,420,1673,381,1704,379,1732,330,1740,291,1707,286,1467,330,1186,384,996,394,905,415,882,410,887,438,792,448,697,472,689,451,676,554,640,634" shape="poly">
                    <area onclick="chapterMapStateClicked(20);" state-id="20" href="#8" coords="1740,291,2000,247,2312,175,2508,126,2603,268,2606,320,2510,428,2497,456,2351,562,2335,642,2245,657,2183,760,2178,825,2070,938,2031,979,1974,1028,1892,946,1833,871,1722,758,1658,709,1575,639,1593,593,1426,616,1428,582,1464,554,1519,515,1596,459,1658,407,1725,353,1735,327" shape="poly">
                </map>';
    }
    public static function AmericanLineBuildersChapterMapCoords($chapter_name){
        return '<map name="'.str_replace(" ", "", $chapter_name).'">
                    <area onclick="chapterMapStateClicked(21);" state-id="21" href="#1"  coords="3801,9989,3482,9935,3354,10148,3216,9723,2664,9382,2792,8935,2526,8967,2090,8371,1972,7861,2099,7371,2535,6786,2291,6446,3695,6276,3865,6734,3918,7085,4163,8787,3992,9201,3897,9467,4141,8627,3950,9702" shape="poly">
                    <area onclick="chapterMapStateClicked(23);" state-id="23" href="#2"  coords="4089,10414,4047,10297,6471,9978,7173,9201,6588,8520,6206,8627,5706,8339,5557,8393,5291,8690,4844,9212,4398,9435,3951,9488,3823,10052,3515,9978,3494,10137,3398,10318,3334,10488" shape="poly">
                    <area onclick="chapterMapStateClicked(22);" state-id="22" href="#3"  coords="3983,9467,4738,9286,5579,8413,5238,6574,4291,6733,3898,6872,4121,8680,4089,9084" shape="poly">
                    <area onclick="chapterMapStateClicked(25);" state-id="25" href="#4"  coords="4568,4319,4334,4542,4217,4829,4206,5426,4451,5861,4313,6713,5865,6499,6121,5882,6312,5862,6291,5457,6014,4851,5780,4968,5738,5106,5472,5181,5674,4702,5578,4127,5302,4010,5259,4031,5057,3978,4812,3893,4706,4021,4727,4127" shape="poly">
                    <area onclick="chapterMapStateClicked(26);" state-id="26" href="#5"  coords="7513,6872,7460,7661,7056,8222,6801,8616,6578,8467,6142,8597,5568,8350,5302,7203,5248,6586,5929,6469,6460,6670,6939,6319,7333,6010,7461,6648" shape="poly">
                    <area onclick="chapterMapStateClicked(29);" state-id="29" href="#6"  coords="8290,8244,8450,8233,9141,7605,8939,7340,8567,7488,8290,7829,8162,7488,7684,7584,7514,6989,7439,7733,6971,8339,6748,8797,7439,9371,7801,9169,8067,9020" shape="poly">
                    <area onclick="chapterMapStateClicked(28);" state-id="28" href="#7"  coords="8928,7273,9960,7007,9800,7486,9885,7818,10045,8159,9471,8052,9524,7839,9279,7637" shape="poly">
                    <area onclick="chapterMapStateClicked(24);" state-id="24" href="#8"  coords="10002,6989,9906,7201,9928,7871,10119,8052,10300,8308,10417,8765,10577,8212,10630,7765,10321,7818" shape="poly">
                    <area onclick="chapterMapStateClicked(27);" state-id="27" href="#9"  coords="9896,8222,10098,8308,10566,9105,9439,9435,7418,9775,6471,9978,6971,9467,7163,9222,7556,9371,7960,9095,8184,8627,8322,8276,8769,7946,9215,7531,9300,7669,9428,7808" shape="poly">
            </map>';
    }
    public static function TexasStateMapCoords($state_Name){
        return '<map  name="'.str_replace(" ", "", $state_Name).'">
                    <area  onclick="stateMapUnionClicked(5);" union-id="5" href="#1" coords="187,718,167,628,21,466,254,487,420,596,467,649,450,735,366,740,316,808,254,780,203,730" shape="poly">
                    <area onclick="stateMapUnionClicked(7);" union-id="7" href="#2" coords="601,214,554,210,575,254,584,385,617,404,568,499,617,499,727,505,761,477,775,355,864,366,860,314,809,304,783,282,788,286,767,286,783,282,672,253,630,217" shape="poly">
                    <area onclick="stateMapUnionClicked(2);" union-id="2" href="#3" coords="1053,367,1059,310,1167,356,1168,420,1118,427,1115,375" shape="poly">
                    <area onclick="stateMapUnionClicked(1);" union-id="1" href="#4" coords="1155,427,1106,369,1041,369,999,383,993,427,1038,453,1075,460,1093,566,1163,603,1181,530" shape="poly">
                    <area onclick="stateMapUnionClicked(3);" union-id="3" href="#5" target="" alt="66" title="66" href="" coords="465,719,657,1005,716,1013,725,931,790,848,972,882,1155,765,1060,636,950,634,884,676,812,611,714,560,716,666,554,685,493,685,691,692,551,683,662,691,626,685,594,686,667,691,510,678,458,680" shape="poly">
                    <area onclick="stateMapUnionClicked(10);" union-id="10" href="#6"coords="1009,569,1044,547,1146,594,1184,555,1200,724,1155,771,1031,617,1008,604" shape="poly">
                    <area onclick="stateMapUnionClicked(4);" union-id="4" href="#7" coords="993,423,979,475,1035,470,1077,509,1058,545,880,682,715,565,710,684,561,610,541,542,600,543,610,506,730,506,761,413,776,356,861,360,860,315,906,305,949,325,1009,323,1047,323,1043,369" shape="poly">
                    <area onclick="stateMapUnionClicked(6);" union-id="6" href="#8" coords="385,577,284,500,328,476,384,13,644,28,641,224,563,220,584,313,577,398,614,415,600,446,558,495,543,527,662,672,605,689,541,690,463,676,453,615" shape="poly">
                    <area onclick="stateMapUnionClicked(8);" union-id="8" href="#9"coords="717,1017,656,1012,690,1103,795,1150,845,1165,864,1155,841,1060,945,891,840,858,771,870,765,939,719,931,718,978" shape="poly">
                </map>';
    }
    public static function KansasStateMapCoords($state_Name){
         return '<map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(11);" union-id="11"  href="#1" coords="130,96,49,1382,2557,1455,2544,521,2385,355,2448,249,2416,199,2295,159" shape="poly">
                </map>';
    }
    public static function NewMexicoStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(12);" union-id="12" href="#2" shape="poly" coords="211, 29, 211, 29, 564, 66, 520, 560, 225, 528, 232, 556, 89, 537, 81, 582, 14, 568, 90, 13, 210, 26, 210, 29, 235, 33, 533, 63" />
                </map>';
    }
    public static function ArizonaStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(13);" union-id="13" href="#1" shape="poly" coords="235, 15, 235, 15, 555, 67, 485, 562, 337, 543, 78, 391, 89, 373, 105, 367, 112, 350, 97, 341, 100, 314, 119, 298, 128, 262, 156, 245, 146, 225, 135, 180, 142, 147, 145, 76, 178, 80, 187, 92, 199, 69, 208, 9, 237, 15, 235, 15, 339, 33" />
                </map>';
    }
    public static function OklahomaStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(14);" union-id="14" href="#1" shape="poly" coords="35, 15, 35, 15, 744, 35, 762, 206, 762, 398, 694, 368, 622, 378, 592, 392, 545, 370, 527, 392, 493, 375, 455, 372, 431, 343, 404, 350, 397, 338, 343, 332, 331, 311, 311, 312, 298, 313, 274, 291, 282, 79, 238, 100, 136, 90, 29, 68, 31, 15, 34, 15, 177, 18" />
                </map>';
    }

    public static function NorthDakotaStateMapCoords($state_Name){
        //return '';    
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(16);" union-id="16" href="#1" target=""  coords="444,165,423,233,413,339,414,384,428,388,429,473,58,449,89,61,475,85,477,120,474,165" shape="poly">
                    <area  onclick="stateMapUnionClicked(18);" union-id="18" href="#2" target="" coords="470,90,665,93,697,338,717,434,722,488,424,478,420,385,440,217,445,170,476,166" shape="poly">
                </map>';
    }
    public static function SouthDakotaStateMapCoords($state_Name){
        //return '';
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(17);" union-id="17" href="#1" target="" alt="1250" title="1250" href="" coords="99,59,403,78,400,130,405,186,368,221,434,293,487,323,465,440,150,420,66,418" shape="poly">
                    <area onclick="stateMapUnionClicked(15);" union-id="15" href="#2" target="" alt="426" title="426" href="" coords="731,504,672,459,597,470,555,440,466,442,492,331,374,218,420,163,402,76,730,85,742,302,741,230,738,433" shape="poly">
                </map>';
    }
    public static function MissouriStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(21);" union-id="21" href="#1"  coords="411,2154,397,1451,596,1451,605,1374,786,1382,786,1500,750,1548,764,2020,771,2153" shape="poly">
                    <area onclick="stateMapUnionClicked(20);" union-id="20" href="#2" coords="601,1387,590,1471,404,1443,390,966,228,808,215,636,114,535,0,327,810,312,838,709,895,829,996,920,930,1061,935,1505,1164,1543,1297,1901,1303,2136,881,2161,1087,2147,1299,2134,773,2159,752,1570,788,1389,738,1375,668,1375" shape="poly">
                    <area  onclick="stateMapUnionClicked(19);" union-id="19" href="#3"  coords="2184,2307,1929,2341,1998,2094,1296,2137,1165,1534,948,1505,940,1046,994,911,906,831,807,307,1358,278,1468,364,1436,444,1520,676,1727,882,1897,1004,1966,1055,1874,1284,2137,1501,2242,1688,2370,1878" shape="poly">
                </map>';
    }
    public static function IowaStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(22);" union-id="22" href="#1" coords="35,24,303,22,461,18,559,10,580,12,584,28,598,42,589,70,605,119,638,130,646,149,667,164,676,184,694,204,702,225,689,252,686,270,644,299,619,303,609,313,609,328,619,341,629,360,624,377,612,390,612,407,606,413,593,420,583,431,587,442,585,450,573,452,560,432,547,423,457,427,361,433,257,437,119,434,109,408,115,390,108,356,104,349,103,322,99,303,82,282,80,255,60,204,58,193,54,167,32,134,41,105,48,76,48,62,38,61,36,53,42,45,39,39" shape="poly">
            </map>';
    }
    public static function MinnesotaStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'"> 
                <area onclick="stateMapUnionClicked(23);" union-id="23" href="#1" coords="43,44,183,44,181,9,202,17,209,35,212,47,213,67,224,73,240,72,252,76,272,81,275,89,286,89,297,82,319,80,330,80,348,89,375,104,392,103,400,115,415,119,419,125,431,130,453,122,470,111,479,121,498,122,529,123,536,131,551,131,564,128,563,136,519,154,480,175,468,185,451,204,416,245,397,258,402,270,390,270,385,274,383,295,385,332,352,360,344,383,358,394,358,409,355,417,353,444,354,464,367,480,391,487,414,502,421,516,433,528,457,542,466,561,473,587,341,595,209,595,93,595,93,418,77,403,63,386,83,365,85,340,76,299,71,280,67,229,61,169,46,129,48,92,53,76" shape="poly">
            </map>';
    }
    public static function WisconsinStateMapCoords($state_Name){
        return '
                <map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(24);" union-id="24" href="#1" coords="79,70,93,58,158,48,202,26,245,8,227,37,199,73,214,60,241,71,278,101,289,106,290,144,277,145,290,254,274,257,275,265,265,268,275,454,290,455,290,503,317,502,325,547,326,587,249,592,238,574,214,564,199,519,208,497,195,484,183,426,157,404,127,382,119,363,88,345,65,336,39,315,43,276,44,247,48,221,33,211,28,196,45,167,80,142" shape="poly">
                    <area onclick="stateMapUnionClicked(25);" union-id="25" href="#2" coords="289,105,401,131,457,142,454,155,483,162,483,192,478,215,498,207,497,227,508,241,508,256,491,269,478,301,484,314,499,298,516,274,535,246,548,222,566,196,575,196,563,217,525,323,528,355,511,398,513,419,511,450,503,488,507,512,518,536,517,555,521,576,427,581,327,588,322,502,288,503,287,455,275,452,266,273,284,254,290,252,282,148,294,143" shape="poly">
                </map>';
    }
    public static function NebraskaStateMapCoords($state_Name){
        return '
            <map name="'.str_replace(" ", "", $state_Name).'">
                <area onclick="stateMapUnionClicked(26);" union-id="26" href="#1" coords="78,70,223,80,364,87,491,91,528,117,539,103,584,105,625,128,644,144,652,175,667,214,683,254,688,293,691,324,704,359,733,400,606,401,500,398,395,395,306,390,263,391,207,384,211,283,170,281,109,275,61,271,70,169" shape="poly">
            </map>';
    }
    public static function AlabamaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(31);" union-id="31" href="#1"  coords="187,63,131,121,92,131,72,131,51,119,55,51,40,39,182,27" shape="poly">
                    <area onclick="stateMapUnionClicked(33);" union-id="33" href="#2"  coords="203,94,206,70,183,56,186,20,234,20,244,61,239,83,214,94" shape="poly">
                    <area onclick="stateMapUnionClicked(32);" union-id="32" href="#3" coords="202,95,227,93,248,63,314,260,319,376,120,397,136,459,93,430,63,459,46,321,49,140,128,122,161,90,190,61" shape="poly">
                </map>';
    }
    public static function ArkansasStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">  
                    <area onclick="stateMapUnionClicked(41);" union-id="41"   href="#1" coords="343,487,266,485,261,697,321,707,340,814,415,805,423,717,411,559,343,554,348,498,348,500,351,523,340,488" shape="poly">
                    <area onclick="stateMapUnionClicked(44);" union-id="44"   href="#2" coords="596,131,594,209,561,265,505,285,495,382,430,466,305,487,258,478,239,225,227,141" shape="poly">
                    <area onclick="stateMapUnionClicked(42);" union-id="42"   href="#3" coords="788,685,787,722,794,793,422,801,421,720,422,624,479,662,593,653,658,666,657,651,724,648,721,621,747,606,782,582,818,581,794,609" shape="poly">
                    <area  onclick="stateMapUnionClicked(40);" union-id="40"   href="#4" coords="715,643,806,577,906,418,915,321,783,345,661,316,665,235,716,133,596,136,582,236,512,287,505,390,449,450,346,490,342,551,412,557,426,622,470,660,646,658" shape="poly">
                    <area  onclick="stateMapUnionClicked(39);" union-id="39"   href="#5" href="" coords="984,212,938,322,796,328,703,332,659,260,711,135,821,124,909,119,928,145,887,202,879,222,946,205" shape="poly">
                </map>';
    }
    public static function FloridaStateMapCoords($state_Name){
        return '<map  name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(36);" union-id="36" href="#1"  coords="33,79,32,60,23,42,141,32,174,51,145,77,140,102,101,75" shape="poly">
                    <area onclick="stateMapUnionClicked(37);" union-id="37" href="#2" coords="143,106,158,66,173,46,264,45,300,28,367,146,403,226,405,303,371,324,275,214,260,132,210,85,199,75,167,97" shape="poly">
                </map>';
    }
    public static function GeorgiaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(34);" union-id="34" href="#1"  coords="22,49,140,36,149,68,107,88,105,103,74,106,72,88,32,90" shape="poly">
                    <area onclick="stateMapUnionClicked(35);" union-id="35" href="#2"  coords="104,380,136,481,384,471,421,405,452,299,391,211,223,64,225,29,150,42,149,74,116,89,78,112,67,90,38,94,78,248,105,300" shape="poly">
                </map>';
    }
    public static function LouisianaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(28);" union-id="28" href="#1" coords="1,282,200,282,198,395,187,431,176,522,208,552,175,585,138,581,136,555,99,497,31,498,10,470" shape="poly">
                    <area onclick="stateMapUnionClicked(27);" union-id="27" href="#2"  coords="208,565,183,508,192,316,201,276,439,265,445,323,454,355,477,401,410,525,389,629,689,622,679,679,810,975,536,992,365,914,50,884,35,865,90,616,41,546,33,489,90,494,116,517,139,586,217,574,161,589,203,571,181,581,193,577,173,585" shape="poly">
                </map>';
    }
    public static function MississippiStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(29);" union-id="29" href="#1"  coords="167,320,79,326,49,314,39,165,94,54,102,36,266,23,276,37,273,192,210,200,194,235,162,238" shape="poly">
                    <area onclick="stateMapUnionClicked(30);" union-id="30" href="#2"  coords="248,479,191,505,169,460,165,431,13,437,52,323,168,323,163,238,187,239,210,201,273,197,270,280,289,487" shape="poly">
                </map>';
    }
    public static function CarolinasStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(52);" union-id="52" href="#1"   coords="268,204,288,237,284,297,225,305,181,330,150,341,31,354,32,325,56,314,60,294,85,280,151,237,179,201,189,213,220,182,236,189,258,149,290,160,267,181" shape="poly">
                    <area onclick="stateMapUnionClicked(53);" union-id="53" href="#2"  coords="280,177,267,198,292,249,285,295,355,289,391,328,494,309,525,323,513,280,476,225,407,207,381,199,369,183,312,190" shape="poly">
                    <area onclick="stateMapUnionClicked(56);" union-id="56" href="#3"  coords="705,339,695,391,638,410,527,326,539,300,613,280,594,251,598,230,674,229,677,206,731,146,854,132,861,147,810,188,824,217,837,248,818,269,748,288" shape="poly">
                    <area onclick="stateMapUnionClicked(54);" union-id="54" href="#4"  coords="518,169,514,85,263,123,295,179,327,182,376,170,381,202,474,228,508,275,536,249,490,182" shape="poly">
                    <area onclick="stateMapUnionClicked(55);" union-id="55" href="#5" coords="800,102,858,88,876,136,729,147,675,209,602,232,549,297,540,260,514,217,497,188,518,124,522,84,816,22,852,63,768,103" shape="poly">
                </map>';
    }
    public static function TennesseeStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(46);" union-id="46" href="#1"  coords="30,296,67,243,95,251,136,272,182,247,242,243,242,296,211,299,212,357,10,373" shape="poly">
                    <area onclick="stateMapUnionClicked(45);" union-id="45" href="#2"  href="" coords="61,232,66,203,76,167,123,161,180,159,227,153,245,181,246,204,247,244,149,254,135,270" shape="poly">
                    <area  union-id="" href="#3" target=""  coords="274,285,348,286,373,300,380,342,324,348,212,358,204,298" shape="poly">
                    <area onclick="stateMapUnionClicked(47);" union-id="47" href="#4" target="" alt="429" title="429" href="" coords="531,179,537,220,476,263,432,288,432,337,384,341,351,279,248,290,246,189,226,133,524,106,504,137,507,157" shape="poly">
                    <area onclick="stateMapUnionClicked(48);" union-id="48" href="#5"  coords="523,106,504,152,547,170,539,218,593,207,611,224,639,189,618,164,631,99" shape="poly">
                    <area onclick="stateMapUnionClicked(51);" union-id="51" href="#6"  coords="812,147,820,156,850,132,903,90,906,58,719,88,734,124,722,145,754,142,790,165" shape="poly">
                    <area onclick="stateMapUnionClicked(49);" union-id="49" href="#7"  coords="428,338,439,294,482,266,542,222,586,211,607,223,625,252,646,286,655,310" shape="poly">
                    <area onclick="stateMapUnionClicked(50);" union-id="50" href="#8" coords="708,231,647,282,620,229,637,154,631,95,706,85,722,108,735,125,721,145,757,146,785,183" shape="poly">
                </map>';
    }
    public static function IllinoisStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(64);" union-id="64" href="#1"  coords="1205,2596,1021,2535,958,2640,893,2649,825,2495,732,2271,635,2250,540,2158,609,2087,722,2075,717,1971,754,1940,921,1935,919,1805,851,1603,929,1527,914,1386,983,1417,1107,1475,1109,1366,1268,1350,1296,1441,1443,1421,1483,1671,1499,1768,1354,2024,1333,2193,1347,2371,1276,2401,1238,2423,1193,2433,1184,2493,1225,2542" shape="poly">
                    <area  onclick="stateMapUnionClicked(62);" union-id="62" href="#2"  coords="501,1946,530,1711,523,1545,875,1530,871,1572,847,1590,857,1813,904,1818,896,1927,741,1938,682,2058,530,2152,461,2078,479,1992" shape="poly">
                    <area  onclick="stateMapUnionClicked(60);" union-id="60" href="#3"  coords="838,1106,820,1302,717,1391,732,1534,521,1543,498,1474,364,1479,333,1384,354,1277,432,1200,470,1156,595,1154,642,1145,690,1145,685,1094,785,1082" shape="poly">
                    <area  onclick="stateMapUnionClicked(63);" union-id="63" href="#4" target="" alt="649" title="649" href="" coords="343,1764,238,1548,348,1548,360,1485,494,1472,524,1550,529,1717,491,1761,421,1748" shape="poly">
                    <area  onclick="stateMapUnionClicked(58);" union-id="58" href="#5"  coords="147,881,198,766,370,761,370,715,571,706,547,498,792,490,795,459,985,455,1010,690,1385,709,1450,1420,1252,1352,998,1349,913,1389,913,1530,741,1534,723,1415,752,1385,821,1315,862,1258,848,1103,800,1078,672,1090,675,1136,441,1170,332,1357,352,1552,234,1558,72,1405,29,1276,57,1045" shape="poly">
                    <area  onclick="stateMapUnionClicked(61);" union-id="61" href="#6"  coords="1225,388,1099,394,1112,516,999,537,988,446,798,457,783,482,447,500,414,432,434,291,345,203,259,84,672,63,1228,27,1254,173,1091,198,1089,271,1207,255" shape="poly">
                    <area onclick="stateMapUnionClicked(59);" union-id="59" href="#7"  href="" coords="144,617,121,686,191,758,358,751,363,707,562,700,553,505,446,511,406,430,382,489,340,546,165,594" shape="poly">
                </map>';
    }

    public static function IndianaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(65);" union-id="65" href="#1"  coords="202,1245,268,1262,244,1284,229,1262,224,1287,236,1300,227,1312,213,1310,205,1290" shape="poly">
                    <area onclick="stateMapUnionClicked(66);" union-id="66" href="#2" coords="198,1289,152,1293,157,1331,129,1328,115,1302,136,1198,234,1037,193,856,195,734,145,113,215,130,294,82,759,27,866,924,751,981,726,1045,617,1215,542,1191,497,1222,463,1288,416,1243,343,1309,274,1261,203,1241" shape="poly">
                </map>';
    } 
    public static function OhioStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(72);" union-id="72" href="#1"  coords="166,162,167,137,223,117,217,84,184,75,128,63,28,79,37,177,82,178,110,177,113,191,149,187" shape="poly">
                    <area onclick="stateMapUnionClicked(71);" union-id="71" href="#2"  coords="267,343,296,333,319,284,362,252,373,139,346,5,240,69,171,136,151,188,113,191,108,181,41,178,45,275,51,307,58,348,84,346,109,372,155,385,187,381,218,360,223,387,246,398,271,373" shape="poly">
                </map>';
    } 
    public static function WestVirginiaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area  onclick="stateMapUnionClicked(75);" union-id="75" href="#1"  coords="122,86,108,14,99,14,101,95,50,142,50,169,32,165,27,195,7,221,5,241,74,308,130,289,166,263,193,168,212,185,260,108,297,103,299,79,269,63,229,84,189,118,182,79" shape="poly">
                </map>';
    } 

    public static function VirginiaStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(73);" union-id="73" href="#1"  coords="424,33,391,17,385,32,350,13,292,119,268,109,235,197,231,226,165,248,143,260,119,232,10,341,150,319,304,303,581,244,517,131,461,104,437,95,443,47" shape="poly">
                </map>';
    } 

    public static function KentuckyStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(67);"  union-id="67" href="#1"  coords="594,175,512,266,471,289,133,321,111,315,113,335,5,339,26,296,22,284,37,262,77,277,75,248,97,215,103,190,141,181,174,189,207,165,249,152,272,160,289,116,313,82,349,63,349,37,375,36,401,58,442,69,480,70,502,54,525,81,570,157" shape="poly">
                </map>';
    }   
    public static function MichiganStateMapCoords($state_Name){
        return '<map  name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(69);"  union-id="69" href="#1"  coords="307,348,337,342,349,335,359,325,359,313,339,289,361,262,384,251,411,323,399,343,380,368,371,410,326,414,322,394,335,365,306,371" shape="poly">
                    <area onclick="stateMapUnionClicked(70);"  union-id="70" href="#2"  coords="265,109,262,88,99,99,127,52,9,124,126,175,152,222,177,169,237,142,201,279,227,346,207,424,321,414,325,392,305,370,303,345,344,335,341,315,331,270,352,224,326,126,299,97,282,105" shape="poly">
                </map>';
    } 
    public static function MarylandStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(68);"  union-id="69" href="#1" coords="421,44,62,114,73,184,114,137,141,118,166,120,209,107,225,100,238,131,272,143,266,155,309,173,324,163,334,171,322,210,308,232,321,246,337,234,363,255,463,276,514,245,528,187,467,204,430,66" shape="poly">
                </map>';
    }
    public static function WashingtonStateMapCoords($state_Name){
        return '<map name="'.str_replace(" ", "", $state_Name).'">
                    <area onclick="stateMapUnionClicked(74);"  union-id="74" href="#1" coords="558,219,596,335,644,326,601,469,597,467,617,517,448,553,37,613,152,507,182,530,212,516,268,483,289,419,303,378,333,387,346,347,213,380,163,328,39,200,239,2,275,40,354,113,383,147,414,167,425,206,444,240" shape="poly">
                </map>';
    }    

}

  