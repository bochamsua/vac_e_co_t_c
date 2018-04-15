<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_ExamController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the exam
     *
     * @access protected
     * @return BS_Docwise_Model_Exam
     */
    protected function _initExam()
    {
        $examId  = (int) $this->getRequest()->getParam('id');
        $exam    = Mage::getModel('bs_docwise/exam');
        if ($examId) {
            $exam->load($examId);
        }
        Mage::register('current_exam', $exam);
        return $exam;
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
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Exams'));
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
     * edit exam - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $examId    = $this->getRequest()->getParam('id');
        $exam      = $this->_initExam();
        if ($examId && !$exam->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This exam no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getExamData(true);
        if (!empty($data)) {
            $exam->setData($data);
        }
        Mage::register('exam_data', $exam);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Exams'));
        if ($exam->getId()) {
            $this->_title($exam->getExamCode());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add exam'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new exam action
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
     * save exam - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('exam')) {
            try {
                $data = $this->_filterDates($data, array('exam_date','requested_date'));
                $exam = $this->_initExam();

                //Need to check the trainees if they are allowed to take the exam

                $scores = $this->getRequest()->getPost('score');

                $questionNo = $this->getRequest()->getPost('questionno');

                if($scores){
                    $examCode = $exam->getExamCode();
                    $examDate = $exam->getExamDate();
                    $examType = $exam->getExamType();
                    $certType = $exam->getCertType();


                    $add = '5 years';

                    if($certType == 3 || $certType == 5){
                        $add = '2 years';
                    }
                    if($examType == 2){
                        $add = '6 months';
                    }



                    $date = date_create($examDate);

                    date_add($date, date_interval_create_from_date_string($add));
                    $expireDate =  date_format($date, 'Y-m-d');

                    $i=1;
                    foreach ($scores as $key=>$value) {
                        $trainee = Mage::getModel('bs_docwise/trainee')->load($key);
                        $name = $trainee->getTraineeName();
                        $vaecoId = $trainee->getVaecoId();

                        $exDate = $expireDate;



                        $certInfo = $examCode;
                        if($certType == 5){

                            $array = explode("/", $examCode);
                            $certInfo = $array[0].'-EASA/'.$array[1];
                        }

                        $scr = (int)$value;
                        $certNo = '';
                        if($scr >= 75 ){
                            if($examType == 2 || $certType == 3){
                                $certNo = 'N/A';
                            }else {
                                $certNo = $certInfo.'/'.$i;
                                $i++;
                            }

                        }else {
                            $originalDate = date_create($examDate);
                            date_add($originalDate, date_interval_create_from_date_string('3 months'));
                            $exDate =  date_format($originalDate, 'Y-m-d');
                        }

                        $scoreModel = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('exam_id', $exam->getId())->addFieldToFilter('trainee_id', $key)->getFirstItem();
                        $scoreModel->setExamId($exam->getId())
                            ->setTraineeId($key)
                            ->setTraineeName($name)
                            ->setVaecoId($vaecoId)
                            ->setScore($value)
                            ->setCertNo($certNo)
                            ->setExamDate($examDate)
                            ->setExpireDate($exDate)
                            ->setQuestionNo($questionNo[$key])
                            ->save()
                            ;


                    }





                }



                $exam->addData($data);
                $requestFileName = $this->_uploadAndGetName(
                    'request_file',
                    Mage::helper('bs_docwise/exam')->getFileBaseDir(),
                    $data
                );
                $exam->setData('request_file', $requestFileName);

                $docwisements = $this->getRequest()->getPost('docwisements', -1);
                if ($docwisements != -1) {
                    $exam->setDocwisementsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($docwisements));
                }


                $filefolders = $this->getRequest()->getPost('filefolders', -1);
                if ($filefolders != -1) {
                    $exam->setFilefoldersData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($filefolders));
                }


                $exam->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Exam was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $exam->getId()));
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['request_file']['value'])) {
                    $data['request_file'] = $data['request_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setExamData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['request_file']['value'])) {
                    $data['request_file'] = $data['request_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the exam.')
                );
                Mage::getSingleton('adminhtml/session')->setExamData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find exam to save.')
        );
        $this->_redirect('*/*/');
    }


    public function generateFourAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getSingleton('bs_docwise/exam')->load($id);

            $this->generateFour($exam);

        }
        $this->_redirect(
            '*/docwise_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateFour($exams)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8004');

        $fileName = '8004_TOEFA-REPORT';


        $result = array();
        foreach ($exams as $examId) {
            $exam = Mage::getModel('bs_docwise/exam')->load($examId);

            $examDate = $exam->getExamDate();
            $examDate = Mage::getModel('core/date')->date("d/m/Y", $examDate);

            $code = $exam->getExamCode();
            $examType = $exam->getExamType();
            $certType = $exam->getCertType();


            $location = 'VAECO-TC';
            if($examType == 2){
                $location = 'Online';
            }

            $type = Mage::getSingleton('bs_docwise/exam_attribute_source_certtype')->getOptionText($certType);
            $title =  'Test of Aviation English - Docwise ('.$type.')';


            $trainees = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('exam_id', $exam->getId())->setOrder('score', 'DESC');

            $totalTrainees = $trainees->count();



            $traineeData = null;
            if (count($trainees)) {
                $traineeData = array();
                foreach ($trainees as $id) {
                    $traineeId = $id->getTraineeId();
                    $trainee = Mage::getModel('bs_docwise/trainee')->load($traineeId);

                    $vaecoId = $trainee->getVaecoId();
                    if($trainee->getId()){
                        $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
                        if ($staff->getId()) {

                            $customer = Mage::getModel('customer/customer')->load($staff->getId());
                            if ($customer->getDob() != '') {
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $customer->getDob());
                            }
                            $name = $customer->getName();

                            $departmentId = $customer->getGroupId();
                            $department = Mage::getModel('customer/group')->load($departmentId);

                            if ($department) {
                                $dept = $department->getCode();
                            }

                            $position = $customer->getPosition();
                            $section = $customer->getDivision();

                        }else {
                            $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $vaecoId)->getFirstItem();
                            if($tn->getId()){
                                $tnee = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                                $name = $tnee->getTraineeName();
                                $vaecoId = $tnee->getTraineeCode();
                                $dept = $tnee->getTraineeDept();

                                if ($tnee->getTraineeDob() != '') {
                                    $dob = Mage::getModel('core/date')->date("d/m/Y", $tnee->getTraineeDob());
                                }
                            }
                        }

                        if ($dept == '') {
                            $dept = 'TC';
                        }

                        $dept = str_replace("\"", "", $dept);

                        $scoreModel = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('trainee_id', $traineeId)->addFieldToFilter('exam_id', $examId)->getFirstItem();
                        $pass = "Passed";
                        if($scoreModel->getId()){
                            $certNo = $scoreModel->getCertNo();
                            $expireDate = $scoreModel->getExpireDate();
                            $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);

                            $score = (int)$scoreModel->getScore();
                            if($score < 75){
                                $pass = "Failed";
                                $expireDate = '';
                            }

                        }

                        $traineeData[] = array(
                            'name'  => $name,
                            'vaeco_id'  => $vaecoId,
                            'dept'      => $dept,
                            'dob'       => $dob,
                            'score'     => $score."/100",
                            'violation' => '',
                            'result'    => $pass,
                            'cert_no'   => $certNo,
                            'expire'    => $expireDate
                        );

                    }





                }



            }

            if($totalTrainees < 10){
                $totalTrainees = '0'.$totalTrainees;
            }
            $result[] = array(
                'title'  => $title,
                'code'  => $code,
                'date'  => $examDate,
                'location'  => $location,
                'total_trainees'    => $totalTrainees,
                'trainees' => $traineeData
            );

        }



        $contentHtml = array(
            'type' => 'replace',
            'content' => $this->prepareFour($result),
            'variable' => 'reportcontent'
        );





        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($fileName, $template, null, null, null,null,null,null,null,$contentHtml);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_docwise')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function prepareFour($data){

        $wordMl = '';
        foreach ($data as $item) {



            $wordMl .= '
            <w:tbl>
                        <w:tblPr>
                            <w:tblW w:w="5003" w:type="pct"/>
                            <w:tblInd w:w="-5" w:type="dxa"/>
                            <w:tblBorders>
                                <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                <w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                            </w:tblBorders>
                            <w:tblLook w:val="01E0" w:firstRow="1" w:lastRow="1" w:firstColumn="1" w:lastColumn="1"
                                       w:noHBand="0" w:noVBand="0"/>
                        </w:tblPr>
                        <w:tblGrid>
                            <w:gridCol w:w="535"/>
                            <w:gridCol w:w="2497"/>
                            <w:gridCol w:w="895"/>
                            <w:gridCol w:w="534"/>
                            <w:gridCol w:w="2142"/>
                            <w:gridCol w:w="1608"/>
                            <w:gridCol w:w="1071"/>
                            <w:gridCol w:w="1250"/>
                            <w:gridCol w:w="1605"/>
                            <w:gridCol w:w="1963"/>
                            <w:gridCol w:w="1605"/>
                        </w:tblGrid>
                        <w:tr w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidTr="003B6040">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1250" w:type="pct"/>
                                    <w:gridSpan w:val="3"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidRDefault="004B68D5"
                                     w:rsidP="00400706">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Title: </w:t>
                                    </w:r>
                                    <w:r w:rsidRPr="00400706">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$item['title'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="852" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidRDefault="004B68D5"
                                     w:rsidP="00400706">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Code: </w:t>
                                    </w:r>
                                    <w:r w:rsidRPr="00400706">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['code'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="853" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidRDefault="00F9352B"
                                     w:rsidP="00400706">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Date: </w:t>
                                    </w:r>
                                    <w:r w:rsidR="004B68D5" w:rsidRPr="00400706">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['date'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="909" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00400706">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:ind w:left="-72"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Location: </w:t>
                                    </w:r>
                                    <w:r w:rsidR="00400706" w:rsidRPr="00400706">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['location'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1136" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                        <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00A5304A" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00400706">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Participant(s): </w:t>
                                    </w:r>
                                    <w:r w:rsidR="004B68D5" w:rsidRPr="00400706">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$item['total_trainees'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>
                        <w:tr w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidTr="003B6040">
                            <w:trPr>
                                <w:tblHeader/>
                            </w:trPr>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="170" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>No</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="795" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Full name</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="455" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00C5040E"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Staff ID</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="682" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="003335C4"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">Company/ Department</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="512" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00F21875" w:rsidRPr="00C064EE" w:rsidRDefault="00F21875"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>DOB</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="341" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Score</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="398" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00F21875"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Exam violation</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="511" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00CC6968"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Exam result</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="625" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Certificate No.</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="511" w:type="pct"/>
                                    <w:tcBorders>
                                        <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                    </w:tcBorders>
                                    <w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00A5304A"
                                     w:rsidP="00C064EE">
                                    <w:pPr>
                                        <w:spacing w:before="40" w:after="20"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:b/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>Expiration date</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';

            if(count($item['trainees'])){
                $i=1;
                foreach ($item['trainees'] as $trainee) {
                    $dept = str_replace("&", "&amp;", $trainee['dept']);

                    $wordMl .= '
                        <w:tr w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidTr="003B6040">
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="170" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="0054258E"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00C064EE">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$i.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="795" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['name'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="455" w:type="pct"/>
                                    <w:gridSpan w:val="2"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['vaeco_id'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="682" w:type="pct"/>
                                    <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t xml:space="preserve">'.$dept.'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="512" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['dob'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="341" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['score'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="398" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="0054258E"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['violation'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="511" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['result'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="625" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00D13D18" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00D13D18">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['cert_no'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="511" w:type="pct"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="0054258E" w:rsidRPr="00C064EE" w:rsidRDefault="00D13D18"
                                     w:rsidP="003B6040">
                                    <w:pPr>
                                        <w:spacing w:before="60" w:after="60"/>
                                        <w:jc w:val="center"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
                                            <w:sz w:val="22"/>
                                            <w:szCs w:val="22"/>
                                        </w:rPr>
                                        <w:t>'.$trainee['expire'].'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                        </w:tr>';
                    $i++;
                }

            }



            $wordMl .= '
                    </w:tbl>';

        }

        return $wordMl;


    }

    public function massGenerateFourAction()
    {

        $exams = (array)$this->getRequest()->getParam('exam');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            $this->generateFour($exams);

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_docwise')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }


    public function generateSixAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getSingleton('bs_docwise/exam')->load($id);

            $this->generateSix($exam);

        }
        $this->_redirect(
            '*/docwise_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateSix($exam)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8006');

        $fileName = $exam->getExamCode() . '_8006_PARTICIPANT DATA COLLECTION';

        $deptId =  $exam->getExamRequestDept();
        $group = Mage::getModel('customer/group')->load($deptId);
        $dept = $group->getCustomerGroupCode();






        $examDate = $exam->getExamDate();
        $examDate = Mage::getModel('core/date')->date("d/m/Y", $examDate);


        $examType = $exam->getExamType();
        $certType = $exam->getCertType();


        $location = 'VAECO-TC';
        if($examType == 2){
            $location = 'Online';
        }


        //$type = Mage::getSingleton('bs_docwise/exam_attribute_source_certtype')->getOptionText($certType);


        $code = $exam->getExamCode();
        $data = array(
            'title' => 'Test of Aviation English - Docwise',
            'code'  => $code,
            'dept_info' => 'Department/Center',
            'duration' => $examDate,
            'location' => $location,


        );

        $trainees = Mage::getModel('bs_docwise/examtrainee')->getCollection()->addFieldToFilter('exam_id', $exam->getId())->setOrder('position', 'ASC');




        $tableData = null;
        $traineeData = null;
        if ($trainees->count()) {
            $traineeData = array();
            foreach ($trainees as $id) {


                $docwise = 'N';
                $function = '';
                $basic = '';
                $traineeId = $id->getTraineeId();
                $trainee = Mage::getModel('bs_docwise/trainee')->load($traineeId);

                $vaecoId = $trainee->getVaecoId();
                if($trainee->getId()){
                    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $trainee->getVaecoId())->getFirstItem();
                    if ($staff->getId()) {

                        $customer = Mage::getModel('customer/customer')->load($staff->getId());
                        if ($customer->getDob() != '') {
                            $dob = Mage::getModel('core/date')->date("d/m/Y", $customer->getDob());
                        }
                        $name = $customer->getName();

                        $departmentId = $customer->getGroupId();
                        $department = Mage::getModel('customer/group')->load($departmentId);

                        if ($department) {
                            $dept = $department->getCode();
                        }

                        $vaecoId = $customer->getVaecoId();
                        $valid = Mage::helper('bs_trainee')->checkDocwise($vaecoId, $exam->getExamDate());
                        if($valid) {
                            $docwise = 'Y';
                        }

                        $customerFunc = $customer->getStaffFunction(); // 3 - A, 4 - M
                        if($customerFunc == 3){
                            $function = 'A';
                        }elseif ($customerFunc == 4){
                            $function = 'M';
                        }

                        $customerBasic = $customer->getStaffBasic(); // 5 - T, 6 - E
                        if($customerBasic == 5){
                            $basic = 'T';
                        }elseif ($customerBasic == 6){
                            $basic = 'E';
                        }


                        //$position = $customer->getPosition();
                        //$section = $customer->getDivision();

                    }else {
                        $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $trainee->getVaecoId())->getFirstItem();
                        if($tn->getId()){
                            $tnee = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                            $name = $tnee->getTraineeName();
                            $vaecoId = $tnee->getTraineeCode();
                            $dept = $tnee->getTraineeDept();

                            $tnFunc = $tnee->getTraineeFunction();
                            if($tnFunc == 177){
                                $function = 'A';
                            }elseif ($tnFunc == 176){
                                $function = 'M';
                            }

                            $tnBas = $tnee->getTraineeBasic();
                            if($tnBas == 175){
                                $basic = 'T';
                            }elseif ($tnBas == 174){
                                $basic = 'E';
                            }
                            if ($tnee->getTraineeDob() != '') {
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $tnee->getTraineeDob());
                            }


                        }
                    }






                    if ($dept == '') {
                        $dept = 'TC';
                    }

                    $dept = str_replace("\"", "", $dept);
                    $traineeData[] = array(
                        'name' => $name,
                        'id' => $vaecoId,
                        'department'=>$dept,
                        'dob' => $dob,
                        'function' => $function,
                        'basic' => $basic,
                        'docwise' => $docwise

                    );
                }



            }

            $tableData = array($traineeData);

        }


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($fileName, $template, $data, $tableData);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_docwise')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateSixAction()
    {

        $exams = (array)$this->getRequest()->getParam('exam');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')
                    //->setStoreId($storeId)
                    ->load($examId);

                $this->generateSix($exam);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_docwise')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }


    public function generateNineAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getSingleton('bs_docwise/exam')->load($id);

            $this->generateNine($exam);

        }
        $this->_redirect(
            '*/docwise_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateNine($exam)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8009');

        $fileName = $exam->getExamCode() . '_8009 MINUTES OF EXAM';

        $deptId =  $exam->getExamRequestDept();
        $group = Mage::getModel('customer/group')->load($deptId);
        $dept = $group->getCustomerGroupCode();







        $examDate = $exam->getExamDate();
        $examDate = Mage::getModel('core/date')->date("d/m/Y", $examDate);

        $certType = $exam->getCertType();

        $examType = $exam->getExamType();


        $location = 'VAECO-TC';
        if($examType == 2){
            $location = 'Online';
        }

        $type = Mage::getSingleton('bs_docwise/exam_attribute_source_certtype')->getOptionText($certType);


        $code = $exam->getExamCode();
        $data = array(
            'title' => 'Test of Aviation English - Docwise',
            'code'  => $code,
            'dept_info' => 'Department/Center',
            'date' => $examDate,
            'time'  => '90',
            'location' => $location,
            'content'   => 'English for '.$type,
            'firstexaminer' => '',
            'secondexaminer'    => '',



        );

        $trainees = Mage::getModel('bs_docwise/examtrainee')->getCollection()->addFieldToFilter('exam_id', $exam->getId())->setOrder('position', 'ASC');


        $tableData = null;
        $traineeData = null;
        if ($trainees->count()) {
            $traineeData = array();
            foreach ($trainees as $id) {


                $traineeId = $id->getTraineeId();
                $trainee = Mage::getModel('bs_docwise/trainee')->load($traineeId);
                $vaecoId = $trainee->getVaecoId();

                if($trainee->getId()){
                    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $trainee->getVaecoId())->getFirstItem();
                    if ($staff->getId()) {

                        $customer = Mage::getModel('customer/customer')->load($staff->getId());
                        if ($customer->getDob() != '') {
                            $dob = Mage::getModel('core/date')->date("d/m/Y", $customer->getDob());
                        }
                        $name = $customer->getName();

                        $departmentId = $customer->getGroupId();
                        $department = Mage::getModel('customer/group')->load($departmentId);

                        if ($department) {
                            $dept = $department->getCode();
                        }

                        $position = $customer->getPosition();
                        $section = $customer->getDivision();

                    }else {
                        $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $trainee->getVaecoId())->getFirstItem();
                        if($tn->getId()){
                            $tnee = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                            $name = $tnee->getTraineeName();
                            $vaecoId = $tnee->getTraineeCode();
                            $dept = $tnee->getTraineeDept();

                            $tnFunc = $tnee->getTraineeFunction();
                            if($tnFunc == 177){
                                $function = 'A';
                            }elseif ($tnFunc == 176){
                                $function = 'M';
                            }

                            $tnBas = $tnee->getTraineeBasic();
                            if($tnBas == 175){
                                $basic = 'T';
                            }elseif ($tnBas == 174){
                                $basic = 'E';
                            }


                        }
                    }

                    if ($dept == '') {
                        $dept = 'TC';
                    }

                    $dept = str_replace("\"", "", $dept);

                    $traineeData[] = array(
                        'name' => $name,
                        'id' => $vaecoId,
                        'department'=>$dept,


                    );

                }




            }

            $tableData = array($traineeData);

        }


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($fileName, $template, $data, $tableData);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_docwise')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateNineAction()
    {

        $exams = (array)$this->getRequest()->getParam('exam');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')
                    //->setStoreId($storeId)
                    ->load($examId);

                $this->generateNine($exam);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_docwise')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }


    public function generateElevenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getSingleton('bs_docwise/exam')->load($id);

            $this->generateEleven($exam);

        }
        $this->_redirect(
            '*/docwise_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateEleven($exam)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8011-A');

        $fileName = $exam->getExamCode() . '_8011 MULTIPLE CHOICE ANSWER SHEET';

        $deptId =  $exam->getExamRequestDept();
        $group = Mage::getModel('customer/group')->load($deptId);
        $dept = $group->getCustomerGroupCode();






        $examDate = $exam->getExamDate();
        $examDate = Mage::getModel('core/date')->date("d/m/Y", $examDate);



        $certType = $exam->getCertType();
        $examType = $exam->getExamType();


        $location = 'VAECO-TC';
        if($examType == 2){
            $location = 'Online';
        }

        $type = Mage::getSingleton('bs_docwise/exam_attribute_source_certtype')->getOptionText($certType);


        $code = $exam->getExamCode();
        $data = array(
            'title' => 'Test of Aviation English - Docwise',
            'code'  => $code,
            'date' => $examDate,
            'time'  => '90',
            'qty'   => '100',
            'content'   => 'English for '.$type,
            'examiner' => '',



        );




        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($fileName, $template, $data);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_docwise')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateElevenAction()
    {

        $exams = (array)$this->getRequest()->getParam('exam');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')
                    //->setStoreId($storeId)
                    ->load($examId);

                $this->generateEleven($exam);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_docwise')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }
    /**
     * delete exam - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $exam = Mage::getModel('bs_docwise/exam');
                $exam->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Exam was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting exam.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find exam to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function deleteTraineeAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {

                $exam = $this->getRequest()->getParam('parent');
                $id = $this->getRequest()->getParam('id');

                //delete from exam trainee table first
                $extn = Mage::getModel('bs_docwise/examtrainee')->load($id);

                $traineeId = $extn->getTraineeId();
                $extn->delete();


                //delete from score table
                $score = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('exam_id', $exam)->addFieldToFilter('trainee_id', $traineeId);
                if($score->count()){
                    $score->walk('delete');
                }

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.trainee_gridJsObject.reload(); window.close()</script>';
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Trainee was successfully removed from the exam. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error removing traine.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find trainee to delete.')
        );
        $this->_redirect('*/*/');
    }


    /**
     * mass delete exam - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exams to delete.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                    $exam = Mage::getModel('bs_docwise/exam');
                    $exam->setId($examId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d exams were successfully deleted.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting exams.')
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
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exams.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')->load($examId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exams were successfully updated.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating exams.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Exam Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massExamTypeAction()
    {
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exams.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')->load($examId)
                    ->setExamType($this->getRequest()->getParam('flag_exam_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exams were successfully updated.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating exams.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Certificate Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCertTypeAction()
    {
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exams.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')->load($examId)
                    ->setCertType($this->getRequest()->getParam('flag_cert_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exams were successfully updated.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating exams.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Requested Department change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massExamRequestDeptAction()
    {
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exams.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                $exam = Mage::getSingleton('bs_docwise/exam')->load($examId)
                    ->setExamRequestDept($this->getRequest()->getParam('flag_exam_request_dept'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exams were successfully updated.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating exams.')
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
        $fileName   = 'exam.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_exam_grid')
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
        $fileName   = 'exam.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_exam_grid')
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
        $fileName   = 'exam.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_exam_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/exam');
    }

    public function traineesAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.trainee');
        $this->renderLayout();
    }

    public function traineesGridAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.trainee');
        $this->renderLayout();
    }

    public function docwisementsAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.docwisement')
            ->setExamDocwisements($this->getRequest()->getPost('exam_docwisements', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function docwisementsgridAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.docwisement')
            ->setExamDocwisements($this->getRequest()->getPost('exam_docwisements', null));
        $this->renderLayout();
    }


    public function filefoldersAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.filefolder')
            ->setExamFilefolders($this->getRequest()->getPost('exam_filefolders', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function filefoldersgridAction()
    {
        $this->_initExam();
        $this->loadLayout();
        $this->getLayout()->getBlock('exam.edit.tab.filefolder')
            ->setExamFilefolders($this->getRequest()->getPost('exam_filefolders', null));
        $this->renderLayout();
    }

}
