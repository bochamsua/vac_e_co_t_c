<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */

require_once(Mage::getBaseDir('lib') . DS.'phpdocx'.DS.'CreateDocx.php');

class BS_Traininglist_Helper_Docx extends BS_Traininglist_Helper_Data
{

    public function generateDocx($filename, $template, $data, $tableData = null, $checkboxes = null, $listvars = null, $content = null, $footer = null, $html = null, $tableHtml = null, $replaceContent = null, $preview = false ){
        $finalDir = $this->getFinalDir();

        $finalUrl = $this->getFinalUrl();

        $filename = $this->getFormattedText($filename);

        $docx = new CreateDocxFromTemplate($template);


        if($data){
            $docx->replaceVariableByText($data);
        }

        if($checkboxes){
            $docx->tickCheckboxes ($checkboxes);
        }

        if($listvars){
            foreach ($listvars as $key => $value) {
                $docx->replaceListVariable($key, $value);
            }

        }

        if($tableData){
            if(count($tableData)){
                foreach ($tableData as $td) {
                    $docx->replaceTableVariable($td);
                }

            }
        }
        if($replaceContent){
            $docx->replaceVariableByExternalFile(array('content'=>$replaceContent),array());
        }
        if($content){
            if(count($content)){
                foreach ($content as $cot) {
                    $docx->addExternalFile($cot);
                }

            }

            //$docx->addExternalFile(array('src' => $content));
        }

        if($html){
            if(count($html)){
                foreach ($html as $ht) {
                    $docx->replaceVariableByHTML($ht['code'],'block',$ht['content']);
                }

            }

        }

        if($tableHtml){

            if($tableHtml['type'] == 'embed'){
                $docx->embedHTML($tableHtml['content'], array('customListStyles'=>true));
            }else {
                if(isset($tableHtml['isarray'])){
                    foreach ($tableHtml['array'] as $key=>$value ) {
                        $docx->replaceVariableByWordML(array($key => $value));
                    }

                }else {
                    $docx->replaceVariableByWordML(array($tableHtml['variable'] => $tableHtml['content']));
                }

            }


        }

        if($footer){
            //$docx->replaceVariableByExternalFile(array('footer'=>$footer),array());
            $docx->addExternalFile(array('src' => $footer));
        }

        $res = $docx->createDocx($finalDir.$filename);

        if($res){
            if($preview){
                $convertFile = $this->convertFile($finalDir.$filename.'.docx', 'html');
                return array(
                    'name'  => $filename,
                    'url'   => $convertFile
                );
            }
            return array(
                'name'  => $filename,
                'url'   => $finalUrl.$filename.'.docx'
            );
        }

        return false;

    }

    public function getFinalDir(){
        return Mage::getBaseDir('media').DS.'files'.DS;
    }
    public function getFinalUrl(){
        return Mage::getBaseUrl('media').'/files/';
    }

    public function prepareQuestionHtml($file){
        $questionData = $this->readInputDocx($file);
        $html = "<p style='font-family: \"Arial\"; font-size: 11pt;'>";

        $i=1;
        foreach ($questionData as $q) {
            $html .= "<p style='font-family: \"Arial\"; font-size: 11pt; margin-top:6.0pt;margin-bottom:3.0pt; margin-left:17.0pt;text-indent:-17pt'><b>".$i.".&nbsp;".$q['question']."</b></p>";
            $j='A';
            foreach ($q['answer'] as $ans) {
                $bg = "";
                if($ans['ra']){
                    //$bg = "style='background:yellow;mso-highlight:yellow'";
                }

                $html .= "<p style='font-family: \"Arial\"; font-size: 11pt;margin-left:34.0pt;text-indent:-17pt;  margin-top:3.0pt;margin-bottom:3.0pt;'><span ".$bg.">".$j.".&nbsp;".$ans['answer']."</span></p>";

                $j++;
            }


            $i++;
        }

        $html .= "</p>";

        return $html;
    }

    public function readInputDocx($file){
        $reader = new WordPHP();

        $text = $reader->readDocument($file);

        $html = str_get_html($text);
        $data = $html->find('p');

        $finalQuestions = array();

        $questions = array();
        foreach ($data as $d) {

            $q = $d->innertext;

            $q = $this->optimizeQuestion($q);
            if($q){
                $questions[] = $q;
            }

        }

        $result = array_chunk($questions, 4);

        $i=1;
        foreach ($result as $q) {
            $question = $q[0];

            $answers = array();
            unset($q[0]);
            $j=1;
            foreach ($q as $ans) {
                $ra = false;
                if(strpos($ans, "[ra]") || strpos($ans, "[ ra]") || strpos($ans, "[ ra ]") || strpos($ans, "[ra ]")){
                    $ra = true;
                }

                $ans = str_replace(array("[ra]","[ ra]","[ra ]","[ ra ]"),"",$ans);

                $answers[] = array(
                    'answer' => $ans,
                    'ra'    => $ra
                );

                $j++;
            }

            $finalQuestions[] = array(
                'question'  => $question,
                'answer'    => $answers,
            );


            $i++;
        }

        return $finalQuestions;
    }

    public function optimizeQuestion($question){
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

    public function convertFile($file, $format, $finalDir = false){
        $finalUrl = Mage::getBaseUrl('media').'/files/';

        if(!$finalDir){
            $finalDir = Mage::getBaseDir('media').DS.'files'.DS;
        }

        $ext = $format;

        if($format == 'html'){
            $format = 'html:XHTML Writer File:UTF8';
        }
        /*
         * Eg. --convert-to pdf *.doc
          --convert-to pdf:writer_pdf_Export --outdir /home/user *.doc
          --convert-to "html:XHTML Writer File:UTF8" *.doc
          --convert-to "txt:Text (encoded):UTF8" *.doc

         */
        $server = $_SERVER['SERVER_NAME'];
        if(strpos($server, ".local")){
            $libreoffice = '/Applications/LibreOffice.app/Contents/MacOS/soffice';
        }else {
            $libreoffice = 'libreoffice';
        }
        $command = "$libreoffice --headless --convert-to \"$format\" --outdir $finalDir $file";

        $files = explode(".", $file);


        unset($files[count($files)-1]);

        $filename = implode(".", $files);

        $filename = explode("/", $filename);
        $filename = $filename[count($filename)-1];

        exec($command, $output, $returnCode);

        if ($returnCode) {
            return FALSE;
        }


        $url =  $finalUrl.$filename.'.'.$ext;
        $url = str_replace("//", "/", $url);

        return $url;
    }

    public function zipFiles($files, $zipName='zippedfile'){
        $zip = new ZipArchive();
        $finalDir = Mage::getBaseDir('media').DS.'files'.DS;
        $finalUrl = Mage::getBaseUrl('media').'/files/';

        $filename = $finalDir.$zipName.'.zip';



        if ($zip->open($filename, ZipArchive::CREATE)) {
            foreach ($files as $file) {

                $name = str_replace($finalUrl,'',$file);

                $file = str_replace($finalUrl,$finalDir,$file);




                $zip->addFile($file, $name);
            }


            $zip->close();

            return $finalUrl.$zipName.'.zip';
        }

        return false;



    }
}
