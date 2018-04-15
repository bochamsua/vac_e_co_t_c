<?php


$dir = "files/";


$crsA =  getList($dir."/a320-crsA.txt");
$me128 =  getList($dir."/a320-me128.txt");
$me567 =  getList($dir."/a320-me567.txt");
$me34 =  getList($dir."/a320-me34.txt");
$ea =  getList($dir."/a320-ea.txt");

echo "CRSA - ME128";
echo "<br>";
foreach ($crsA as $line) {
    foreach ($me128 as $l) {
        if($line['new'] == $l['new']){
            echo "found {$line['old']} <br>";
        }
    }

}
echo "<br><br><br><br>";

echo "CRSA - ME567";
echo "<br>";
foreach ($crsA as $line) {
    foreach ($me567 as $l) {
        if($line['new'] == $l['new']){
            echo "found {$line['old']} <br>";
        }
    }

}

echo "<br><br><br><br>";
echo "CRSA - ME34";
echo "<br>";
foreach ($crsA as $line) {
    foreach ($me34 as $l) {
        if($line['new'] == $l['new']){
            echo "found {$line['old']} <br>";
        }
    }

}
echo "<br><br><br><br>";
echo "CRSA - E&A";
echo "<br>";
foreach ($crsA as $line) {
    foreach ($ea as $l) {
        if($line['new'] == $l['new']){
            echo "found {$line['old']} <br>";
        }
    }

}


function getList($fileName = 'danhsach.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    while (!$danhsach->eof()) {
        $lineO = $danhsach->fgets();
        $line = strtolower($lineO);

        $line = preg_replace('/[^0-9a-z]/', '', $line);

        if($line != ''){
            $result[] = array('new'=>$line,'old'=>$lineO);
        }



    }

    //$result = array_unique($result);
    //sort($result);

    $danhsach = null;

    return $result;
}



function listFolderFiles($dir)
{
   $result = array();
    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if (!$fileInfo->isDot() && !$fileInfo->isDir()) {
           $result[] = $fileInfo->getFilename();

        }
    }

    return $result;
}


//file_put_contents('lll.htm',serialize($results));
//echo "<pre>Done";
//print_r($results);

/*
 *
Reset


TRUNCATE  bquestions;
TRUNCATE  banswers;
ALTER TABLE  bquestions AUTO_INCREMENT = 1;
ALTER TABLE  banswers AUTO_INCREMENT = 1;


 */









