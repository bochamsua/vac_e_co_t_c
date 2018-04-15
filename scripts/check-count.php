<?php



$crsA =  getList("courses.txt");


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









