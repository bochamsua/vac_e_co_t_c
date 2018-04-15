<?php


$files = array(
    'bank-general' => 'General',
    'bank-a321-a' => 'A320/321 Level A',
    'bank-airframe' => 'Airframe',
    'bank-powerplant' => 'Powerplant',
    'bank-law' => 'Law',
    'bank-law-new' => 'Law New',

);

foreach ($files as $key => $value) {
    $list = getList($key.'.txt');


    $xml = buildUpBank($list, $value);

    file_put_contents($key.".xml", $xml);
    echo "Done ".$value. "<br>";
}





function getList($fileName = 'bank.txt'){
    if(!file_exists($fileName)){
        return false;
    }
    $danhsach = new SplFileObject($fileName, 'ru');
    $result = array();
    $i=0;
    $j=0;
    $question = array();
    while (!$danhsach->eof()) {

        $line=$danhsach->fgets();
        $line = trim($line);
        $line = str_replace("﻿",'',$line);

        if(startsWithNumber($line)){
            $question[$i]['question'] = startsWithNumber($line);

            $i++;
        }elseif(startWith($line)){
            $question[$i-1]['answer'][] = startWith($line);
        }





    }

    $danhsach = null;

    return $question;
}

function buildUpBank($list, $title = ''){

    $string = 'A320/321 Category A';
    if($title != ''){
        $string = $title;
    }

    $xml = '<?xml version="1.0" encoding="utf-8"?>
<wpProQuiz>
    <header version="0.37" exportVersion="1"/>
    <data>
        <quiz>
            <title titleHidden="false"><![CDATA['.$string.']]></title>
            <text><![CDATA['.$string.']]></text>
            <category/>
            <resultText gradeEnabled="false"><![CDATA[]]></resultText>
            <btnRestartQuizHidden>false</btnRestartQuizHidden>
            <btnViewQuestionHidden>false</btnViewQuestionHidden>
            <questionRandom>false</questionRandom>
            <answerRandom>false</answerRandom>
            <timeLimit>0</timeLimit>
            <showPoints>true</showPoints>
            <statistic activated="true" ipLock="1440"/>
            <quizRunOnce type="1" cookie="false" time="0">false</quizRunOnce>
            <numberedAnswer>true</numberedAnswer>
            <hideAnswerMessageBox>false</hideAnswerMessageBox>
            <disabledAnswerMark>false</disabledAnswerMark>
            <showMaxQuestion showMaxQuestionValue="1" showMaxQuestionPercent="false">false</showMaxQuestion>
            <toplist activated="false">
                <toplistDataAddPermissions>1</toplistDataAddPermissions>
                <toplistDataSort>1</toplistDataSort>
                <toplistDataAddMultiple>false</toplistDataAddMultiple>
                <toplistDataAddBlock>1</toplistDataAddBlock>
                <toplistDataShowLimit>1</toplistDataShowLimit>
                <toplistDataShowIn>0</toplistDataShowIn>
                <toplistDataCaptcha>false</toplistDataCaptcha>
                <toplistDataAddAutomatic>false</toplistDataAddAutomatic>
            </toplist>
            <showAverageResult>false</showAverageResult>
            <prerequisite>false</prerequisite>
            <showReviewQuestion>true</showReviewQuestion>
            <quizSummaryHide>false</quizSummaryHide>
            <skipQuestionDisabled>false</skipQuestionDisabled>
            <emailNotification>0</emailNotification>
            <userEmailNotification>false</userEmailNotification>
            <showCategoryScore>true</showCategoryScore>
            <hideResultCorrectQuestion>false</hideResultCorrectQuestion>
            <hideResultQuizTime>false</hideResultQuizTime>
            <hideResultPoints>false</hideResultPoints>
            <autostart>false</autostart>
            <forcingQuestionSolve>false</forcingQuestionSolve>
            <hideQuestionPositionOverview>false</hideQuestionPositionOverview>
            <hideQuestionNumbering>false</hideQuestionNumbering>
            <sortCategories>true</sortCategories>
            <showCategory>false</showCategory>
            <quizModus questionsPerPage="0">2</quizModus>
            <startOnlyRegisteredUser>false</startOnlyRegisteredUser>
            <adminEmail>
                <to/>
                <form/>
                <subject>Wp-Pro-Quiz: One user completed a quiz</subject>
                <html>false</html>
                <message><![CDATA[Wp-Pro-Quiz

The user "$username" has completed "$quizname" the quiz.

Points: $points
Result: $result]]></message>
            </adminEmail>
            <userEmail>
                <to>-1</to>
                <toUser>false</toUser>
                <toForm>false</toForm>
                <form/>
                <subject>Wp-Pro-Quiz: One user completed a quiz</subject>
                <html>false</html>
                <message><![CDATA[Wp-Pro-Quiz

You have completed the quiz "$quizname".

Points: $points
Result: $result]]></message>
            </userEmail>
            <forms activated="false" position="0"/>
            <questions>';
    $i=1;
    foreach ($list as $item) {

        $question = $item['question'];
        $xml .= '<question answerType="single">
                    <title><![CDATA[Question: '.$i.']]></title>
                    <points>1</points>
                    <questionText><![CDATA['.$question.']]></questionText>
                    <correctMsg><![CDATA[]]></correctMsg>
                    <incorrectMsg><![CDATA[]]></incorrectMsg>
                    <tipMsg enabled="false"><![CDATA[]]></tipMsg>
                    <category/>
                    <correctSameText>false</correctSameText>
                    <showPointsInBox>false</showPointsInBox>
                    <answerPointsActivated>false</answerPointsActivated>
                    <answerPointsDiffModusActivated>false</answerPointsDiffModusActivated>
                    <disableCorrect>false</disableCorrect>
                    <answers>';


        if(isset($item['answer'])){
            $answers = $item['answer'];
            foreach ($answers as $answer) {

                $correct = 'false';
                if(strpos($answer, "[CA]")){
                    $correct = 'true';
                }
                $answer = str_replace(array('.[CA]', '[CA]'), '', $answer);
                $answer = trim($answer);

                $xml .= '<answer points="1" correct="'.$correct.'">
                            <answerText
                                    html="false"><![CDATA['.$answer.']]></answerText>
                            <stortText html="false"><![CDATA[]]></stortText>
                        </answer>';
            }
        }else {
            die($question);
        }


        $xml .= '</answers>
                </question>';




        $i++;

    }

    $xml .= '</questions>
        </quiz>
    </data>
</wpProQuiz>';


    return $xml;

}

function startsWithNumber($haystack) {
    if(preg_match('/^\d./', $haystack)){
        $haystack = preg_replace('/^\d./', '', $haystack);
        $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '),$haystack);
        $haystack = trim($haystack);
        $haystack = ltrim($haystack,".");
        $haystack = trim($haystack);


        return $haystack;
    }
    return false;

}
function startWith($haystack){
    $check = array('A.', 'B.','C.','D.','a.','b.','c.','d.');
    foreach ($check as $item) {
        if(substr($haystack,0, strlen($item))===$item){
                $haystack = str_replace($check, '', $haystack);
            $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '), $haystack);
            $haystack = trim($haystack);
                    return $haystack;
        }

    }

    return false;

}






