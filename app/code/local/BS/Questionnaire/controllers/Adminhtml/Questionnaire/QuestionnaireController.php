<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire admin controller
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Adminhtml_Questionnaire_QuestionnaireController extends BS_Questionnaire_Controller_Adminhtml_Questionnaire
{
    /**
     * init the questionnaire
     *
     * @access protected
     * @return BS_Questionnaire_Model_Questionnaire
     */
    protected function _initQuestionnaire()
    {
        $questionnaireId  = (int) $this->getRequest()->getParam('id');
        $questionnaire    = Mage::getModel('bs_questionnaire/questionnaire');
        if ($questionnaireId) {
            $questionnaire->load($questionnaireId);
        }
        Mage::register('current_questionnaire', $questionnaire);
        return $questionnaire;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('bs_questionnaire')->__('Instant Questionnaire'))
             ->_title(Mage::helper('bs_questionnaire')->__('Questionnaires'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit questionnaire - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $questionnaireId    = $this->getRequest()->getParam('id');
        $questionnaire      = $this->_initQuestionnaire();
        if ($questionnaireId && !$questionnaire->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_questionnaire')->__('This questionnaire no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getQuestionnaireData(true);
        if (!empty($data)) {
            $questionnaire->setData($data);
        }
        Mage::register('questionnaire_data', $questionnaire);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_questionnaire')->__('Instant Questionnaire'))
             ->_title(Mage::helper('bs_questionnaire')->__('Questionnaires'));
        if ($questionnaire->getId()) {
            $this->_title($questionnaire->getName());
        } else {
            $this->_title(Mage::helper('bs_questionnaire')->__('Add questionnaire'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new questionnaire action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save questionnaire - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('questionnaire')) {
            try {
                $questionnaire = $this->_initQuestionnaire();


                $data = $this->_filterDates($data, array('exam_date'));

                $compress = $data['compress'];
                $highlight = $data['highlight'];

                $questionnaire->addData($data);
                $questionnaire->save();

                $sFontsize = 12;
                $qFontsize = 10;
                $spacing = '0-0';
                if(floatval($data['subject_size']) > 0){
                    $sFontsize = floatval($data['subject_size']);
                }
                if(floatval($data['question_size']) > 0){
                    $qFontsize = floatval($data['question_size']);
                }
                if($data['spacing'] != ''){
                    $spacing = trim($data['spacing']);
                }


                $examDate = $data['exam_date'];

                $number = intval($data['number_of_times']);
                $qtime = floatval($data['question_time']);
                $courseId = $data['course_id'];
                $subjectId = $data['subject_id'];
                $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                if($subject->getSubjectShortcode() != ''){
                    $name = $subject->getSubjectShortcode();
                }else {
                    $name = $subject->getSubjectName();
                }
                $questions = $data['questions'];

                $template8009 = Mage::helper('bs_formtemplate')->getFormtemplate('8009');
                $template8010 = Mage::helper('bs_formtemplate')->getFormtemplate('8010');
                $template8011 = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ');
                $template8011Key = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ-KEY');


                $course = Mage::getModel('catalog/product')->load($courseId);


                $content = $name;

                $checkboxes['firstexam'] = 1;


                $name = $course->getSku();
                if($examDate != ''){
                    $date = Mage::getModel('core/date')->date("d/m/Y", $examDate);
                }else {
                    $date = Mage::getModel('core/date')->date("d/m/Y", 'now');
                }

                if($qtime == 0){
                    $qtime = 1.5;
                }

                $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);
                $traineeData = Mage::helper('bs_traininglist/course')->getCourseTraineeInfo($course);

                $currentUser = Mage::getSingleton('admin/session')->getUser();
                $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFullname());


                $data = array(
                    'date' => $date,
                    'content' => $content,
                    'preparedby' => $preparedBy,
                    'firstexaminer' => '',
                    'secondexaminer' => '',
                    'examiner' => '',
                    'q'     => '01',
                    'chap1'     => $content,
                    'chap2'     => '',
                    'chap3'     => '',
                    'chap4'     => '',
                    'chap5'     => '',
                    'chap6'     => '',
                    'chap7'     => '',
                    'chap8'     => '',
                    'chap9'     => '',
                    'chap10'     => '',
                    'chap11'     => '',
                    'chap12'     => '',


                );

                $data = array_merge($data, $basicInfo);

                //we check if the content includes multiple subjects already, subject name is put between ##


                if(preg_match('/#(.*?)#/', $questions)){
                    //split by subjects
                    $questionArray = preg_split('/#(.*?)#/', $questions, -1, PREG_SPLIT_NO_EMPTY);

                    preg_match_all('/#(.*?)#/',$questions, $matches);

                    $subjects = $matches[1];

                    //make sure number of questionnaires parsed must be equal to number of subjects, if not, we wont process
                    if(count($questionArray) == count($subjects) ){//only accept maximum 4 subjects//&& count($questionArray) <5
                        $questionData = '';

                        $qty = 0;
                        $tableData = array();


                        $newContent = array();
                        for($i=0; $i < count($questionArray); $i++){
                            $j = $i+1;


                            $data['chap'.$j] = $subjects[$i];
                            $newContent[] = $subjects[$i];
                            $subjectXML = Mage::helper('bs_questionnaire')->buildXmlSubject($subjects[$i], $sFontsize);
                            $questionData .= $subjectXML;
                            $questions = explode("\r\n", $questionArray[$i]);

                            $q = Mage::helper('bs_questionnaire')->buildQuestionnaire($questions);
                            $qty += count($q);
                            $questionData .= Mage::helper('bs_questionnaire')->buildXmlQuestionnaire($q, $highlight, $qFontsize, $spacing);

                            $tableData[] = $q;

                            if($number >= 2){
                                for($n=2; $n <= $number; $n++){
                                    ${'result'.$n} = Mage::helper('bs_questionnaire')->shuffleQuestions($q);
                                    ${'tableData'.$n}[] = ${'result'.$n};

                                    ${'questionData'.$n} .= $subjectXML;
                                    ${'questionData'.$n} .= Mage::helper('bs_questionnaire')->buildXmlQuestionnaire(${'result'.$n}, $highlight, $qFontsize, $spacing);
                                }
                            }

                        }

                        $eight = false;

                        if(count($tableData) > 4){
                            $template8011 = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ-EIGHT');
                            $template8011Key = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ-EIGHT-KEY');

                            $eight = true;

                            if(count($tableData) > 8){
                                $template8011 = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ-TWELVE');
                                $template8011Key = Mage::helper('bs_formtemplate')->getFormtemplate('8011-IQ-TWELVE-KEY');
                            }
                            $tableDataArray = array_chunk($tableData, 4);
                            $tableData = $tableDataArray[0];
                            $tableDataSecond = $tableDataArray[1];
                            if(isset($tableDataArray[2])){
                                $tableDataThird = $tableDataArray[2];
                            }


                        }

                        $tableDataFinal = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven($tableData, true);
                        $tableDataFinal = array($tableDataFinal);
                        if(isset($tableDataSecond)){
                            $tableDataFinalSecond = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven($tableDataSecond, true, array('t','w','e','r'));
                            $tableDataFinal[] = $tableDataFinalSecond;
                        }
                        if(isset($tableDataThird)){
                            $tableDataFinalThird = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven($tableDataThird, true, array('a','s','d','f'));
                            $tableDataFinal[] = $tableDataFinalThird;
                        }



                        $time = ceil($qty * $qtime);
                        if($qty < 10){
                            $qty = '0'.$qty;
                        }

                        $data['time'] = $time;
                        $data['qty'] = $qty;
                        $newContent = implode(", ", $newContent);

                        if(preg_match( '/ata/i', "moke".$newContent)){
                            $newContent = preg_replace('/ata/i', '', $newContent);
                            $newContent = 'ATA '.$newContent;
                        }

                        $data['content'] = $newContent;

                        $tableHtml = array('variable' => 'question_data','content' => $questionData);




                        try {
                            $files = array();

                            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8009 MINUTES OF EXAM'.'-'.time(), $template8009, $data, $traineeData, $checkboxes);

                            if($compress){
                                $files[] = $res['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                );
                            }


                            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8010 MULTIPLE CHOICE QUESTIONNAIRE 1'.'-'.time(), $template8010, $data, null, $checkboxes, null, null, null, null, $tableHtml);

                            if($compress){
                                $files[] = $res['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                );
                            }

                            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 MULTIPLE CHOICE ANSWER SHEET'.'-'.time(), $template8011, $data, $tableDataFinal, $checkboxes);

                            if($compress){
                                $files[] = $res['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                );
                            }

                            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 KEY 1'.'-'.time(), $template8011Key, $data, $tableDataFinal, $checkboxes);

                            if($compress){
                                $files[] = $res['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                );
                            }


                            if($number >= 2){
                                for($i=2; $i <= $number; $i++){
                                    if($i < 10){
                                        $data['q'] = '0'.$i;
                                    }else {
                                        $data['q'] = $i;
                                    }

                                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8010 MULTIPLE CHOICE QUESTIONNAIRE '.$i.'-'.time(), $template8010, $data, null, $checkboxes, null, null, null, null, array('variable' => 'question_data','content' => ${'questionData'.$i}));

                                    if($compress){
                                        $files[] = $res['url'];
                                    }else {
                                        $this->_getSession()->addSuccess(
                                            Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                        );
                                    }

                                    if($eight){

                                        $tableDataArray = array_chunk(${'tableData'.$i}, 4);
                                        ${'tableData'.$i} = $tableDataArray[0];
                                        ${'tableData'.$i.'Second'} = $tableDataArray[1];

                                        if(isset($tableDataArray[2])){
                                            ${'tableData'.$i.'Third'} = $tableDataArray[2];
                                        }
                                    }

                                    ${'tableDataFinal'.$i} = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven(${'tableData'.$i}, true);
                                    ${'tableDataFinal'.$i} = array(${'tableDataFinal'.$i});

                                    if(isset(${'tableData'.$i.'Second'})){
                                        ${'tableDataFinal'.$i.'Second'} = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven(${'tableData'.$i.'Second'}, true, array('t','w','e','r'));
                                        ${'tableDataFinal'.$i}[] = ${'tableDataFinal'.$i.'Second'};
                                    }
                                    if(isset(${'tableData'.$i.'Third'})){
                                        ${'tableDataFinal'.$i.'Third'} = Mage::helper('bs_questionnaire')->prepareMultipleSubjectsEleven(${'tableData'.$i.'Third'}, true, array('a','s','d','f'));
                                        ${'tableDataFinal'.$i}[] = ${'tableDataFinal'.$i.'Third'};
                                    }



                                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 KEY '.$i.'-'.time(), $template8011Key, $data, ${'tableDataFinal'.$i}, $checkboxes);

                                    if($compress){
                                        $files[] = $res['url'];
                                    }else {
                                        $this->_getSession()->addSuccess(
                                            Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                        );
                                    }
                                }
                            }

                            if($compress){
                                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, Mage::helper('bs_traininglist')->getFormattedText($name).'-questionnaires-'.time());
                                if($zip){
                                    $this->_getSession()->addSuccess(
                                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a href="%s" target="_blank">%s</a> to download.', $zip, 'HERE')
                                    );
                                }
                            }





                        } catch (Exception $e) {
                            $this->_getSession()->addError($e->getMessage());
                        }


                    }


                }else {
                    $questions = explode("\r\n", $questions);

                    $result = Mage::helper('bs_questionnaire')->buildQuestionnaire($questions);

                    $qty = count($result);
                    $time = ceil($qtime * $qty);
                    if($qty < 10){
                        $qty = '0'.$qty;
                    }

                    $data['time'] = $time;
                    $data['qty'] = $qty;


                    $questionData = Mage::helper('bs_questionnaire')->buildXmlQuestionnaire($result, $highlight);

                    if($number >= 2){
                        for($n=2; $n <= $number; $n++){
                            ${'result'.$n} = Mage::helper('bs_questionnaire')->shuffleQuestions($result);
                            ${'questionData'.$n} = Mage::helper('bs_questionnaire')->buildXmlQuestionnaire(${'result'.$n}, $highlight);
                        }
                    }


                    $rowData = Mage::helper('bs_questionnaire')->prepareEleven($result);
                    $tableData = array($rowData);



                    $tableHtml = array('variable' => 'question_data','content' => $questionData);


                    try {
                        $files = array();

                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8009 MINUTES OF EXAM'.'-'.time(), $template8009, $data, $traineeData, $checkboxes);

                        if($compress){
                            $files[] = $res['url'];
                        }else {
                            $this->_getSession()->addSuccess(
                                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                            );
                        }


                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8010 MULTIPLE CHOICE QUESTIONNAIRE 1'.'-'.time(), $template8010, $data, null, $checkboxes, null, null, null, null, $tableHtml);

                        if($compress){
                            $files[] = $res['url'];
                        }else {
                            $this->_getSession()->addSuccess(
                                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                            );
                        }

                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 MULTIPLE CHOICE ANSWER SHEET'.'-'.time(), $template8011, $data, $tableData, $checkboxes);

                        if($compress){
                            $files[] = $res['url'];
                        }else {
                            $this->_getSession()->addSuccess(
                                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                            );
                        }

                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 KEY 1'.'-'.time(), $template8011Key, $data, $tableData, $checkboxes);

                        if($compress){
                            $files[] = $res['url'];
                        }else {
                            $this->_getSession()->addSuccess(
                                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                            );
                        }


                        if($number >= 2){
                            for($i=2; $i <= $number; $i++){
                                if($i < 10){
                                    $data['q'] = '0'.$i;
                                }else {
                                    $data['q'] = $i;
                                }
                                $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8010 MULTIPLE CHOICE QUESTIONNAIRE '.$i.'-'.time(), $template8010, $data, null, $checkboxes, null, null, null, null, array('variable' => 'question_data','content' => ${'questionData'.$i}));

                                if($compress){
                                    $files[] = $res['url'];
                                }else {
                                    $this->_getSession()->addSuccess(
                                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                    );
                                }


                                ${'rowData'.$i} = Mage::helper('bs_questionnaire')->prepareEleven(${'result'.$i});
                                ${'tableData'.$i} = array(${'rowData'.$i});

                                $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_8011 KEY '.$i.'-'.time(), $template8011Key, $data, ${'tableData'.$i}, $checkboxes);

                                if($compress){
                                    $files[] = $res['url'];
                                }else {
                                    $this->_getSession()->addSuccess(
                                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                                    );
                                }
                            }
                        }



                        if($compress){
                            $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, Mage::helper('bs_traininglist')->getFormattedText($name).'-questionnaires'.'-'.time());
                            if($zip){
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a href="%s" target="_blank">%s</a> to download.', $zip, 'HERE')
                                );
                            }
                        }



                    } catch (Exception $e) {
                        $this->_getSession()->addError($e->getMessage());
                    }

                }

                

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $questionnaire->getId()));
                    return;
                }
                $this->_redirect('*/catalog_product/edit', array('id'=>$courseId));
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['input_file']['value'])) {
                    $data['input_file'] = $data['input_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setQuestionnaireData($data);
                $this->_redirect('*/catalog_product/edit', array('id'=>$courseId));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['input_file']['value'])) {
                    $data['input_file'] = $data['input_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_questionnaire')->__('There was a problem saving the questionnaire.')
                );
                Mage::getSingleton('adminhtml/session')->setQuestionnaireData($data);
                $this->_redirect('*/catalog_product/edit', array('id'=>$courseId));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_questionnaire')->__('Unable to find questionnaire to save.')
        );
        $this->_redirect('*/catalog_product/');
    }
    
    /**
     * delete questionnaire - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $questionnaire = Mage::getModel('bs_questionnaire/questionnaire');
                $questionnaire->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_questionnaire')->__('Questionnaire was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_questionnaire')->__('There was an error deleting questionnaire.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_questionnaire')->__('Could not find questionnaire to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete questionnaire - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $questionnaireIds = $this->getRequest()->getParam('questionnaire');
        if (!is_array($questionnaireIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_questionnaire')->__('Please select questionnaires to delete.')
            );
        } else {
            try {
                foreach ($questionnaireIds as $questionnaireId) {
                    $questionnaire = Mage::getModel('bs_questionnaire/questionnaire');
                    $questionnaire->setId($questionnaireId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_questionnaire')->__('Total of %d questionnaires were successfully deleted.', count($questionnaireIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_questionnaire')->__('There was an error deleting questionnaires.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $questionnaireIds = $this->getRequest()->getParam('questionnaire');
        if (!is_array($questionnaireIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_questionnaire')->__('Please select questionnaires.')
            );
        } else {
            try {
                foreach ($questionnaireIds as $questionnaireId) {
                $questionnaire = Mage::getSingleton('bs_questionnaire/questionnaire')->load($questionnaireId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d questionnaires were successfully updated.', count($questionnaireIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_questionnaire')->__('There was an error updating questionnaires.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Import to Question Bank? change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massImportBankAction()
    {
        $questionnaireIds = $this->getRequest()->getParam('questionnaire');
        if (!is_array($questionnaireIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_questionnaire')->__('Please select questionnaires.')
            );
        } else {
            try {
                foreach ($questionnaireIds as $questionnaireId) {
                $questionnaire = Mage::getSingleton('bs_questionnaire/questionnaire')->load($questionnaireId)
                    ->setImportBank($this->getRequest()->getParam('flag_import_bank'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d questionnaires were successfully updated.', count($questionnaireIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_questionnaire')->__('There was an error updating questionnaires.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'questionnaire.csv';
        $content    = $this->getLayout()->createBlock('bs_questionnaire/adminhtml_questionnaire_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'questionnaire.xls';
        $content    = $this->getLayout()->createBlock('bs_questionnaire/adminhtml_questionnaire_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'questionnaire.xml';
        $content    = $this->getLayout()->createBlock('bs_questionnaire/adminhtml_questionnaire_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/questionnaire');
    }



}
