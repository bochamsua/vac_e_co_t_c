<?php

require_once 'lib/docx/wordphp.php';
require_once 'lib/simple_html_dom.php';


$reader = new WordPHP();

$text = $reader->readDocument('media/files/8010.docx');

$html = str_get_html($text);
$data = $html->find('p');



echo "<pre>";
$questions = array();
foreach ($data as $d) {

    $q = $d->innertext;

    $q = optimizeQuestion($q);
    if($q){
        $questions[] = $q;
    }

}

$result = array_chunk($questions, 4);

$i=1;
foreach ($result as $q) {
    $question = $q[0];
    echo '<strong>'.$i.'. '.$question.'</strong><br>';
    unset($q[0]);
    $j=1;
    foreach ($q as $ans) {
        $bg = '';
        if(strpos($ans, "[ra]") || strpos($ans, "[ ra]") || strpos($ans, "[ ra ]") || strpos($ans, "[ra ]")){
            $bg = 'style = "color: red;"';
        }

        $ans = str_replace(array("[ra]","[ ra]","[ra ]","[ ra ]"),"",$ans);
        echo '<span '.$bg.'>'.$i.'.'.$j.'. '.$ans.'</span> <br>';

        $j++;
    }

    echo "<br><br>";

    $i++;
}


function optimizeQuestion($question){
    $question = preg_replace('/\s+/', ' ', strip_tags($question));

    $question = trim($question);
    $question = str_replace("&nbsp;","",$question);
    $question = str_replace(" .",".",$question);

    $specialChars = array_merge(range('A','Z'), range('a','z'), range('1','100'));

    foreach ($specialChars as $char) {
        $test = $char.".";
        $pos = strpos($question, $test);
        if($pos === 0){
            $start = strlen($test);
            $question = substr_replace($question, "", 0, $start);
        }
    }


    $question = trim($question);

    if(strlen($question) > 1){
        return $question;
    }

    return false;

}





