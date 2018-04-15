<?php


$dir = "files/";

$aircraft = array("A320321","A330","B777","ATR72");
$zones = array("ME128","ME567","ME34","EA");

$result = array();

$ignore = array(6,9,14,15,16,17,18,19,37,39,40,41,42,43,44,48,60,61,62,63,64,65,66,67,68,69);
$str = "<td>&nbsp;</td>";
for($i=5; $i<=80; $i++) {
    $j = $i;
    if($i < 10){
        $j = "0".$i;
    }
    if (!in_array($i, $ignore)) {
        $str .= "<td bgcolor='#ffd700'><strong>ATA".$j."</strong></td>";
    }


}


echo "<p style='font-size: 26px; font-weight: bold;'>ARS ZONES BASED CONTENT FILTER</p>";

echo "<table border='1'>";
foreach ($zones as $zone) {

    echo "<tr><td><strong>".$zone."</strong></td><td><table border='1'><tr>".$str."</tr>";
    foreach ($aircraft  as $ac) {
        echo "<tr>";
        $lines = getList($dir.$ac."/".$zone.".txt");

        echo "<td align='center'><strong>".$ac."</strong></td>";
        for($i=5; $i<=80; $i++){
            $j = $i;
            if($i < 10){
                $j = "0".$i;
            }

            if(in_array($i,$ignore)){
                continue;
            }


            $ch = "";
            foreach ($lines as $line) {
                $ata = strval($line);
                if($ata == $j){
                    $ch =  "OK";
                }
            }

            if($ch == ""){
                echo "<td align='center' bgcolor='yellow'>&nbsp;</td>";
            }else {
                echo "<td align='center' bgcolor='green'>&nbsp;</td>";
            }

        }

        $result[$zone][$ac] = $lines;
        echo "</tr>";
    }
    echo "</table></td></tr>";



}
echo "</table>";

echo "<p style='color: red; font-style: italic; font-size: 16px;'><strong style='background-color: #008000; width: 50px; height: 20px; float: left; margin-right: 10px;'>&nbsp;</strong>  means we have content in TRAINING WORKSHEET</p>";
echo "<p style='color: red; font-style: italic; font-size: 16px;'><strong style='background-color: yellow; width: 50px; height: 20px; float: left;margin-right: 10px;'>&nbsp;</strong>  means we DON'T have content in TRAINING WORKSHEET</p>";



//print_r($result);


function getList($fileName = 'danhsach.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    while (!$danhsach->eof()) {
        $line = $danhsach->fgets();

        $line = preg_replace('/[^0-9]/', '', $line);
        $ata = substr($line,0,2);

        if($ata != ''){
            $result[] = $ata;
        }



    }

    $result = array_unique($result);
    sort($result);

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









