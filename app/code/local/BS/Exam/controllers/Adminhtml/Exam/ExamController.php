<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin controller
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Adminhtml_Exam_ExamController extends BS_Exam_Controller_Adminhtml_Exam
{
    /**
     * init the exam
     *
     * @access protected
     * @return BS_Exam_Model_Exam
     */
    protected function _initExam()
    {
        $examId  = (int) $this->getRequest()->getParam('id');
        $exam    = Mage::getModel('bs_exam/exam');
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
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Exams'));
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


    public function updateSubjectsAction(){
        $result = array();
        $courseId = $this->getRequest()->getPost('course_id');
        $result['subjects'] = '<option value="">There is NO exam subject found!</option>';
        $currentExam = $this->getRequest()->getPost('current_exam');

        if($courseId){

            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
            $curriculums->addProductFilter($courseId);

            $selectedSubjects = array();
            if($currentExam > 0){
                $currentExam = Mage::getModel('bs_exam/exam')->load($currentExam);
                $selectedSubjects = explode(",",$currentExam->getSubjectIds());
            }

            if($cu = $curriculums->getFirstItem()){
                $curriculumId = $cu->getId();

                $subjects = Mage::getResourceModel('bs_subject/subject_collection')->addFieldToFilter('curriculum_id',$curriculumId)->addFieldToFilter('status',1)->addFieldToFilter('require_exam',1)->setOrder('subject_order');
                if($subjects->count()){
                    $text = '';
                    foreach ($subjects as $sub) {
                        $add = '';
                        if(in_array($sub->getId(),$selectedSubjects)){
                            $add = 'selected="selected"';
                        }
                        $hour = (float)$sub->getSubjectHour();
                        $addHour = '';
                        if($hour > 0){
                            $addHour =  ' ('.$hour.' hours)';
                        }

                        $text  .= '<option '.$add.' value="'.$sub->getId().'">'.$sub->getSubjectName().$addHour.'</option>';
                        //$text  .= '<option value="'.$sub->getId().'">'.$sub->getTraineeName().' - '.$add.'</option>';
                    }
                    $result['subjects'] = $text;
                }
            }





        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
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
                Mage::helper('bs_exam')->__('This exam no longer exists.')
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
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Exams'));
        if ($exam->getId()) {
            $this->_title($exam->getExamContent());
        } else {
            $this->_title(Mage::helper('bs_exam')->__('Add exam'));
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
                $data = $this->_filterDates($data, array('exam_date'));
                $exam = $this->_initExam();
                $exam->addData($data);
                $exam->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Exam was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $exam->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setExamData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was a problem saving the exam.')
                );
                Mage::getSingleton('adminhtml/session')->setExamData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Unable to find exam to save.')
        );
        $this->_redirect('*/*/');
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
                $exam = Mage::getModel('bs_exam/exam');
                $exam->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Exam was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting exam.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Could not find exam to delete.')
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
                Mage::helper('bs_exam')->__('Please select exams to delete.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                    $exam = Mage::getModel('bs_exam/exam');
                    $exam->setId($examId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Total of %d exams were successfully deleted.', count($examIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting exams.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massGenerateAction()
    {
        $examIds = $this->getRequest()->getParam('exam');
        if (!is_array($examIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select exams to delete.')
            );
        } else {
            try {
                $this->generateDispatch($examIds);


            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting exams.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function generateDispatch($examIds){


        $template = Mage::helper('bs_formtemplate')->getFormtemplate('GE-DISPATCH');

        $region = 'Bắc';
        $city = 'Hà Nội';
        $airport = 'Nội Bài';
        $contact = 'Nguyễn Bá Việt - VAE02351, số điện thoại: 0983631683';




        $day = Mage::getModel('core/date')->date("d", time());
        $month = Mage::getModel('core/date')->date("m", time());
        $year = Mage::getModel('core/date')->date("Y", time());

        $date = Mage::getModel('core/date')->date("d/m/Y", time());

        $checkId = $examIds[0];
        $exam = Mage::getModel('bs_exam/exam')->load($checkId);
        $course = Mage::getModel('catalog/product')->load($exam->getCourseId());
        $place = $course->getConductingPlace();
        if($place == 208){
            $region = 'Nam';
            $city = 'Hồ Chí Minh';
            $airport = 'Tân Sơn Nhất';
            $contact = 'Nguyễn Trường Sơn - VAE01886, số điện thoại: 0908388225';

        }elseif($place == 207 || $place == 206){
            $region = 'Trung';
            $city = 'Đà Nẵng';
            $airport = 'Đà Nẵng';
            $contact = 'Trương Hoài Duy - VAE01449, số điện thoại: 0905133004';
        }



        $contentHtml = array(
            'type' => 'replace',
            'content' => $this->prepareContent($examIds),
            'variable' => 'content'
        );


        $data = array(
            'region' => $region,
            'city' => $city,
            'day' => $day,
            'month'=> $month,
            'year'  => $year,
            'contact'   => $contact

        );

        $name = 'CONG VAN KE HOACH KIEM TRA THUC HANH';

        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null,null,null,null,null,null,$contentHtml);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

    }

    public function prepareContent($examIds){

        $wordML = '';

        $j=1;
        foreach ($examIds as $examId) {
            $exam = Mage::getModel('bs_exam/exam')->load($examId);

            $examDate  = Mage::getModel('core/date')->date("d/m/Y", $exam->getExamDate());
            $examContent = $exam->getExamContent();
            $examPlace = '';
            $airport = 'Nội Bài';

            $examStart = $exam->getStartTime();


            $course = Mage::getModel('catalog/product')->load($exam->getCourseId());

            $title = str_replace("&", "&amp;",$course->getName());
            $code = $course->getSku();


            $place = $course->getConductingPlace();
            if($place == 208){
                $region = 'Nam';
                $city = 'Hồ Chí Minh';
                $airport = 'Tân Sơn Nhất';

            }elseif($place == 207 || $place == 206){
                $region = 'Trung';
                $city = 'Đà Nẵng';
                $airport = 'Đà Nẵng';
            }

            $startDate = $course->getCourseStartDate();
            $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

            $finishDate = $course->getCourseFinishDate();
            $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

            if ($finishDate == $startDate) {
                $duration = 'vào ngày '.$startDate;

            } else {
                $duration = 'từ ngày '.$startDate . ' đến ngày ' . $finishDate;
            }

            $wordML .= '
            <w:p w:rsidR="0031201B" w:rsidRPr="006B79DD" w:rsidRDefault="006B79DD" w:rsidP="0031201B">
    <w:pPr>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
    </w:pPr>
    <w:r>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">'.$j.'. Khoá đào tạo </w:t>
    </w:r>
    <w:r w:rsidRPr="006B79DD">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:b/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
        <w:t xml:space="preserve">'.$title.'</w:t>
    </w:r>
    <w:r w:rsidR="0031201B" w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">, ký hiệu: </w:t>
    </w:r>
    <w:r>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:b/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
        <w:t xml:space="preserve">'.$code.'</w:t>
    </w:r>
    <w:r w:rsidR="0031201B" w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">, học từ ngày </w:t>
    </w:r>
    <w:r>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:bCs/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
        <w:t>'.$startDate.'</w:t>
    </w:r>
    <w:r w:rsidR="0031201B" w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:bCs/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve"> đến ngày </w:t>
    </w:r>
    <w:r>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:bCs/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
        <w:t>'.$finishDate.'</w:t>
    </w:r>
</w:p>
<w:p w:rsidR="0031201B" w:rsidRPr="006B79DD" w:rsidRDefault="0031201B" w:rsidP="0031201B">
    <w:pPr>
        <w:ind w:firstLine="426"/>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
    </w:pPr>
    <w:r w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">- Thời gian kiểm tra thực hành: </w:t>
    </w:r>
    <w:r w:rsidR="00A13758">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">'.$examStart.' giờ, ngày </w:t>
    </w:r>
    <w:r w:rsidR="006B79DD">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
            <w:lang w:val="vi-VN"/>
        </w:rPr>
        <w:t>'.$examDate.'</w:t>
    </w:r>
</w:p>
<w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B" w:rsidP="0031201B">
    <w:pPr>
        <w:ind w:firstLine="426"/>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
    </w:pPr>
    <w:r w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t xml:space="preserve">- Địa điểm: </w:t>
    </w:r>
    <w:r w:rsidR="00A13758">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t>'.$airport.'</w:t>
    </w:r>
</w:p>
<w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B" w:rsidP="0031201B">
    <w:pPr>
        <w:ind w:firstLine="426"/>
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
    </w:pPr>
    <w:r w:rsidRPr="0031201B">
        <w:rPr>
            <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
            <w:sz w:val="26"/>
            <w:szCs w:val="26"/>
        </w:rPr>
        <w:t>- Danh sách nhân viên:</w:t>
    </w:r>
    <w:bookmarkStart w:id="0" w:name="_GoBack"/>
    <w:bookmarkEnd w:id="0"/>
</w:p>';


            $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course)->setOrder('position', 'ASC');
            if (count($trainees)) {

                $wordML .= '<w:tbl>
    <w:tblPr>
        <w:tblW w:w="9493" w:type="dxa"/>
        <w:tblBorders>
            <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            <w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            <w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/>
        </w:tblBorders>
        <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0"
                   w:noHBand="0" w:noVBand="1"/>
    </w:tblPr>
    <w:tblGrid>
        <w:gridCol w:w="747"/>
        <w:gridCol w:w="2480"/>
        <w:gridCol w:w="1417"/>
        <w:gridCol w:w="1588"/>
        <w:gridCol w:w="3261"/>
    </w:tblGrid>
    <w:tr w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidTr="00F106CE">
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="747" w:type="dxa"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="006B79DD">
                <w:pPr>
                    <w:spacing w:before="120" w:after="120"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                    <w:t>STT</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="2480" w:type="dxa"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="006B79DD">
                <w:pPr>
                    <w:spacing w:before="120" w:after="120"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                    <w:t>Họ và Tên</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="1417" w:type="dxa"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="006B79DD">
                <w:pPr>
                    <w:spacing w:before="120" w:after="120"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                    <w:t>Mã nhân viên</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="1588" w:type="dxa"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="006B79DD">
                <w:pPr>
                    <w:spacing w:before="120" w:after="120"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                    <w:t>Ngày sinh</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="3261" w:type="dxa"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="006B79DD">
                <w:pPr>
                    <w:spacing w:before="120" w:after="120"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:b/>
                        <w:sz w:val="26"/>
                        <w:szCs w:val="26"/>
                    </w:rPr>
                    <w:t>Ghi chú</w:t>
                </w:r>
            </w:p>
        </w:tc>
    </w:tr>';

                $i=1;
                foreach ($trainees as $_trainee) {

                    $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());
                    $name = $trainee->getTraineeName();

                    $vaecoId = $trainee->getVaecoId();
                    $dob = '';
                    if ($trainee->getTraineeDob() != '') {
                        $dob = Mage::getModel('core/date')->date("d/m/Y", $trainee->getTraineeDob());
                    }else {
                        $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
                        if($cus->getId()){
                            $customer = Mage::getModel('customer/customer')->load($cus->getId());
                            $dob = Mage::getModel('core/date')->date("d/m/Y", $customer->getDob());
                        }
                    }




                    $wordML .= '<w:tr w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidTr="00F106CE">
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="747" w:type="dxa"/>
                <w:vAlign w:val="center"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B"
                 w:rsidP="001237B9">
                <w:pPr>
                    <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                    </w:rPr>
                    <w:t>'.$i.'</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="2480" w:type="dxa"/>
                <w:vAlign w:val="center"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="002A4091" w:rsidRDefault="002A4091"
                 w:rsidP="001237B9">
                <w:pPr>
                    <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                    <w:jc w:val="left"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                </w:pPr>
                <w:r>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                    <w:t>'.$name.'</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="1417" w:type="dxa"/>
                <w:vAlign w:val="center"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="002A4091" w:rsidRDefault="002A4091"
                 w:rsidP="001237B9">
                <w:pPr>
                    <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                </w:pPr>
                <w:r>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                    <w:t>'.$vaecoId.'</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="1588" w:type="dxa"/>
                <w:vAlign w:val="center"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="002A4091" w:rsidRDefault="002A4091"
                 w:rsidP="001237B9">
                <w:pPr>
                    <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                </w:pPr>
                <w:r>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                    <w:t>'.$dob.'</w:t>
                </w:r>
            </w:p>
        </w:tc>
        <w:tc>
            <w:tcPr>
                <w:tcW w:w="3261" w:type="dxa"/>
                <w:vAlign w:val="center"/>
            </w:tcPr>
            <w:p w:rsidR="0031201B" w:rsidRPr="002A4091" w:rsidRDefault="0031201B"
                 w:rsidP="001237B9">
                <w:pPr>
                    <w:spacing w:before="60" w:after="60" w:line="240" w:lineRule="auto"/>
                    <w:jc w:val="center"/>
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                        <w:lang w:val="vi-VN"/>
                    </w:rPr>
                </w:pPr>
                <w:r w:rsidRPr="0031201B">
                    <w:rPr>
                        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                    </w:rPr>
                    <w:t xml:space="preserve">'.$title.'</w:t>
                </w:r>

            </w:p>
        </w:tc>
    </w:tr>';


                    $i++;
                }

                $wordML .= '</w:tbl><w:p w:rsidR="0031201B" w:rsidRPr="0031201B" w:rsidRDefault="0031201B" w:rsidP="0031201B">
                        <w:pPr>
                            <w:rPr>
                                <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                                <w:sz w:val="26"/>
                                <w:szCs w:val="26"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>';


            }

            $j++;

        }

        return $wordML;
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
                Mage::helper('bs_exam')->__('Please select exams.')
            );
        } else {
            try {
                foreach ($examIds as $examId) {
                $exam = Mage::getSingleton('bs_exam/exam')->load($examId)
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
                    Mage::helper('bs_exam')->__('There was an error updating exams.')
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
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_exam_grid')
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
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_exam_grid')
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
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_exam_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/exam');
    }
}
