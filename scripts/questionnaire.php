<?php



require_once 'app/Mage.php';
require_once 'simple_html_dom.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableCatProd = $resource->getTableName('catalog_category_product');



$result  = $readConnection->fetchAll("SELECT * FROM bcourses");
$results = array();
foreach ($result as $row) {
    $course = $row['course_code'];
    $pathToCourse = 'files/'.$course.'/';

    if(file_exists($pathToCourse)){
        $files = listFolderFiles($pathToCourse);

        if(count($files)){
            $courseId = $readConnection->fetchOne("SELECT course_id FROM bcourses WHERE course_code = '{$course}'");


            foreach ($files as $file) {
                $code = substr($file,0,-4);
                $code = str_replace(array('Bank','N','-',' ','57'),'',$code);

                $subjectId = $readConnection->fetchOne("SELECT subject_id FROM bsubjects WHERE subject_code = '{$code}'");

                if($subjectId){
                    $html = file_get_html($pathToCourse.$file);


                    $ques = $html->find('ol');
                    $ans = $html->find('p');

                    $questions = array();

                    $answers = array();

                    foreach ($ques as $ol) {
                        $q = preg_replace('/\s+/', ' ', strip_tags($ol->innertext));
                        $q = str_replace("&nbsp;","",$q);
                        $questions[] = trim($q);
                    }

                    $chars = array("&nbsp;","A. ","B. ","C. ","D. ");//

                    foreach ($ans as $p) {


                        $correct = 0;
                        $an = $p->innertext;
                        if(strpos($an,'yellow')){
                            $correct = 1;
                        }

                        $an = strip_tags($an);

                        $txt = preg_replace('/\s+/', ' ', $an);
                        $txt = str_replace($chars,"",$txt);

                        if(strlen($txt) > 0){
                            $answers[] = array(
                                'txt' => trim($txt),
                                'correct'=>$correct
                            );
                        }

                    }

                    for($i=0; $i < count($questions); $i++){

                        $q = $questions[$i];
                        $q = mysql_real_escape_string($q);
                        $sqlInsertQuestion = "INSERT INTO `bquestions` (question_text,course_id,subject_id) VALUES ('{$q}',{$courseId},{$subjectId})";
                        $writeConnection->query($sqlInsertQuestion);
                        $lastId = $writeConnection->lastInsertId();
                        $anses = array();
                        $k = 1;
                        for($j=$i * 3; $j < $i * 3 + 3; $j++){

                            $a = $answers[$j]['txt'];
                            $a = mysql_real_escape_string($a);
                            $sqlInsertAnswer = "INSERT INTO `banswers` (answer_text,question_id,answer_correct,answer_position) VALUES ('{$a}',{$lastId},{$answers[$j]['correct']},{$k})";

                            try {
                                $writeConnection->query($sqlInsertAnswer);
                                $k++;
                            }catch (Exception $ex){
                                echo 'File: '.$pathToCourse.$file. "--- question: {$i}. {$q} --- answer: {$a}";
                            }
                            $anses[] = array(
                                'answer'=>$a,
                                'correct'=>$answers[$j]['correct'],
                            );

                        }

                        $lastId = 0;
                        $results[] = array(
                             'question'=>$q,
                             'answer'=>$anses
                         );
                    }
                }




            }
        }
    }



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
echo "<pre>Done";
//print_r($results);

/*
 *
Reset


TRUNCATE  bquestions;
TRUNCATE  banswers;
ALTER TABLE  bquestions AUTO_INCREMENT = 1;
ALTER TABLE  banswers AUTO_INCREMENT = 1;


 */









