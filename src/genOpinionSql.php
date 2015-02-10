<?php

$nbGen = 150;

echo 'Fectching lorem ipsum...' . PHP_EOL;
$ipsumNb = 10000;

$ipsum = file_get_contents('http://loripsum.net/api/'.$ipsumNb.'/');
$ipsum = str_replace('<p>', '', $ipsum);
$ipsum = preg_replace('/\s\s+/', ' ', $ipsum);
$ipsum = explode('</p>', $ipsum);

$sipsum = file_get_contents('http://loripsum.net/api/'.$ipsumNb.'/small');
$sipsum = str_replace('<p>', '', $sipsum);
$sipsum = preg_replace('/\s\s+/', ' ', $sipsum);
$sipsum = explode('</p>', $sipsum);

function randIn ($array) {
    return $array[rand(0, count($array)-1)];
}

function randChar ($max, $min=5, $str='abcdefghijklmnopqrstuvwxyz') {
    return substr(str_shuffle(str_repeat($str, $max)), 0, rand($min,$max));
}

function loremIpsum ($nb, $small=false) {
    global $ipsum, $ipsumNb, $sipsum;
    $gipsum = '';
    for ($i=0; $i<$nb; ++$i) {
        $gipsum .= (($small) ? $sipsum[rand(0, $ipsumNb-1)] : $ipsum[rand(0, $ipsumNb-1)]) . PHP_EOL;
    }
   return $gipsum; 
}

function genRow () {

    $baseSQL = '(NULL,%branch%,%specialization%,%language%,%housingType%,%transportType%,
            NULL,%university%,%year%,"%sem%","%country%","%city%","%generalComment%",
            %noteGeneral%,%noteHousing%,%noteLife%,%housingRent%,"%housingComment%",
            "%housingOrganization%","%housingAdress1%","%housingAdress2%","%housingPostalCode%",
            "%housingCity%","%housingCountry%","%housingEmail%","%housingPhone%","%lifeComment%",
            "%transportCompany%",%transportPrice%,"%transportComment%","%transportOnSite%",
            1,"2014-04-29 13:31:50","%journeyType%",%noteStudying%,"%studyingComment%",%housingEnabled%,
            %transportEnabled%,%housingContactEnabled%,"%generalAdvice%",%noteInternship%,
            "%companyName%","%companyField%","%internshipTitle%","%companyLink%","%diploma%",
            "%internshipComment%", "%internshipDescription%")';
    
    $branchSpe = array (
        1 => array(1, 2, 3, 4, 'NULL', 'NULL'),
        2 => array(5, 6, 7, 8, 9, 'NULL'),
        3 => array(10, 11, 12, 13, 14, 'NULL'),
        4 => array(15, 16, 17, 18, 'NULL', 'NULL'),
        5 => array(19, 20, 21, 'NULL', 'NULL', 'NULL'),
        6 => array(22, 23, 24, 'NULL', 'NULL', 'NULL')
    );
    $branch = rand(1,6);
    $spe = rand(0,5);
    
    $replaceBase = array(
        'branch' => $branch,
        'specialization' => $branchSpe[$branch][$spe],
        'language' => rand(1,4),
        'housingType' => rand(1,3),
        'transportType' => rand(1,4),
        'university' => rand(1,4),
        'year' => rand(2010,2014),
        'sem' => (rand(0,1)) ? 'A' : 'P',
        'country' => randIn(array('DE', 'SE', 'ES', 'IT')),
        'city' => ucfirst(randChar(15)),
        'generalComment' => loremIpsum(rand(1,3)),
        'noteGeneral' => rand(1, 10),
        'noteHousing' => rand(1, 10),
        'noteLife' => rand(1, 10),
        'housingRent' => rand(100, 800),
        'housingComment' => loremIpsum(rand(1,3), true),
        'housingOrganization' => ucfirst(randChar(25)),
        'housingAdress1' => rand(1,50) . ' ' . randChar(5, 10) . ' ' . ucfirst(randChar(25)),
        'housingAdress2' => (rand(0,1)) ? randChar(35) : '',
        'housingPostalCode' => randChar(6,5,'0123456789'),
        'housingCity' => ucfirst(randChar(15)),
        'housingCountry' => randIn(array('DE', 'SE', 'ES', 'IT')),
        'housingEmail' => randChar(20) . '@' . randChar(20) . '.' . randChar(3,2),
        'housingPhone' => randChar(12,10,'0123456789'),
        'lifeComment' => loremIpsum(rand(2,4), true),
        'transportCompany' => ucfirst(randChar(25)),
        'transportPrice' => rand(50, 1500),
        'transportComment' => (rand(0, 1)) ? loremIpsum(1, true) : '',
        'transportOnSite' => (rand(0, 2)) ? loremIpsum(1, true) : '',
        'journeyType' => rand(1, 4),
        'noteStudying' => rand(1, 10),
        'studyingComment' => loremIpsum(rand(1,3)),
        'housingEnabled' => rand(0,1),
        'transportEnabled' => rand(0,1),
        'housingContactEnabled' => rand(0,1),
        'generalAdvice' => (rand(0,2)) ? loremIpsum(rand(1,2), true) : '',
        'noteInternship' => rand(1, 10),
        'companyName' => ucfirst(randChar(25)),
        'companyField' => ucfirst(randChar(15)) . ((rand(0,1)) ? ucfirst(randChar(15)) : ''),
        'internshipTitle' => ucfirst(randChar(10)) . ((rand(0,3)) ? ucfirst(randChar(5,2)) : '') . ((rand(0,2)) ? ucfirst(randChar(12)) : '') . ((rand(0,1)) ? randChar(11) : ''),
        'companyLink' => 'http://' . randChar(10) . '.' . randChar(3,2),
        'diploma' => ucfirst(randChar(10)) . ((rand(0,3)) ? ucfirst(randChar(5,2)) : '') . ((rand(0,2)) ? ucfirst(randChar(12)) : ''),
        'internshipComment' => loremIpsum(rand(1,3)),
        'internshipDescription' => loremIpsum(rand(1,3), true)
    );
    
    $sql = $baseSQL;
    foreach ($replaceBase as $k => $v) {
        $sql = str_replace('%'.$k.'%', $v, $sql);
    }
    
    return $sql;
}

$fp = fopen('dump.sql', 'w');
fwrite($fp, 'INSERT INTO `opinion` VALUES '.PHP_EOL);

echo 'Begin generation...' . PHP_EOL;
$rows = array();
for ($i=0; $i<$nbGen; ++$i) {
    $rows[] = genRow();
}
echo 'Done.' . PHP_EOL;
fwrite($fp, implode(','.PHP_EOL, $rows));

fclose($fp);