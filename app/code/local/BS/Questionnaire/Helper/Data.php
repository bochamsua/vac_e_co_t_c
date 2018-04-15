<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire default helper
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function prepareEleven($result){
        $rows = count($result);

        for($i=1; $i<= 25; $i++){
            $i1 = '';
            $i2 = '';
            $i3 = '';
            $j1 = '';
            $j2 = '';
            $j3 = '';
            $k1 = '';
            $k2 = '';
            $k3 = '';
            $l1 = '';
            $l2 = '';
            $l3 = '';
            $iforprint = $i;
            if($iforprint > $rows){
                $iforprint = '';
            }
            for($t=1; $t <= 3; $t++){
                if(isset($result[$i-1]['correct']) && $result[$i-1]['correct'] == $t){
                    ${'i'.$t} = 'X';
                }
            }


            $j= $i+25;
            if($j > $rows){
                $j = '';
            }
            for($t=1; $t <= 3; $t++){
                if(isset($result[$j-1]['correct']) && $result[$j-1]['correct'] == $t){
                    ${'j'.$t} = 'X';
                }
            }

            $k = $i + 50;
            if($k > $rows){
                $k = '';
            }
            for($t=1; $t <= 3; $t++){
                if(isset($result[$k-1]['correct']) &&  $result[$k-1]['correct'] == $t){
                    ${'k'.$t} = 'X';
                }
            }

            $l = $i + 75;
            if($l > $rows){
                $l = '';
            }

            for($t=1; $t <= 3; $t++){
                if(isset($result[$l-1]['correct']) && $result[$l-1]['correct'] == $t){
                    ${'l'.$t} = 'X';
                }
            }
            if($l > $rows){
                $l = '';
            }
            $rowData[] = array(
                'i' => $iforprint,
                'i1' => $i1,
                'i2' => $i2,
                'i3' => $i3,
                'j' => $j,
                'j1' => $j1,
                'j2' => $j2,
                'j3' => $j3,
                'k' => $k,
                'k1' => $k1,
                'k2' => $k2,
                'k3' => $k3,
                'l' => $l,
                'l1' => $l1,
                'l2' => $l2,
                'l3' => $l3,
            );
        }

        
        return $rowData;
    }


    public function prepareMultipleSubjectsEleven($result, $eight = false, $chars = array('i','j','k','l')){

        $maximum = $this->getLargestArrayItemsCount($result);

        $number = 25;
        if($eight){
            $number = $maximum;
        }

        $rowData = array();

        $blockI = $result[0];
        $countI = count($blockI);
        if(isset($result[1])){
            $blockJ = $result[1];
            $countJ = count($blockJ);
        }
        if(isset($result[2])){
            $blockK = $result[2];
            $countK = count($blockK);
        }
        if(isset($result[3])){
            $blockL = $result[3];
            $countL = count($blockL);
        }

        for($i=1; $i<= $number; $i++){
            $i1 = '';
            $i2 = '';
            $i3 = '';
            $j1 = '';
            $j2 = '';
            $j3 = '';
            $k1 = '';
            $k2 = '';
            $k3 = '';
            $l1 = '';
            $l2 = '';
            $l3 = '';
            $iforprint = $i;
            $jforprint = $i;
            $kforprint = $i;
            $lforprint = $i;
            if($iforprint > min($countI, $maximum)){
                $iforprint = '';
            }
            for($t=1; $t <= 3; $t++){
                if(isset($blockI[$i-1]['correct']) && $blockI[$i-1]['correct'] == $t){
                    ${'i'.$t} = 'X';
                }
            }

            if(isset($blockJ) ){
                if($jforprint > min($countJ, $maximum)){
                    $jforprint = '';
                }

                for($t=1; $t <= 3; $t++){
                    if(isset($blockJ[$i-1]['correct']) && $blockJ[$i-1]['correct'] == $t){
                        ${'j'.$t} = 'X';
                    }
                }
            }else {
                $jforprint = '';
            }

            if(isset($blockK) ){
                if($kforprint > min($countK, $maximum)){
                    $kforprint = '';
                }
                for($t=1; $t <= 3; $t++){
                    if(isset($blockK[$i-1]['correct']) &&  $blockK[$i-1]['correct'] == $t){
                        ${'k'.$t} = 'X';
                    }
                }
            }else {
                $kforprint = '';
            }

            if(isset($blockL)){
                if($lforprint > min($countL, $maximum)){
                    $lforprint = '';
                }
                for($t=1; $t <= 3; $t++){
                    if(isset($blockL[$i-1]['correct']) && $blockL[$i-1]['correct'] == $t){
                        ${'l'.$t} = 'X';
                    }
                }
            }else {
                $lforprint = '';
            }

            $rowData[] = array(
                $chars[0] => $iforprint,
                $chars[0].'1' => $i1,
                $chars[0].'2' => $i2,
                $chars[0].'3' => $i3,
                $chars[1] => $jforprint,
                $chars[1].'1' => $j1,
                $chars[1].'2' => $j2,
                $chars[1].'3' => $j3,
                $chars[2] => $kforprint,
                $chars[2].'1' => $k1,
                $chars[2].'2' => $k2,
                $chars[2].'3' => $k3,
                $chars[3] => $lforprint,
                $chars[3].'1' => $l1,
                $chars[3].'2' => $l2,
                $chars[3].'3' => $l3,
            );
        }




        return $rowData;
    }

    public function getLargestArrayItemsCount($array){
        $count = array();
        foreach ($array as $item) {
            $count[] = count($item);
        }

        return max($count);
    }

    /*
     *
     /^\d{1,4}([.,\-)])/

    ^ assert position at start of the string
    \d{1,4} match a digit [0-9]
        Quantifier: {1,4} Between 1 and 4 times, as many times as possible, giving back as needed [greedy]
    1st Capturing group ([.,\-)])
        [.,\-)] match a single character present in the list below
            ., a single character in the list ., literally
            \- matches the character - literally
            ) the literal character )
     */
    public function isQuestion($text){
        if(preg_match('/^\d{1,4}([\t.,\-)])/', $text)){
            $haystack = preg_replace('/^\d{1,4}([\t.,\-)])/', '', $text);
            $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '),$haystack);
            $haystack = trim($haystack);

            return $haystack;
        }
        return false;
    }

    /*
     *

    /^[a-z]([.,)\-])/i
        ^ assert position at start of the string
        [a-z] match a single character present in the list below
            a-z a single character in the range between a and z (case insensitive)
        1st Capturing group ([.,)\-])
            [.,)\-] match a single character present in the list below
                .,) a single character in the list .,) literally
                \- matches the character - literally
        i modifier: insensitive. Case insensitive match (ignores case of [a-zA-Z])
     */
    public function isAnswer($text){
        if(preg_match('/^[a-z]{1,2}([.,)\-])/i', $text)){
            $haystack = preg_replace('/^[a-z]{1,2}([.,)\-])/i', '', $text);
            $haystack = str_replace(array(chr( 194 ) . chr( 160 ), '  '), array('',' '),$haystack);
            $haystack = trim($haystack);

            return $haystack;
        }

        return false;
    }

    public function shuffleQuestions($array) {
        $keys = array_keys($array);

        shuffle($keys);
        shuffle($keys);

        $new = array();
        foreach($keys as $key) {
            $new[] = $array[$key];
        }



        return $new;
    }

    public function buildQuestionnaire($questions){
        $result = array();
        $i=0;
        if(count($questions)){
            foreach ($questions as $question) {
                $question = trim($question);
                $question = str_replace(array("﻿", "\t", "\r", "\n"),'',$question);

                if($this->isQuestion($question)){
                    $result[$i]['question'] = $this->isQuestion($question);

                    $i++;
                }elseif($this->isAnswer($question)){
                    $answer = $this->isAnswer($question);
                    $result[$i-1]['answer'][] = $answer;

                    if(preg_match('/\[([rc]a)\]/', $answer)){
                        $result[$i-1]['correct'] = count($result[$i-1]['answer']);
                    }


                }
            }
        }

        return $result;
    }

    public function buildXmlQuestionnaire($questions, $highlighted = true, $fontsize = 10, $spacing = '1-1'){
        $xml = '';
        $step = 1;
        if(!isset($fontsize)){
            $fontsize = 10;

        }
        $fontsize = $fontsize * 2;

        if(!isset($spacing)){
            $spacing = '1-1';
        }
        $spacing = explode("-",$spacing);
        if(count($spacing) == 2){
            $before = floatval($spacing[0]);
            $after = floatval($spacing[1]);
        }

        if(!isset($before)){
            $before = 1;
        }
        if(!isset($after)){
            $after = 1;
        }

        $before = $before * 20;
        $after = $after * 20;

        $questionBefore = $before + 20;

        foreach ($questions as $question) {
            $q = $question['question'];
            $q = str_replace(array("&","<", ">"), array("&amp;", "&lt;", "&gt;"), $q);
            $a = $question['answer'];

            $xml .= '<w:p w:rsidR="00F06955" w:rsidRPr="007B5DD6" w:rsidRDefault="00B07621" w:rsidP="00B07621">
                        <w:pPr>
                            <w:spacing w:before="'.$questionBefore.'" w:after="'.$after.'"/>
                            <w:ind w:left="340" w:hanging="340"/>
                            <w:jc w:val="both"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                            <w:t>'.$step.'.</w:t>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                            <w:tab/>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                            <w:t xml:space="preserve">'.$q.'</w:t>
                        </w:r>
                    </w:p>';

            $index = 0;
            foreach ($a as $item) {

                $highlight = '';
                if($highlighted && preg_match('/\[([rc]a)\]/', $item)){
                    $highlight = '<w:highlight w:val="yellow"/>';
                }
                $answer = preg_replace('/\[([rc]a)\]/', '', $item);
                $answer = trim($answer);

                $answer = str_replace(array("&","<", ">"), array("&amp;", "&lt;", "&gt;"), $answer);

                $indx = $this->getCharacter($index);

                $xml .= '<w:p w:rsidR="00F06955" w:rsidRPr="007B5DD6" w:rsidRDefault="00B07621" w:rsidP="00B07621">
                        <w:pPr>
                            <w:tabs>
                                <w:tab w:val="left" w:pos="1512"/>
                            </w:tabs>
                            <w:autoSpaceDE w:val="0"/>
                            <w:autoSpaceDN w:val="0"/>
                            <w:adjustRightInd w:val="0"/>
                            <w:ind w:left="680" w:hanging="340"/>
                            <w:jc w:val="both"/>
                            <w:spacing w:before="'.$before.'" w:after="'.$after.'"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                                '.$highlight.'
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                                '.$highlight.'
                            </w:rPr>
                            <w:t>'.$indx.'.</w:t>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                                '.$highlight.'
                            </w:rPr>
                            <w:tab/>
                        </w:r>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                                '.$highlight.'
                            </w:rPr>
                            <w:t xml:space="preserve">'.$answer.'</w:t>
                        </w:r>
                    </w:p>';

                $index++;
            }

            $step++;

        }

        return $xml;
    }

    public function buildXmlSubject($name, $fontsize = 12){
        $xml = '';
        if(!isset($fontsize)){
            $fontsize = 12;
        }
        $fontsize = $fontsize * 2;

        if($name != ''){
            $name = strtoupper($name);

            $xml .= '<w:p w:rsidR="00F06955" w:rsidRPr="007B5DD6" w:rsidRDefault="00F06955" w:rsidP="008004F5">
                        <w:pPr>
                            
                            <w:spacing w:before="120" w:after="120"/>
                            <w:ind w:left="340" w:hanging="340"/>
                            <w:jc w:val="both"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r w:rsidRPr="007B5DD6">
                            <w:rPr>
                                <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                <w:b/>
                                <w:sz w:val="'.$fontsize.'"/>
                                <w:szCs w:val="'.$fontsize.'"/>
                            </w:rPr>
                            <w:t xml:space="preserve">'.$name.'</w:t>
                        </w:r>

                    </w:p>';
        }

        return $xml;
    }

    public function getCharacter($index){
        if($index == 0){
            return 'A';
        }elseif ($index == 1){
            return 'B';
        }elseif ($index == 2){
            return 'C';
        }

        return '';
    }
}
