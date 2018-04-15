<?php

require_once 'phpdocx/classes/CreateDocx.inc';

require_once 'app/Mage.php';




umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableCatProd = $resource->getTableName('catalog_category_product');

$fileName = '8010';



$result  = $readConnection->fetchRow("SELECT * FROM bcourses WHERE course_id = 2");

$courseCode = $result['course_code'];
$courstTitle = $result['course_name'];
$courseId = $result['course_id'];

//test with ATA22
$subjectId = 3;
$ata = 'ATA 22';

$courstTitle = strtoupper($courstTitle);
$courseCode = strtoupper($courseCode);








$docx = new CreateDocxFromTemplate('files/'.$fileName.'.docx');

$latinListOptions = array();
$latinListOptions[0]['type'] = 'decimal';
$latinListOptions[0]['format'] = '%1.';
$latinListOptions[0]['font'] = 'Arial';
$latinListOptions[0]['hanging'] = 360;
$latinListOptions[0]['left'] = 360;
$latinListOptions[1]['type'] = 'upperLetter';
$latinListOptions[1]['format'] = '%2.';
$latinListOptions[1]['left'] = 720;
$latinListOptions[1]['font'] = 'Arial';
//Create the list style with name: latin
$docx->createListStyle('latin', $latinListOptions);
//List items
$myList = array('item 1', array('subitem 1.1', 'subitem 1.2'), 'item 2');
//Insert custom list into the Word document

$sqlQuestions = "SELECT * FROM bquestions WHERE course_id = {$courseId} AND subject_id = {$subjectId}";
$questions = $readConnection->fetchAll($sqlQuestions);

$qty = count($questions);

$variables = array(
    'course_title' => $courstTitle,
    'course_code' => $courseCode,
    'content'=>$ata,
    'qno'=>'01',
    'qqty'=>$qty,
    'ata'=>$ata,

);

$list = array();
foreach ($questions as $q) {
    $questionId = $q['question_id'];
    $questionText = $q['question_text'];

    $list[] = $questionText;
    $sqlA = "SELECT * FROM banswers WHERE question_id = {$questionId} ORDER BY answer_position ASC";
    $answers = $readConnection->fetchAll($sqlA);

    $ans = array();
    foreach ($answers as $a) {
        $ans[] = $a['answer_text'];
    }
    $list[] = $ans;


}

$options = array(
    'font' => 'Arial',

    'fontSize' => 10,

);


$docx->addList($list, 'latin', $options);



$docx->replaceVariableByText($variables);

$docx->createDocx('files/Final '.$fileName);










