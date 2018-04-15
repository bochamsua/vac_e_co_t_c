<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
        table thead {
            background-color: #ccc;
        }
        table tr td {
            padding: 3px;
        }
    </style>
    <title>Ubrand</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Ubrand</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Paste content here:<br><textarea name="content" rows="5" cols="100"><?php if($_POST["content"]) echo  strip_tags($_POST["content"])?></textarea>
    <br>
    <p><strong>Make sure to scroll all the pages before copy.</strong> ID: viewerContainer. Copy Inner HTML</p>
    <br>Keywords here, each keyword on one line
    <br><textarea name="question" rows="10" cols="100"></textarea>
    <br>
    <input style="padding: 5px 10px; font-size: 20px; font-weight: bold;" type="submit" name="submit" value="Submit">
</form>

<?php

?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["content"])) {
        $nameErr = "Please put content";
    } else {
        $content = $_POST["content"];
        $keywords = $_POST["question"];
        $keywords = explode("\r\n", $keywords);
        $content = strip_tags($content);

/*
        $questions = explode("\r\n", $questions);

        $questions = buildQuestionnaire($questions);
        if(count($questions)){
            $i=1;
            foreach ($questions as $question) {
                $q = $question['question'];
                $a = $question['answer'];

                echo $i.'. '.$q;
                echo '<br>';

                $j=0;
                foreach ($a as $item) {
                    $words = explode(" ", $item);
                    $newWords = array();

                    $chunks = array_chunk($words, 3);
                    foreach ($chunks as $chunk) {
                        $text = implode(" ", $chunk);
                        $found = findMatch($text, $content);
                        $newWords[] = $text.'('.$found.')';
                        //$newWords[] = $text;

                    }

//                    $count = count($words);
//                    for($k=0; $k<$count; $k++){
//                        $text = $words[$k];
//
//                        $found = findMatch($text, $content);
//                        $words[$k] = $words[$k].'('.$found.')';
//                    }

                    $indx = getCharacter($j);
                    echo $indx.'. '.implode(" ", $newWords);
                    echo '<br>';

                    $j++;
                }

                $i++;
            }
        }*/

        $bound = '(?:[!?.;])';
        $filler = '(?:[^!?.;\\d]|\\d*\\.?\\d+)*';

        //$content = strip_tags($content);

        $found = array();
        foreach ($keywords as $key) {
            preg_match_all("#{$bound}({$filler}{$key}{$filler})(?={$bound})#si", "!$content", $matches);
            $found = array_merge($found, $matches[1]);
        }

        if(count($found)){
            foreach ($found as $item) {
                $content = str_replace($item, '<span style="background-color: yellow;">'.$item.'</span>', $content);
            }
        }

        //replace . with line break
        $content = str_replace(array(". ","? "),array(". <br>","? <br>") , $content);

        echo  $content;

    }

}

function findMatch($input, $content){
    $bound = '(?:[!?.;])';
    $filler = '(?:[^!?.;\\d]|\\d*\\.?\\d+)*';

    $found = array();
    preg_match_all("#{$bound}({$filler}{$input}{$filler})(?={$bound})#si", "!$content", $matches);
    $found = array_merge($found, $matches[1]);

    return count($found);


}


function isQuestion($text){
    if(preg_match('/^\d{1,4}([\t.,\-)])/', $text)){
        $haystack = preg_replace('/^\d{1,4}([\t.,\-)])/', '', $text);
        $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '),$haystack);
        $haystack = trim($haystack);

        return $haystack;
    }
    return false;
}

function isAnswer($text){
    if(preg_match('/^[a-z]{1,2}([.,)\-])/i', $text)){
        $haystack = preg_replace('/^[a-z]{1,2}([.,)\-])/i', '', $text);
        $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '),$haystack);
        $haystack = trim($haystack);

        return $haystack;
    }

    return false;
}

function buildQuestionnaire($questions){
    $result = array();
    $i=0;
    if(count($questions)){
        foreach ($questions as $question) {
            $question = trim($question);
            $question = str_replace(array("﻿", "\t", "\r", "\n"),'',$question);

            if(isQuestion($question)){
                $result[$i]['question'] = isQuestion($question);

                $i++;
            }elseif(isAnswer($question)){
                $answer = isAnswer($question);
                $result[$i-1]['answer'][] = $answer;



            }
        }
    }

    return $result;
}

function getCharacter($index){
    if($index == 0){
        return 'A';
    }elseif ($index == 1){
        return 'B';
    }elseif ($index == 2){
        return 'C';
    }elseif ($index == 3){
        return 'D';
    }elseif ($index == 4){
        return 'D';
    }elseif ($index == 5){
        return 'F';
    }

    return '';
}
?>


</body>
</html>