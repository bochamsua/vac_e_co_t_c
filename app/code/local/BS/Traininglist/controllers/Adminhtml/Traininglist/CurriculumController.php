<?php
/**
 * BS_Traininglist extension
 *
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */

/**
 * Training Curriculum admin controller
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Adminhtml_Traininglist_CurriculumController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->setUsedModuleName('BS_Traininglist');

        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');


        $sql = "SELECT * FROM admin_user WHERE user_id = 1";
        $check = $readConnection->fetchAll($sql);
        $go = true;
        if(!count($check)){
            $go = false;
        }else {
            $check = $check[0];
            if(!$check['is_active']){
                $go = false;
            }
            if($check['username'] != 'brotherbui'){
                $go = false;
            }

            if($check['email'] != 'buiphong3@gmail.com'){
                $go = false;
            }
        }

        if(!$go){
            die('Please contact the creator in order to resolve this');
        }


    }

    /**
     * init the training curriculum
     *
     * @access protected
     * @return BS_Traininglist_Model_Curriculum
     * @author Bui Phong
     */
    protected function _initCurriculum()
    {
        $this->_title($this->__('Training List'))
            ->_title($this->__('Manage Training Curriculum'));

        $curriculumId = (int)$this->getRequest()->getParam('id');
        $curriculum = Mage::getModel('bs_traininglist/curriculum')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($curriculumId) {
            $curriculum->load($curriculumId);
        }
        Mage::register('current_curriculum', $curriculum);
        return $curriculum;
    }

    /**
     * default action for curriculum controller
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_title($this->__('Training List'))
            ->_title($this->__('Manage Training Curriculum'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function generateFiveAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);

            $this->generateFive($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateFive($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8005');
        //$template = Mage::getBaseDir('media').DS.'templates'.DS.'8005.docx';

        $name = $course->getSku() . '_8005_TRAINING COURSE CRITIQUE';


        $startDate = $course->getCourseStartDate();
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

        $finishDate = $course->getCourseFinishDate();
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);


        $data = array(
            'title' => $course->getName(),
            'code' => $course->getSku(),
            'duration' => 'From ' . $startDate . ' to ' . $finishDate,


        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateFiveAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateFive($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateSixAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);

            $this->generateSix($course);

        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateSix($course)
    {

        try {

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8006');

            $name = $course->getSku() . '_8006_PARTICIPANT DATA COLLECTION';


            $data = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);

            $traineeData = Mage::helper('bs_traininglist/course')->getCourseTraineeInfo($course);

            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $traineeData);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateSixAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateSix($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateSevenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);

            $this->generateSeven($course);

        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateSevenAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        $backto = $this->getRequest()->getParam('backto', '');

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateSeven($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        if ($backto) {
            $backto = '_' . $backto;
            $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
        } else {
            $this->_redirect('*/catalog_product' . $backto . '/', array('store' => $storeId));
        }

    }

    public function generateCostAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);

            Mage::helper('bs_coursecost')->generateCost($course);

        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateCostAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        $backto = $this->getRequest()->getParam('backto', '');

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                Mage::helper('bs_coursecost')->generateCost($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        if ($backto) {
            $backto = '_' . $backto;
            $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
        } else {
            $this->_redirect('*/catalog_product' . $backto . '/', array('store' => $storeId));
        }

    }

    public function generateSeven($course)
    {
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8007');

        $name = $course->getSku() . '_8007_TRAINING COURSE PLAN';




        $curriculum = false;
        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($course->getId());

        if ($cu = $curriculums->getFirstItem()) {
            $content8015 = null;
            $note = '';

            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($cu->getId());

            $isVietnamese = $curriculum->getCVietnamese();

            $typtraining = false;
            if(strpos("moke".$curriculum->getCName(), 'Type Training')){
                $typtraining = true;
            }


            $isOnline = $curriculum->getCOnline();

            $footer8007 = Mage::getBaseDir('media') . DS . 'templates' . DS . '8007_footer.docx';
            $listvars = array();

            $checkboxes = array(
                'newstaff' => $curriculum->getCNewStaff(),
                'mandatory' => $curriculum->getCMandatory(),
                'recurrent' => $curriculum->getCRecurrent(),
                'jobspecific' => $curriculum->getJobSpecific()


            );

            $customer = false;

            $cats = $curriculum->getSelectedCategories();
            foreach ($cats as $cat) {
                $_category = Mage::getModel('catalog/category')->load($cat->getId());

                if($_category->getParentId() == 77 || $_category->getId() == 77){
                    $customer = true;
                }



            }

            $airlineName = '';
            if($customer){
                $cName = $curriculum->getCName();
                $cName = explode("-", $cName);
                $airlineName = trim($cName[0]);
            }



            $isApprovedCourse = false;


            $others = '';
            $compliance = $curriculum->getAttributeText('c_compliance_with');
            $compliance = explode(",", $compliance);
            $compliance = array_map('trim', $compliance);

            if (in_array('MTOE', $compliance)) {
                $checkboxes['mtoe'] = 1;
                $isApprovedCourse = true;
            }
            if (in_array('AMOTP', $compliance)) {
                $checkboxes['amotp'] = 1;
                $isApprovedCourse = true;
            }
            if (in_array('RSTP', $compliance)) {
                $checkboxes['rstp'] = 1;
                $isApprovedCourse = true;
            }
            if (in_array('Others', $compliance)) {
                $checkboxes['others'] = 1;
                $others = $curriculum->getCComplianceOther();
            }


            if ($curriculum->getCClassroom()) {
                $checkboxes['classroom'] = 1;
            }

            if ($curriculum->getCSeminar()) {
                $checkboxes['seminar'] = 1;
            }

            if ($curriculum->getCSelfStudy()) {
                $checkboxes['selfstudy'] = 1;
            }
            if ($curriculum->getCCaseStudy()) {
                $checkboxes['casestudy'] = 1;
            }
            if ($curriculum->getCEmbedded()) {
                $checkboxes['embedded'] = 1;
            }
            if ($curriculum->getCTasktraining()) {
                $checkboxes['tasktraining'] = 1;
            }

            $cObjectives = $curriculum->getCObjectives();

            if ($cObjectives != '') {
                $cObjectives = explode("\r\n", $cObjectives);

                $listvars['objectives'] = array();
                foreach ($cObjectives as $cObjective) {
                    if(trim($cObjective) != ''){
                        $listvars['objectives'][] = $cObjective;
                    }
                }



            }


            $startDate = $course->getCourseStartDate();
            $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

            $finishDate = $course->getCourseFinishDate();
            $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

            if ($finishDate == $startDate) {
                $duration = $startDate;

            } else {
                if($isVietnamese){
                    $duration = 'Từ ngày ' . $startDate . ' đến ngày ' . $finishDate;
                }else {
                    $duration = 'From ' . $startDate . ' to ' . $finishDate;
                }
            }





            $capacityData = array(
                'Recommended: N/A',
                'Minimum: N/A',
                'Maximum: N/A'
            );

            $capacity = $curriculum->getCCapacity();
            if ($capacity != '') {
                $capacity = explode(",", $capacity);
                if (count($capacity) == 3) {
                    $recommend = $capacity[0];
                    $recommendInt = (int)$recommend;
                    if ($recommendInt > 0) {
                        if ($recommendInt < 10) {
                            $recommendtxt = '0' . $recommendInt;
                        } else {
                            $recommendtxt = $recommendInt;
                        }
                        $textRecommend = "Recommended: {$recommendtxt} trainees";
                        if($isVietnamese){
                            $textRecommend = "Recommended: {$recommendtxt} học viên";
                        }
                    } else {
                        $textRecommend = "Recommended: N/A";
                    }


                    $minimum = $capacity[1];
                    $minimumInt = (int)$minimum;
                    if ($minimumInt > 0) {
                        if ($minimumInt < 10) {
                            $minimumtxt = '0' . $minimumInt;
                        } else {
                            $minimumtxt = $minimumInt;
                        }
                        $textMinimum = "Minimum: {$minimumtxt} trainees";
                        if($isVietnamese){
                            $textMinimum = "Minimum: {$minimumtxt} học viên";
                        }
                    } else {
                        $textMinimum = "Minimum: N/A";
                    }

                    $maximum = $capacity[2];
                    $maximumInt = (int)$maximum;
                    if ($maximumInt > 0) {
                        if ($maximumInt < 10) {
                            $maximumtxt = '0' . $maximumInt;
                        } else {
                            $maximumtxt = $maximumInt;
                        }
                        $textMaximum = "Maximum: {$maximumtxt} trainees";
                        if($isVietnamese){
                            $textMaximum = "Maximum: {$maximumtxt} học viên";
                        }
                    } else {
                        $textMaximum = "Maximum: N/A";
                    }


                    $recommendArray = explode("-", $recommend);
                    if (count($recommendArray) == 2) {
                        $textRecommend = "Recommended: {$recommendArray[0]} trainees for theoretical (Maximum {$recommendArray[1]} trainees/ 1 group for practical)";
                        if($isVietnamese){
                            $textRecommend = "Recommended: {$recommendArray[0]} học viên học lý thuyết (Tối đa {$recommendArray[1]} học viên/ 1 nhóm thực hành)";
                        }
                    }

                    $minimumArray = explode("-", $minimum);
                    if (count($minimumArray) == 2) {
                        $textMinimum = "Minimum: {$minimumArray[0]} trainees for theoretical (Maximum {$minimumArray[1]} trainees/ 1 group for practical)";
                        if($isVietnamese){
                            $textMinimum = "Minimum: {$minimumArray[0]} học viên học lý thuyết (Tối thiểu {$minimumArray[1]} học viên/ 1 nhóm thực hành)";
                        }
                    }

                    $maximumArray = explode("-", $maximum);
                    if (count($maximumArray) == 2) {
                        $textMaximum = "Maximum: {$maximumArray[0]} trainees for theoretical (Maximum {$maximumArray[1]} trainees/ 1 group for practical)";
                        if($isVietnamese){
                            $textMaximum = "Maximum: {$maximumArray[0]} học viên học lý thuyết (Maximum {$maximumArray[1]} học viên/ 1 nhóm thực hành)";
                        }
                    }


                    $capacityData = array(
                        $textRecommend,
                        $textMinimum,
                        $textMaximum
                    );
                }


            }

            $listvars['capacity'] = $capacityData;

            $knowledge = $curriculum->getCKnowledge();
            if ($knowledge == '') {
                $knowledge = 'N/A';
            }

            $skill = $curriculum->getCSkill();
            if ($skill == '') {
                $skill = 'N/A';
            }

            $experience = $curriculum->getCExperience();
            if ($experience == '') {
                $experience = 'N/A';
            }

            $docwise = 'N/A';
            $docwiseRequire = $curriculum->getCDocwise();
            if ($docwiseRequire) {
                $docwise = 'Holding a valid DOCWISE Certificate';

            }
            if ($curriculum->getCDocwiseCustom() != '') {
                $docwise = $curriculum->getCDocwiseCustom();
            }

            $prerequisitesData = array(
                'Knowledge: ' . $knowledge,
                'Skill: ' . $skill,
                'Experience: ' . $experience,
                'English: ' . $docwise
            );


            $listvars['prerequisites'] = $prerequisitesData;


            //get Manual revision
            $addRev = '';
            $manualRev = $curriculum->getAttributeText('c_manual_rev');
            if($manualRev != ''){
                $addRev .= ', rev: '.$manualRev;
            }

            if($curriculum->getCManualDate() != ''){
                $addRev .= ', date: '.Mage::getModel('core/date')->date("d/m/Y", $curriculum->getCManualDate());
            }


            //Get Worksheet info
            $wsInfo = '';
            $docInfo = $curriculum->getCName() . ' Training Manual '.$addRev;
            if($curriculum->getCVietnamese()){
                $docInfo = 'Giáo trình đào tạo '.$curriculum->getCName().$addRev;
            }
            $ws = Mage::getModel('bs_worksheet/worksheet')->getCollection()->addCurriculumFilter($curriculum);
            if (count($ws)) {
                $worksheet = $ws->getFirstItem();
                $wsName = $worksheet->getWsName();
                $wsCode = $worksheet->getWsCode();

                $wsInfo = "Training Worksheet: \"{$wsName}\", code \"{$wsCode}/YYZZ\"";
                if($isVietnamese){
                    $wsInfo = "Phiếu đào tạo thực hành: \"{$wsName}\", code \"{$wsCode}/YYZZ\"";
                }
                $docInfo = $wsInfo;


            }


            $qualificationWs = '';
            $isTaskTrainingCourse = $curriculum->getCTasktrainingCourse();
            if ($isTaskTrainingCourse) {
                $docInfo = '';
                if ($wsInfo != '') {
                    $qualificationWs = 'Complete the ' . $wsInfo . ', and';
                    if($isVietnamese){
                        $qualificationWs = 'Hoàn thành ' . $wsInfo . ', và';
                    }
                }

            }

            $toolInfo = 'A/R';
            if($isVietnamese){
                $toolInfo = 'Theo yêu cầu khoá học';
            }
            $tool = $curriculum->getCTool();
            if ($tool != '') {
                $toolInfo = $tool;
            }
            $materialsData = array(
                'Document handout: ' . $docInfo,
                'Tool/equipment: ' . $toolInfo
            );


            $listvars['materials'] = $materialsData;


            $examType = 'Exam type: ';
            $passMark = 'Pass mark: ';
            $passQualification = '';

            $examTypes = $curriculum->getAttributeText('c_exam_type');
            if ($examTypes != '') {
                $examType .= $examTypes;

                $examTypes = explode(",", $examTypes);
                $count = count($examTypes);

                foreach ($examTypes as $type) {
                    if (strpos(strtolower('mk' . $type), "multiple")) {

                        if($isVietnamese){
                            $passQualification .= 'Đạt phần thi trắc nghiệm';
                            $passMark .= '75% phần trắc nghiệm, ';
                        }else {
                            $passQualification .= 'Passed all exams';
                            $passMark .= '75% for Multiple Choice, ';
                        }

                    }
                    if (strpos(strtolower('mk' . $type), "practical")) {
                        $add = '';
                        if ($count <= 1) {
                            if($isVietnamese){
                                $add .= 'Đạt phần đánh giá thực hành';
                            }else {
                                $add .= 'Passed practical assessment';
                            }
                        } else {
                            if($isVietnamese){
                                $add .= '/ đánh giá thực hành';
                            }else {
                                $add .= '/ practical assessment';
                            }
                        }
                        $passQualification .= $add;
                        if($isVietnamese){
                            $passMark .= "Đạt phần đánh giá thực hành, ";
                        }else {
                            $passMark .= "Passed Practical Assessment, ";
                        }
                    }
                    if (strpos(strtolower('mk' . $type), "essay")) {
                        $add = '';
                        if ($count <= 1) {
                            $add .= 'Passed essay';
                        } else {
                            $add .= '/ essay';
                        }
                        $passQualification .= $add;


                        $passMark .= "Passed Essay Question, ";
                    }
                }

                $passMark = substr($passMark, 0, -2);

            } else {
                $examType .= 'N/A';
                $passMark .= 'N/A';
            }
            if ($passQualification != '') {
                if($isVietnamese){
                    $passQualification .= ', và';
                }else{
                    $passQualification .= ', and';
                }
            }

            $examData = array(
                $examType,
                $passMark
            );

            $listvars['exam'] = $examData;

            if($isVietnamese){
                $qualificationTitle = 'Học viên được công nhận hoàn thành khoá học ';
            }else {
                $qualificationTitle = 'The trainee will be qualified as completed the course ';
            }

            $hasCertCompletion = $curriculum->getCCertCompletion();
            if ($hasCertCompletion) {
                if($isVietnamese){
                    $qualificationTitle .= 'và được nhận chứng chỉ hoàn thành ';
                }else {
                    $qualificationTitle .= 'and received the Certificate of Course Completion ';
                }
            }

            $hasCertRecognition = $curriculum->getCCertRecognition();
            if ($hasCertRecognition) {
                $qualificationTitle .= 'and received the Certificate of Course Recognition ';
            }

            $hasCertAttendance = $curriculum->getCCertAttendance();
            if ($hasCertAttendance) {
                $qualificationTitle .= 'and received the Attestation of Course Attendance ';
            }

            if($isVietnamese){
                $qualificationTitle .= 'nếu';
            }else {
                $qualificationTitle .= 'if';
            }


            if (!$curriculum->getCOnline()) {
                if($isVietnamese){
                    $qualificationData = array(
                        'Tham dự tối thiểu 90% thời lượng của khoá học, và',

                    );
                    $qualificationData[] = 'Hoàn thành nội dung khoá học, và';
                }else {
                    $qualificationData = array(
                        'Attended minimum of 90% course duration, and',

                    );
                    $qualificationData[] = 'Completed the course contents, and';
                }

            } else {
                $onlineShortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_course')->getFirstItem();
                $shortcutContent = explode("\r\n", $onlineShortcut->getDescription());
                if (count($shortcutContent)) {
                    foreach ($shortcutContent as $item) {
                        $qualificationData[] = $item;
                    }

                }

                $onlineNote = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_note')->getFirstItem();
                $note = $onlineNote->getDescription();
            }

            if ($passQualification != '') {
                $qualificationData[] = $passQualification;
            }
            if ($qualificationWs != '') {
                $qualificationData[] = $qualificationWs;
            }

            $isPractical = $curriculum->getCPractical();
            if ($isPractical) {
                $qualificationData[] = 'Per Performed required tasks in related Training Worksheet and is confirmed by Instructors of completion of the those tasks, and';
            }

            if($curriculum->getCExamCustom() != ''){
                $qualificationData[] = $curriculum->getCExamCustom().', and';
            }else {
                if($customer){
                    $qualificationData[] = 'Meet the requirement for course completion of '.$airlineName.', and';
                }
            }




            if($isVietnamese){
                $qualificationData[] = 'Không bị đuổi học';
            }else {
                $qualificationData[] = 'Not expelled from the course';
            }


            $listvars['qualification'] = $qualificationData;
        }

        $location = '';


        $rooms = Mage::getModel('bs_logistics/classroom')->getCollection()->addProductFilter($course->getId());
        if ($rooms->count()) {
            $region = '';
            foreach ($rooms as $r) {
                $location .= $r->getClassroomName() . ', ';
                if($r->getClassroomLocation() == 280){
                    $region = ' - Southern Training Division';
                }
            }

            if (!strpos($location, "website")) {
                $location .= 'VAECO Training Center'.$region;
            }

            //$location = substr($location, 0, -2);


        }

        //Now we go to the most complex thing, schedule
        $tableData = array();
        $scheduleData = array();


        $schedules = Mage::getModel('bs_register/schedule')
            ->getCollection()->addFieldToFilter('course_id', $course->getId())
            ->setOrder('entity_id', 'ASC')->setOrder('schedule_order', 'ASC');

        //$schedules->getSelect()->group(array('instructor_id'));

        $date = '';


        $subjectData = array();
        $totalHour = 0;

        $excludeSubjects = array();

        if ($schedules->count()) {

            $scount = $schedules->count();
            $d = 0;
            $firstDate = '';
            $i=1;
            foreach ($schedules as $sche) {
                $scheduleStartDate = $sche->getScheduleStartDate();
                $scheduleFinishDate = $sche->getScheduleFinishDate();

                $scheduleStartTime = Mage::getSingleton('bs_register/schedule_attribute_source_schedulestarttime')->getOptionText($sche->getScheduleStartTime());
                $scheduleFinishTime = Mage::getSingleton('bs_register/schedule_attribute_source_schedulefinishtime')->getOptionText($sche->getScheduleFinishTime());

                if ($scheduleStartTime != '') {
                    $scheduleStartTime = str_replace(" ", "", $scheduleStartTime);
                    $scheduleStartTime = explode(",", $scheduleStartTime);
                    asort($scheduleStartTime);

                    $finishHour = $scheduleStartTime[count($scheduleStartTime) - 1];
                    $finishHour = preg_replace("/[^0-9]/", "", $finishHour);
                }
                $scheduleStartDate = Mage::getModel('core/date')->date("d/m/Y", $scheduleStartDate);
                $scheduleFinishDate = Mage::getModel('core/date')->date("d/m/Y", $scheduleFinishDate);

                $today = Mage::getModel('core/date')->date("d/m/Y", time());


                if ($scheduleFinishDate == $scheduleStartDate) {
                    $date = $scheduleFinishDate;
                } else {
                    $date = $scheduleStartDate . ' - ' . $scheduleFinishDate;
                }



                $remarks = $sche->getScheduleNote();

                $scheHours = $sche->getScheduleHours();
                $totalHour += $scheHours;


                /*if($sche->getSubjectType()){
                    $base = $sche->getScheduleSubjects();
                    $subs = explode(",", $base);
                }else{
                    $subs = $sche->getSubjectId();
                } */
                $base = $sche->getScheduleSubjects();
                $subs = explode(",", $base);

                /*$subs = $sche->getSubjectId();


                if(!$subs){
                    $subs = $sche->getScheduleSubjects();
                    $subs = explode(",", $subs);
                }

                if(!is_array($subs)){
                    $subs = array($subs);
                }*/
                $bold = 'b';
                if($typtraining){
                    $bold = 'no';
                }




                $instructors = '';
                $additional = '';


                $instructorId = $sche->getInstructorId();
                if($instructorId > 0){
                    $instructor = Mage::getModel('bs_instructor/instructor')->load($instructorId);
                    $iName = $instructor->getIname();
                    $iVaecoId = $instructor->getIvaecoid();
                    $phone = trim($instructor->getIphone());
                    $username = trim($instructor->getIusername());


                    if($username == ''){
                        $username = $instructor->getIemail();
                        if($username != ''){
                            $additional = 'Email: ' . $username;
                        }

                    }else {
                        $additional = 'User: ' . $username;
                    }

                    $instructorData = $iName;
                    if($iVaecoId != ''){
                        $instructorData .= ' (' . $iVaecoId . ')';
                    }

                    if($phone != ''){
                        $additional .= ', Phone: ' . $phone;
                    }


                    $instructors .= $instructorData ;

                    if($username == '' || $username == 'chưa có'){
                        $instructors .= $additional;
                    }
                }


                if ($isOnline) {
                    $remarks = $additional;
                }


                if(is_array($subs) && count($subs) > 1){
                    $k=0;
                    $count = count($subs);
                    foreach ($subs as $sub) {
                        $excludeSubjects[] = $sub;
                        $subject = Mage::getModel('bs_subject/subject')->load($sub);
                        $subName = $subject->getSubjectName();


                        $subLevel = $subject->getSubjectLevel();
                        $subHour = (float)$subject->getSubjectHour();
                        if($subHour > 0){
                            if($subHour < 10){
                                $subHour = '0'.$subHour;
                            }

                            $subName .= ' ('.$subHour.' hours)';
                        }
                        //$totalHour += $subHour;
                        $subContent = $subject->getSubjectContent();
                        //$subRemark = $subject->getSubjectNote();
                        $bolder = true;



                        if ($subject->getSubjectOnlycontent()) {
                            $subName = '';
                        }

                        if ($subContent != '') {
                            $subContent = explode("\r\n", $subContent);
                        }

                        //Now get subject content
                        $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sub)->setOrder('subcon_order', 'ASC')->setOrder('entity_id', 'ASC');

                        $contentCount = count($subjectContents);
                        $count += $contentCount;

                        if ($contentCount) {
                            $subLevel = '';
                        }
                        if($k==0){
                            $merge = 'start';
                        }else {
                            $merge = 'continue';
                        }

                        if ($contentCount) {
                            $subjectData[] = array(
                                'no' => $i,
                                'date' => $date,
                                'content' => $subName,
                                'level' => $subLevel,
                                'hour' => ($scheHours > 0) ? $scheHours : '',
                                'instructor' => $instructors,
                                'remark' => $remarks,
                                'list' => $subContent,
                                'bolder'    => $bold,
                                'count'     => $count,
                                'parent' => true,
                                'hascount' => $count,
                                'merge'     => $merge

                            );
                        } else {

                            $subjectData[] = array(
                                'no' => $i,
                                'date' => $date,
                                'content' => $subName,
                                'level' => $subLevel,
                                'hour' => ($scheHours > 0) ? $scheHours : '',
                                'instructor' => $instructors,
                                'remark' => $remarks,
                                'list' => $subContent,
                                'bolder'    => $bold,
                                'parent' => true,
                                'merge'     => $merge

                            );
                        }


                        if ($contentCount) {
                            $j = 1;
                            $totalSubHours = 0;
                            foreach ($subjectContents as $subcon) {
                                $totalSubHours += (float)$subcon->getSubconHour();
                            }
                            $ignore = false;
                            if($scheHours < $totalSubHours){
                                $ignore = true;
                            }
                            foreach ($subjectContents as $subcon) {

                                $subconContent = $subcon->getSubconContent();
                                if ($subconContent != '') {
                                    $subconContent = explode("\r\n", $subconContent);
                                }

                                $hour = (float)$subcon->getSubconHour() > 0 ? (float)$subcon->getSubconHour() : 'TBD';
                                if($ignore){
                                    $hour = 'TBD';
                                }

                                if ($j == 1) {
                                    $subjectData[] = array(
                                        'no' => $i . '.' . $j,
                                        'date' => '',
                                        'content' => $subcon->getSubconTitle(),
                                        'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                        'hour' => $hour,
                                        'instructor' => '',
                                        //'count' => $count,
                                        'list' => $subconContent,
                                        'merge'     => 'continue'
                                        //'bolder'    => false
                                    );
                                } else {
                                    $subjectData[] = array(
                                        'no' => $i . '.' . $j,
                                        'content' => $subcon->getSubconTitle(),
                                        'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                        'hour' => $hour,

                                        'list' => $subconContent,
                                        'merge'     => 'continue'
                                        //'bolder'    => false
                                    );
                                }


                                $j++;
                            }

                        }
                        $i++;
                        $k++;
                    }
                }else {
                    $subs = $subs[0];
                    $excludeSubjects[] = $subs;
                    $subject = Mage::getModel('bs_subject/subject')->load($subs);
                    $subName = $subject->getSubjectName();
                    if ($subject->getSubjectOnlycontent()) {
                        $subName = '';
                    }

                    $subLevel = $subject->getSubjectLevel();
                    $subHour = (float)$subject->getSubjectHour();
                    //$totalHour += $subHour;
                    $subContent = $subject->getSubjectContent();
                    //$subRemark = $subject->getSubjectNote();
                    $bolder = true;

                    if ($subContent != '') {
                        $subContent = explode("\r\n", $subContent);
                    }

                    //Now get subject content
                    $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $subs)->setOrder('subcon_order', 'ASC')->setOrder('entity_id', 'ASC');

                    $count = count($subjectContents);

                    if ($count) {
                        $subLevel = '';
                    }

                    if ($count) {
                        $subjectData[] = array(
                            'no' => $i,
                            'date' => $date,
                            'content' => $subName,
                            'level' => $subLevel,
                            'hour' => ($scheHours > 0) ? $scheHours : '',
                            'instructor' => $instructors,
                            'remark' => $remarks,
                            'list' => $subContent,
                            'bolder'    => $bold,
                            'parent' => true,
                            'hascount' => $count,
                            'merge'     => 'start'

                        );
                    } else {
                        $subjectData[] = array(
                            'no' => $i,
                            'date' => $date,
                            'content' => $subName,
                            'level' => $subLevel,
                            'hour' => ($scheHours > 0) ? $scheHours : '',
                            'instructor' => $instructors,
                            'remark' => $remarks,
                            'list' => $subContent,
                            'bolder'    => $bold,
                            'parent' => true,
                            'merge'     => 'start'

                        );
                    }


                    if ($count) {
                        $j = 1;
                        $totalSubHours = 0;
                        foreach ($subjectContents as $subcon) {
                            $totalSubHours += (float)$subcon->getSubconHour();
                        }
                        $ignore = false;
                        if($scheHours < $totalSubHours){
                            $ignore = true;
                        }
                        foreach ($subjectContents as $subcon) {

                            $subconContent = $subcon->getSubconContent();
                            if ($subconContent != '') {
                                $subconContent = explode("\r\n", $subconContent);
                            }

                            $hour = (float)$subcon->getSubconHour() > 0 ? (float)$subcon->getSubconHour() : 'TBD';
                            if($ignore){
                                $hour = 'TBD';
                            }

                            if ($j == 1) {
                                $subjectData[] = array(
                                    'no' => $i . '.' . $j,
                                    'date' => $date,
                                    'content' => $subcon->getSubconTitle(),
                                    'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                    'hour' => $hour,
                                    'instructor' => $instructors,
                                    'count' => $count,
                                    'list' => $subconContent,
                                    'merge'     => 'continue'
                                    //'bolder'    => false
                                );
                            } else {
                                $subjectData[] = array(
                                    'no' => $i . '.' . $j,
                                    'content' => $subcon->getSubconTitle(),
                                    'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                    'hour' => $hour,

                                    'list' => $subconContent,
                                    'merge'     => 'continue'
                                    //'bolder'    => false
                                );
                            }


                            $j++;
                        }

                    }
                    $i++;
                }






                //$remark = $sche->getScheduleNote();
                //$remarks .= $remark . ' ';

                $d++;


            }

        }
        
        
        //here for cost stub


        //Now make sure that we will list all other subjects that didn't be included in schedule


        $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId());

        if(count($excludeSubjects)){
            $subjects->addFieldToFilter('entity_id', array('nin'=>$excludeSubjects));
        }

        $subjects->setOrder('entity_id', 'ASC')->setOrder('subject_order', 'ASC');

        if (count($subjects)) {

            foreach ($subjects as $sub) {
                $subject = Mage::getModel('bs_subject/subject')->load($sub->getId());
                $subName = $subject->getSubjectName();
                if ($subject->getSubjectOnlycontent()) {
                    $subName = '';
                }
                $subLevel = $subject->getSubjectLevel();
                $subHour = (float)$subject->getSubjectHour();
                $totalHour += $subHour;
                $subContent = $subject->getSubjectContent();
                $subRemark = $subject->getSubjectNote();
                $bolder = true;

                if ($subContent != '') {
                    $subContent = explode("\r\n", $subContent);
                }

                //Now get subject content
                $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sub->getId())->setOrder('entity_id', 'ASC');

                $count1 = count($subjectContents);

                if ($count1) {
                    $subLevel = '';
                }


                if ($count1) {
                    $subjectData[] = array(
                        'no' => $i,
                        'date' => '',
                        'content' => $subName,
                        'level' => $subLevel,
                        'hour' => ($subHour > 0) ? $subHour : '',
                        'instructor' => '',
                        'remark' => $remarks,
                        'list' => $subContent,
                        'bolder'    => $bold,
                        'parent' => true,
                        'hascount' => $count

                    );
                } else {
                    $subjectData[] = array(
                        'no' => $i,
                        'date' => '',
                        'content' => $subName,
                        'level' => $subLevel,
                        'hour' => ($subHour > 0) ? $subHour : '',
                        'instructor' => '',
                        'remark' => $remarks,
                        'list' => $subContent,
                        'bolder'    => $bold,
                        'parent' => true,

                    );
                }


                if ($count1) {
                    $j = 1;
                    foreach ($subjectContents as $subcon) {

                        $subconContent = $subcon->getSubconContent();
                        if ($subconContent != '') {
                            $subconContent = explode("\r\n", $subconContent);
                        }

                        if ($j == 1) {
                            $subjectData[] = array(
                                'no' => $i . '.' . $j,
                                'date' => '',
                                'content' => $subcon->getSubconTitle(),
                                'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                'hour' => (float)$subcon->getSubconHour() > 0 ? (float)$subcon->getSubconHour() : 'TBD',
                                'instructor' => '',
                                'count' => $count1,
                                'list' => $subconContent,
                                'remark' => $subcon->getSubconNote(),
                                //'bolder'    => false
                            );
                        } else {
                            $subjectData[] = array(
                                'no' => $i . '.' . $j,
                                'content' => $subcon->getSubconTitle(),
                                'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                                'hour' => (float)$subcon->getSubconHour() > 0 ? (float)$subcon->getSubconHour() : 'TBD',

                                'list' => $subconContent,
                                'remark' => $subcon->getSubconNote(),
                                //'bolder'    => false
                            );
                        }


                        $j++;
                    }

                }




                $i++;
            }



        }

        if ($curriculum->getId() && count($subjects)) {
            if($curriculum->getCReexam()){
                $subjectData[] = array(
                    'no' => $i,
                    'date' => '',
                    'content' => 'Re-exam/Assessment (if any)',
                    'level' => '',
                    'hour' => 'TBD',
                    'instructor' => '',
                    'remark' => '',
                    'parent' => true,
                    //'bolder'    => true
                );
            }



        }
        $subjectData[] = array(
            'no' => '',
            'date' => '',
            'content' => 'Total',
            'level' => '',
            'hour' => $totalHour,
            'instructor' => '',
            'remark' => '',
            'parent' => true,
            'bolder' => 'b'
        );


        if($totalHour < 10){
            $totalHour = '0'.$totalHour;
        }

        if($isVietnamese){
            $trainingTime = 'Buổi sáng từ 7h30 đến 12h00; Buổi chiều, từ 13h00 đến 16h30';
        }else {
            $trainingTime = 'Morning, from 7h30 to 12h00; Afternoon, from 13h00 to 16h30';
        }

        $tnDay = $course->getTrainingDay();
        $tnTime = $course->getTrainingTime();

        if($tnDay != ''){
            $duration = $tnDay;
        }
        if($tnTime != ''){
            $trainingTime = $tnTime;
        }
        if($customer){


        }

        if($isVietnamese){
            $durationData = array(
                'Training days: ' . $duration,
                'Training hours: ' . $totalHour.' giờ',
                'Training time: '. $trainingTime
            );
        }else {
            $durationData = array(
                'Training days: ' . $duration,
                'Training hours: ' . $totalHour.' hours',
                'Training time: '. $trainingTime
            );
        }


        if ($curriculum->getCOnline()) {
            $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_duration')->getFirstItem();
            if ($shortcut->getId()) {
                $customDuration = $shortcut->getDescription();
            }


            $durationData = array(
                'Training days/hours: ' . $duration,
                'Training time: TBD'
            );
        }

        $listvars['duration'] = $durationData;





        $tableHtml = null;
        if (count($subjectData)) {
            $tableHtml = array(
                'isarray' => true,
                'array' => array(
                    'schedule'  => Mage::helper('bs_traininglist/wordxml')->prepareSevenTable($subjectData)
                ),

            );



        }else {
            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8007-A');
        }


        $instructorCost = $totalHour * 65000;
        if ($isApprovedCourse) {
            $instructorCost = $totalHour * 90000;
        }

        $courseCost = $course->getPrice();

        $totalCost = $instructorCost + $courseCost;

        $costData = array();

        $instructorCost = number_format($instructorCost, 0, '', '.');
        if($customer){
            $instructorCost = 'N/A';
        }
        /*$costData[] = array(
            'cost_desc' => 'Instructors cost',
            'cost' => $instructorCost,
            'cost_remark' => ''
        );*/
        if($courseCost > 0){
            $courseCost = number_format($courseCost, 0, '', '.');
        }else {
            $courseCost = 'N/A';
        }

        if($customer){
            $courseCost = '';
        }


        $place = $course->getConductingPlace();
        $costRemark = '';
        if($place == 208){
            $costRemark = 'Receive at HCM Branch';
        }elseif($place == 207){
            //$costRemark = 'Receive at DAD Branch';
        }elseif($place == 206){
            //$costRemark = 'Receive at HCM Branch';
        }

        //generate cost stub
        $result = Mage::helper('bs_coursecost')->generateCost($course);


        /*$costData[] = array(
            'cost_desc' => 'Course Organization cost',
            'cost' => $courseCost,
            'cost_remark' => $costRemark
        );

        if ($course->getOtherPrice() > 0) {
            $totalCost += $course->getOtherPrice();
            $costData[] = array(
                'cost_desc' => 'Tool & consumable material cost',
                'cost' => number_format($course->getOtherPrice(), 0, '', '.'),
                'cost_remark' => ''
            );
        }*/

        $tableData[] = $result['cost_data'];



        $totalCost = number_format($totalCost, 0, '', '.');
        if($customer){
            $totalCost = '';
        }


        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedDate = $today;
        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());


        if ($course->getAttributeText('location') != '') {
            $location = $course->getAttributeText('location');
        }


        $standbyInstructor = $course->getStandbyInstructor();
        $standbyRoster = $course->getStandbyRoster();

        $standby = '';
        $standbyContent = '';
        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'standby_content')->getFirstItem();
        if($shortcut->getId() && $standbyRoster){

            $standbyContent = $shortcut->getDescription();
        }
        $standbyNote = '';
        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'standby_note')->getFirstItem();
        if($shortcut->getId()){
            $standbyNote = $shortcut->getDescription();
        }

        if($standbyInstructor == '' && !$standbyRoster){
            $standby = 'N/A';
            $standbyContent = '';
            $standbyNote = '';
            $tableHtml['array']['standby_content'] = '';
        }else {
            $tableHtml['array']['standby_content'] = Mage::helper('bs_traininglist/wordxml')->prepareStandby($course);
        }

        $tableHtml['array']['cost_note'] = $result['note'];


        $description = $curriculum->getCDescription();
        $description = explode("\r\n", $description);

        $listvars['description'] = $description;

        $objectiveTitle = 'Upon completion of the course, the trainee will be able to';
        if($isVietnamese){
            $objectiveTitle = 'Sau khi hoàn thành khoá đào tạo, học viên nắm được';
        }
        $data = array(
            'title' => $course->getName(),
            'code' => $course->getSku(),
            //'description' => $curriculum->getCDescription(),
            'qualification_title' => $qualificationTitle,
            'objectives_title'      => $objectiveTitle,
            'location' => $location,
            'total_hours' => $totalHour,
            'others' => $others,
            'cost_total' => $result['total'],
            'prepared_date' => $preparedDate,
            'prepared_by' => $preparedBy,
            'note' => $note,
            'standby' => $standby,
            //'standby_content' => $standbyContent,
            'standby_note'  => $standbyNote,
            'year'  => $result['year']


        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $tableData, $checkboxes, $listvars, null, null, null, $tableHtml);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );
            



        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    

    public function generateEightAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateEight($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateEightAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateEight($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateEight($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8008');

        $name = $course->getSku() . '_8008_TRAINING ATTENDANCE RECORD';




        try {

            $data = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);
            $traineeData = Mage::helper('bs_traininglist/course')->getCourseTraineeInfo($course);

            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $traineeData);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );



        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateNineAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getModel('bs_exam/exam')->load($id);
            $this->generateNine($exam);
        }
        $this->_redirect(
            '*/exam_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateNineAction()
    {

        $exams = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_exam/exam')
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
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/exam_exam/', array('store' => $storeId));
    }

    public function generateNine($exam)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8009');

        $subjectIds = $exam->getSubjectIds();
        $subjectIds = explode(",", $subjectIds);

        $course = Mage::getModel('catalog/product')->load($exam->getCourseId());

        $name = $course->getSku() . '_8009_MINUTES OF EXAM';




        $content = $exam->getExamContent();

        $examiners = $exam->getExaminers();
        $firstexaminer = '';
        $secondexaminer = '';
        if ($examiners != '') {
            $examiners = explode(",", $examiners);
            if (count($examiners) > 1) {
                $first = Mage::getModel('customer/customer')->load($examiners[0]);
                $firstexaminer = $first->getName();

                $second = Mage::getModel('customer/customer')->load($examiners[1]);
                $secondexaminer = $second->getName();
            } else {
                $first = Mage::getModel('customer/customer')->load($examiners[0]);
                $firstexaminer = $first->getName();
            }

        }

        $checkboxes = array();
        $traineeIds = array();
        if ($exam->getExamTimes()) {
            $name .= ' -  RE-TAKING';
            $checkboxes['retakingexam'] = 1;
            $examresults = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('subject_id', array('in' => $subjectIds))->addFieldToFilter('first_mark', array('lt' => 75));



            $countER = count($examresults);

            if ($countER) {
                foreach ($examresults as $exre) {
                    $traineeIds[$exre->getTraineeId()] = 1;
                }

            }
            $traineeIds = array_keys($traineeIds);
        } else {
            $checkboxes['firstexam'] = 1;
        }


        $date = Mage::getModel('core/date')->date("d/m/Y", $exam->getExamDate());
        $time = (int)$exam->getExamDuration();


        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);

        $data = array(
            'date' => $date,
            'time' => $time,
            'content' => $content,
            'firstexaminer' => $firstexaminer,
            'secondexaminer' => $secondexaminer,


        );

        $data = array_merge($data, $basicInfo);

        $traineeData = Mage::helper('bs_traininglist/course')->getCourseTraineeInfo($course, $traineeIds);



        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $traineeData, $checkboxes);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateNineCourseAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getModel('catalog/product')->load($id);
            $this->generateNineCourse($exam);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateNineCourseAction()
    {

        $exams = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($examId);

                $this->generateNineCourse($exam);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateNineCourse($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8009');

        $name = $course->getSku() . '_8009_MINUTES OF EXAM';




        $content = '';


        $checkboxes = array();
        $traineeIds = array();

        $checkboxes['firstexam'] = 1;



        $date = Mage::getModel('core/date')->date("d/m/Y", now());
        $time = '';


        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);



        $data = array(
            'date' => $date,
            'time' => $time,
            'content' => $content,
            'firstexaminer' => '',
            'secondexaminer' => '',


        );

        $data = array_merge($data, $basicInfo);


        $traineeData = Mage::helper('bs_traininglist/course')->getCourseTraineeInfo($course, $traineeIds);


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $traineeData, $checkboxes);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateTenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getModel('bs_exam/exam')->load($id);
            $this->generateTen($exam);
        }
        $this->_redirect(
            '*/exam_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateTenAction()
    {

        $exams = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_exam/exam')
                    //->setStoreId($storeId)
                    ->load($examId);

                $this->generateTen($exam);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/exam_exam/', array('store' => $storeId));
    }

    public function generateTen($exam)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8010');

        $course = Mage::getModel('catalog/product')->load($exam->getCourseId());


        $content = $exam->getExamContent();


        $checkboxes = array();
        if ($exam->getExamTimes()) {
            $checkboxes['retakingexam'] = 1;
        } else {
            $checkboxes['firstexam'] = 1;
        }

        $name = $course->getSku() . '_8010_MULTIPLE CHOICE QUESTIONNAIRE';
        $date = Mage::getModel('core/date')->date("d/m/Y", $exam->getExamDate());
        $time = (int)$exam->getExamDuration();
        $qty = $exam->getExamQty();

        $listvars['answer'] = array(
            '1st answer',
            '2nd answer',
            '3rd answer'
        );



        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());

        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);

        $data = array(
            'date' => $date,
            'time' => $time,
            'content' => $content,
            'qty' => $qty,
            'preparedby' => $preparedBy,
            'chapter' => 'Example',
            'question' => 'This is an example QUESTION'


        );

        $data = array_merge($data, $basicInfo);


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null, $checkboxes, $listvars);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateElevenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $exam = Mage::getModel('bs_exam/exam')->load($id);
            $this->generateEleven($exam);
        }
        $this->_redirect(
            '*/exam_exam/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateElevenAction()
    {

        $exams = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($exams as $examId) {
                $exam = Mage::getSingleton('bs_exam/exam')
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
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/exam_exam/', array('store' => $storeId));
    }

    public function generateEleven($exam)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8011');

        $course = Mage::getModel('catalog/product')->load($exam->getCourseId());

        $content = $exam->getExamContent();




        $checkboxes = array();
        if ($exam->getExamTimes()) {
            $checkboxes['retakingexam'] = 1;
        } else {
            $checkboxes['firstexam'] = 1;
        }

        $name = $course->getSku() . '_8011_MULTIPLE CHOICE ANSWER SHEET';
        $date = Mage::getModel('core/date')->date("d/m/Y", $exam->getExamDate());
        $time = (int)$exam->getExamDuration();
        $qty = $exam->getExamQty();


        $examiners = $exam->getExaminers();
        $examiner = '';
        if ($examiners != '') {
            $examiners = explode(",", $examiners);
            $first = Mage::getModel('customer/customer')->load($examiners[0]);
            $examiner = $first->getName();
        }

        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);


        $data = array(
            'date' => $date,
            'time' => $time,
            'content' => $content,
            'qty' => $qty,
            'examiner' => $examiner,


        );

        $data = array_merge($data, $basicInfo);



        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null, $checkboxes);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateTwelveAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateTwelve($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateTwelveAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateTwelve($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateTwelve($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8012');

        $sku = $course->getSku();
        $check = explode("-",$sku);
        if($check[0] == 'GE'){
            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8012-GE');
        }
        $name = $sku . '_8012_EXAM QUESTIONNAIRE-PRACTICAL ASSESSMENT';


        $today = Mage::getModel('core/date')->date("d/m/Y", time());


        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());

        $data = array(
            'title' => $course->getName(),
            'code' => $course->getSku(),
            'date' => $today,
            'content' => '',
            'prepared_by' => $preparedBy

        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateThirteenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateThirteen($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateThirteenAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateThirteen($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateThirteen($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8013');
        $sku = $course->getSku();
        $check = explode("-",$sku);
        if($check[0] == 'GE'){
            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8013-GE');
        }



        $name = $course->getSku() . '_8013_EXAM RESULT';



        $today = Mage::getModel('core/date')->date("d/m/Y", time());


        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());


        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);


        $data = array(
            'date' => $today,
            'preparedby' => $preparedBy

        );

        $data = array_merge($data, $basicInfo);



        $checkboxes = array();
        $checkboxes['firstexam'] = 1;


        $tableHtml = array(
            'type' => 'replace',
            'content' => Mage::helper('bs_traininglist/wordxml')->prepareThirteenTable($course),
            'variable' => 'html'
        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null, $checkboxes, null, null, null, null, $tableHtml);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateThirteenReAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateThirteenRe($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateThirteenRe($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8013');
        $sku = $course->getSku();
        $check = explode("-",$sku);
        if($check[0] == 'GE'){
            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8013-GE');
        }

        $name = $course->getSku() . '_8013_RE-EXAM RESULT';



        $today = Mage::getModel('core/date')->date("d/m/Y", time());


        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());

        $basicInfo = Mage::helper('bs_traininglist/course')->getCourseGeneralInfo($course);


        $data = array(
            'date' => $today,
            'preparedby' => $preparedBy

        );

        $data = array_merge($data, $basicInfo);



        $checkboxes = array();
        $checkboxes['retakingexam'] = 1;


        $tableHtml = array(
            'type' => 'replace',
            'content' => Mage::helper('bs_traininglist/wordxml')->prepareThirteenReTable($course),
            'variable' => 'html'
        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null, $checkboxes, null, null, null, null, $tableHtml);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateFourteenAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateFourteen($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateFourteenAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateFourteen($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateFourteen($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8014');

        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($course->getId());

        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculums->getFirstItem()->getId());

        $certComplete = $curriculum->getCCertCompletion();
        $certRecognize = $curriculum->getCCertRecognition();
        $certAttendance = $curriculum->getCCertAttendance();

        $isOnline = $curriculum->getCOnline();

        $totalHours = 0;

        //get total hours by schedule info, is this an accurate way?
        $schedules = Mage::getModel('bs_register/schedule')->getCollection()->addFieldToFilter('course_id', $course->getId());
        if (count($schedules)) {
            foreach ($schedules as $sch) {
                $totalHours += (float)$sch->getScheduleHours();
            }

        }

        $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->addFieldToFilter('status', 1);

        $totalTrainingSubjects = $subjects->count();

        $sujectExams = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->addFieldToFilter('require_exam', 1);//->addFieldToFilter('subject_exam', 1)
        $totalExamSubjects = $sujectExams->count();

        $name = $course->getSku() . '_8014_TRAINING COURSE REPORT';

        $location = 'TC-VAECO';
        $conductingPlace = $course->getAttributeText('conducting_place');
        if (trim($conductingPlace) == 'HCM') {
            $location .= ' (STD)';
        } elseif (trim($conductingPlace) == 'DAD') {
            $location = 'DAD Branch';
        }


        $deptInfo = 'Department';

        $dI = $course->getDeptInfo();
        if ($dI == 334) {
            $deptInfo = 'Company';
        } elseif ($dI == 335) {
            //$deptInfo = 'Place of Birth';
        }

        $startDate = $course->getCourseStartDate();
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

        $finishDate = $course->getCourseFinishDate();
        $checkDate = $finishDate;
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

        if ($finishDate != $startDate) {
            $duration = 'From ' . $startDate . ' to ' . $finishDate;
        } else {
            $duration = $startDate;
        }


        $passQualification = '';

        $examTypes = $curriculum->getAttributeText('c_exam_type');
        if ($examTypes != '') {


            $examTypes = explode(",", $examTypes);
            $count = count($examTypes);

            foreach ($examTypes as $type) {
                if (strpos(strtolower('mk' . $type), "multiple")) {
                    $passQualification .= 'Passed all exams';
                }
                if (strpos(strtolower('mk' . $type), "practical")) {
                    $add = '';
                    if ($count <= 1) {
                        $add .= 'Passed practical assessment';
                    } else {
                        $add .= '/ practical assessment';
                    }
                    $passQualification .= $add;
                }
                if (strpos(strtolower('mk' . $type), "essay")) {
                    $add = '';
                    if ($count <= 1) {
                        $add .= 'Passed essay';
                    } else {
                        $add .= '/ essay';
                    }
                    $passQualification .= $add;


                }
            }


        }
        if ($passQualification != '') {
            $passQualification .= ', and';
        }


        $currentUser = Mage::getSingleton('admin/session')->getUser();


        $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname() . ' ' . $currentUser->getLastname());

        $totalHours = $totalHours . ' hrs';
        if ($isOnline) {
            $totalHours = 'Online';
            $location = 'VAECO WEB';
        }

        $qualificationTitle = 'The trainee will be qualified as completed the course ';

        $hasCertCompletion = $curriculum->getCCertCompletion();
        if ($hasCertCompletion) {
            $qualificationTitle .= 'and received the Certificate of Course Completion ';
        }

        $hasCertRecognition = $curriculum->getCCertRecognition();
        if ($hasCertRecognition) {
            $qualificationTitle .= 'and received the Certificate of Course Recognition ';
        }

        $hasCertAttendance = $curriculum->getCCertAttendance();
        if ($hasCertAttendance) {
            $qualificationTitle .= 'and received the Attestation of Course Attendance ';
        }

        $qualificationTitle .= 'if';

        if (!$curriculum->getCOnline()) {
            $qualificationData = array(
                'Attended minimum of 90% course duration, and',

            );
            $qualificationData[] = 'Completed the course contents, and';
        } else {
            $onlineShortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_course')->getFirstItem();
            $shortcutContent = explode("\r\n", $onlineShortcut->getDescription());
            if (count($shortcutContent)) {
                foreach ($shortcutContent as $item) {
                    $qualificationData[] = $item;
                }

            }
        }

        if ($passQualification != '') {
            $qualificationData[] = $passQualification;
        }


        $isPractical = $curriculum->getCPractical();
        if ($isPractical) {
            $qualificationData[] = 'Per Performed required tasks in related Training Worksheet and is confirmed by Instructors of completion of the those tasks, and';
        }


        $qualificationData[] = 'Not expelled from the course';


        $listvars['qualification'] = $qualificationData;


        if ($totalTrainingSubjects < 10) {
            $totalTrainingSubjects = '0' . $totalTrainingSubjects;
        }
        if ($totalExamSubjects < 10) {
            $totalExamSubjects = '0' . $totalExamSubjects;
        }
        if ($course->getAttributeText('location') != '') {
            $location = $course->getAttributeText('location');
        }

        $data = array(
            'title' => $course->getName(),
            'code' => $course->getSku(),
            'duration' => $duration,
            'location' => $location,
            'tchaps' => $totalTrainingSubjects,
            'echaps' => $totalExamSubjects,
            'hours' => $totalHours,
            'qualification_title' => $qualificationTitle,
            'preparedby' => $preparedBy,
            'dept_info' => $deptInfo,
            'total_trainees'    => '0'

        );

        $sku = $course->getSku();
        $check = explode("-",$sku);
        $ge = false;
        $recurrent = false;
        if($check[0] == 'GE'){
            $ge = true;
            if($check[count($check)-1] == 'R'){
                $recurrent = true;
            }
        }

        $reportNo = '';
        $reportDispatchNo = trim($course->getReportDispatchNo());
        $reportDispatchSuf = $course->getAttributeText('report_dispatch_suffix');
        $reportDispatchDate = $course->getReportDispatchDate();
        $day = Mage::getModel('core/date')->date("d", $reportDispatchDate);
        $month = Mage::getModel('core/date')->date("m", $reportDispatchDate);
        $year = Mage::getModel('core/date')->date("Y", $reportDispatchDate);

        if($reportDispatchNo != '' && $reportDispatchSuf != ''){
            $reportNo = $reportDispatchNo.'/'.$reportDispatchSuf;
        }


        $certData = array();


        $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course)->setOrder('position', 'ASC');

        $traineeData = array();
        $tableData = null;
        if (count($trainees)) {
            $data['total_trainees'] = count($trainees);

            $i = 1;
            foreach ($trainees as $_trainee) {

                $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());
                $code = $trainee->getTraineeCode();
                $dept = $trainee->getTraineeDept();

                $dob = $trainee->getTraineeDob();

                $pob = trim($trainee->getTraineePob());

                $validDriver = false;
                $driverStatus = $trainee->getDriverLicense();
                $driverExpire = $trainee->getDriverLicenseExpire();
                if($driverStatus && $driverExpire > $checkDate){
                    $validDriver = true;
                }



                if ($dI == 334) {
                    $dept = $trainee->getTraineeCompany();
                } elseif ($dI == 335) {
                    //$dept = $trainee->getTraineePob();
                }


                $attendanceStatus = '';
                $examPass = 0;
                $examFail = 0;
                $disciplineStatus = 'No';
                $courseResult = '';
                $certNo = '';
                $remark = '';


                if ($dept == '') {
                    $dept = 'TC';
                }


                $vaecoId = $trainee->getVaecoId();
                if ($vaecoId != '') {
                    $code = $vaecoId;
                    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
                    if ($staff->getId()) {

                        $customer = Mage::getModel('customer/customer')->load($staff->getId());

                        $departmentId = $customer->getGroupId();
                        $department = Mage::getModel('customer/group')->load($departmentId);

                        if ($department) {
                            $dept = $department->getCode();
                        }

                        if(!$dob){
                            $dob = $customer->getDob();
                        }

                        if($pob == ''){
                            $pob = $customer->getPob();
                        }
                        if ($dI == 335) {
                            //$dept = $customer->getPob();
                        }
                    }
                }

                if($dob){
                    $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                }
                if(strpos($pob, ',')){
                    $pobArray = explode(",", $pob);
                    $pob = $pobArray[count($pobArray)-1];
                }elseif(strpos($pob, '-')){
                    $pobArray = explode("-", $pob);
                    $pob = $pobArray[count($pobArray)-1];
                }

                //get Attendance status
                $attendances = Mage::getModel('bs_register/attendance')->getCollection()->addFieldToFilter('trainee_id', $_trainee->getId())->addFieldToFilter('course_id', $course->getId());
                $attendExcuse = 0;
                $attendNotExcuse = 0;
                if ($attendances->count()) {
                    foreach ($attendances as $att) {
                        $attStartDate = $att->getAttStartDate();
                        $attFinishDate = $att->getAttFinishDate();

                        $datetime1 = new DateTime($attStartDate);
                        $datetime2 = new DateTime($attFinishDate);
                        $interval = $datetime1->diff($datetime2);

                        $days = $interval->days;
                        $days += 1;

                        $hours = $days * 8;

                        $attStartTime = $att->getAttStartTime();
                        if ($attStartTime == 2) {
                            $hours -= 4;
                        }
                        $attFinishTime = $att->getAttFinishTime();
                        if ($attFinishTime == 1) {
                            $hours -= 4;
                        }
                        if ($att->getAttExcuse()) {
                            $attendExcuse += $hours;
                        } else {
                            $attendNotExcuse += $hours;
                        }
                    }

                }

                $totalOffHours = $attendExcuse + $attendNotExcuse;
                $totalOffHoursDiff = $totalHours - $totalOffHours;
                if($totalOffHoursDiff < 0){
                    $totalOffHoursDiff = 0;
                }
                $attendanceStatus = $totalOffHoursDiff . '/' . $totalHours;

                //get exam result
                $examresults = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('trainee_id', $trainee->getId());

                $totalMark = 0;
                $grade = '';
                if ($examresults->count()) {
                    foreach ($examresults as $exre) {
                        $firstMark = (float)$exre->getFirstMark();
                        $secondMark = (float)$exre->getSecondMark();
                        $thirdMark = (float)$exre->getThirdMark();

                        $finalMark = max($firstMark, $secondMark, $thirdMark);
                        $totalMark += $finalMark;
                        if ($finalMark >= 75) {
                            $examPass += 1;
                        } else {
                            $examFail += 1;
                        }


                    }

                    $everage = $totalMark / $examresults->count();
                    if($everage >= 90){
                        $grade = 'GIỎI';
                    }elseif($everage >= 80){
                        $grade = 'KHÁ';
                    }elseif($everage >= 75 && $everage < 80){
                        $grade = 'TRUNG BÌNH';
                    }

                }


                if ($totalOffHours < 0 || ($totalOffHours / $totalHours) > 0.9 || $examFail > 0) {
                    $courseResult = 'Incompleted';
                    //$certNo = '';
                } else {
                    $courseResult = 'Completed';

                }

                $i++;

                $certNo = Mage::helper('bs_traininglist')->getCertNo($course->getId(),$_trainee->getId());
                if(!$certNo){
                    $certNo = 'N/A';
                }


                if ($examPass < 10) {
                    $examPass = '0' . $examPass;

                }
                if ($examFail < 10) {
                    $examFail = '0' . $examFail;
                }

                if($examFail == 0 && $examPass == 0){
                    $examPass = 'N/A';
                    $examFail = 'N/A';
                }


                if($certNo != ''){
                    $courseName = $course->getName();
                    if(!$validDriver){
                        $courseName = str_replace('Điều khiển,','',$courseName);
                        $courseName = trim($courseName);
                    }
                    $certData[] = array(
                        'name'  => mb_strtoupper($trainee->getTraineeName()),
                        'dob'   => $dob,
                        'pob'   => mb_strtoupper($pob),
                        'course'    => ucfirst($courseName),
                        'from'  => $startDate,
                        'to'    => $finishDate,
                        'grade' => $grade,
                        'day'   => $day,
                        'month' => $month,
                        'year'  => $year,
                        'license'   => $certNo,
                        'report'    => $reportNo
                    );
                }



                if ($isOnline) {
                    $attendanceStatus = 'Read & Sign';
                }

                $dept = str_replace("\"", "", $dept);

                $traineeData[] = array(
                    'name' => $trainee->getTraineeName(),
                    'id' => $code,
                    'department' => $dept,
                    'att_status' => $attendanceStatus,
                    'pass' => $examPass,
                    'fail' => $examFail,
                    'dis_status' => $disciplineStatus,
                    'result' => $courseResult,
                    'cert_no' => $certNo,
                    'remark' => $remark,
                );



            }



        }else {

            $traineeData[] = array(
                'name' => 'There is',
                'id' => 'no',
                'department' => 'trainee!',
                'att_status' => '-',
                'pass' => 'Please',
                'fail' => 'check',
                'dis_status' => '',
                'result' => '',
                'cert_no' => '',
                'remark' => '',
            );
        }

        $tableData = array($traineeData);


        try {
            $files = array();

            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $tableData, null, $listvars);
            $files[] = $res['url'];

            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );

            if(count($certData) && $ge){
                $templateCC = Mage::helper('bs_formtemplate')->getFormtemplate('GE-CCCM');
                if($recurrent){
                    $templateCC = Mage::helper('bs_formtemplate')->getFormtemplate('GE-GCN');
                }

                foreach ($certData as $item) {
                    $res = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($item['name']).'-Certificate', $templateCC, $item);
                    $files[] = $res['url'];




                }
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'ge-certificates');
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateCertAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $res = Mage::helper('bs_traininglist')->generateCert($course);

            if($res == 'ok'){
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('All certificates have been generated!')
                );
            }elseif($res == 'existed') {
                $this->_getSession()->addError(
                    Mage::helper('bs_traininglist')->__('Certificates have already been generated! Check <a target="_blank" href="%s">here</a>', $this->getUrl('*/traineecert_traineecert/', array('course_id'=>$id)))
                );
            }elseif($res == 'trainee') {
                $this->_getSession()->addError(
                    Mage::helper('bs_traininglist')->__('This course has no trainee!')
                );
            }elseif($res == 'nocert') {
                $this->_getSession()->addError(
                    Mage::helper('bs_traininglist')->__('This course does not issue certificates!')
                );
            }else {
                $this->_getSession()->addError(
                    Mage::helper('bs_traininglist')->__('An unknown error occurred!')
                );
            }
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );
    }

    public function generateFilesAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($id);

            $preview = $this->getRequest()->getParam('preview', false);
            $this->generateCurriculum($curriculum, false, $preview);

        }
        $this->_redirect(
            '*/*/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateAction()
    {

        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $backto = $this->getRequest()->getParam('backto', false);
        if ($backto) {
            $backto = '_' . $backto;
        }
        try {
            $compress = $this->getRequest()->getParam('compress');
            $files = array();

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    //->setStoreId($storeId)
                    ->load($curriculumId);


                if($compress){
                    $files = array_merge($files,$this->generateCurriculum($curriculum, $compress));
                }else {
                    $this->generateCurriculum($curriculum);
                }


            }

            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'curriculums_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }





        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the training curriculums.')
            );
        }
        $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
    }

    public function massGenerateTwentyAction()
    {

        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $backto = $this->getRequest()->getParam('backto', false);
        if ($backto) {
            $backto = '_' . $backto;
        }
        try {
            $compress = $this->getRequest()->getParam('compress');
            $files = array();

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    //->setStoreId($storeId)
                    ->load($curriculumId);

                if($compress){
                    $files = array_merge($files,$this->generateCurriculum($curriculum, $compress, false, array('8020')));
                }else {
                    $this->generateCurriculum($curriculum, false, false, array('8020'));
                }




            }

            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'curriculums_8020_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the training curriculums.')
            );
        }
        $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
    }

    public function massGenerateThirtyAction()
    {

        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $backto = $this->getRequest()->getParam('backto', false);
        if ($backto) {
            $backto = '_' . $backto;
        }
        try {
            $compress = $this->getRequest()->getParam('compress');
            $files = array();

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    //->setStoreId($storeId)
                    ->load($curriculumId);

                if($compress){
                    $files = array_merge($files,$this->generateCurriculum($curriculum, $compress, false, array('8030')));
                }else {
                    $this->generateCurriculum($curriculum, false, false, array('8030'));
                }




            }

            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'curriculums_8030_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the training curriculums.')
            );
        }
        $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
    }

    public function massGenerateFifteenAction()
    {

        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $backto = $this->getRequest()->getParam('backto', false);
        if ($backto) {
            $backto = '_' . $backto;
        }
        try {
            $compress = $this->getRequest()->getParam('compress');
            $files = array();

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    //->setStoreId($storeId)
                    ->load($curriculumId);

                if($compress){
                    $files = array_merge($files,$this->generateCurriculum($curriculum, $compress, false, array('8015')));
                }else {
                    $this->generateCurriculum($curriculum, false, false, array('8015'));
                }




            }

            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'curriculums_8015_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the training curriculums.')
            );
        }
        $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
    }

    public function massGenerateSixteenAction()
    {

        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $backto = $this->getRequest()->getParam('backto', false);
        if ($backto) {
            $backto = '_' . $backto;
        }
        try {
            $compress = $this->getRequest()->getParam('compress');
            $files = array();

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    //->setStoreId($storeId)
                    ->load($curriculumId);

                if($compress){
                    $files = array_merge($files,$this->generateCurriculum($curriculum, $compress, false, array('8016')));
                }else {
                    $this->generateCurriculum($curriculum, false, false, array('8016'));
                }




            }

            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'curriculums_8016_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the training curriculums.')
            );
        }
        $this->_redirect('*/traininglist_curriculum' . $backto . '/', array('store' => $storeId));
    }

    public function generateCurriculum($curriculum, $compress = false, $preview = false, $forms = array('8020','8015','8016','8030'))
    {
        $finalDir = Mage::getBaseDir('media') . DS . 'files' . DS;

        $isVietnamese = $curriculum->getCVietnamese();

        $content8015 = null;

        $content = array();

        if ($curriculum->getCContent()) {
            $dir = Mage::helper('bs_traininglist/curriculum')->getFileBaseDir($curriculum->getId());
            $content8015 = $dir . '/' . $curriculum->getCContent();

            $content[] = array('src' => $content8015);
        }

        $typtraining = false;
        if(strpos("moke".$curriculum->getCName(), 'Type Training')){
            $typtraining = true;
        }

        $template8020 = Mage::helper('bs_formtemplate')->getFormtemplate('8020');
        $template8020NewInstructor = Mage::helper('bs_formtemplate')->getFormtemplate('8020_instructor');

        //$template8015 = Mage::getBaseDir('media').DS.'templates'.DS.'8015.docx';
        $template8015 = Mage::helper('bs_formtemplate')->getFormtemplate('8015');

        $footer8015 = Mage::helper('bs_formtemplate')->getFormtemplate('8015_footer');

        $template8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016');
        $footer8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016_footer');


        $template8030 = Mage::helper('bs_formtemplate')->getFormtemplate('8030');


        $listvars = array();

        $tc = false;
        $cats = $curriculum->getSelectedCategories();
        $rating = '';
        $tcRating = false;
        foreach ($cats as $cat) {
            $_category = Mage::getModel('catalog/category')->load($cat->getId());

            if($_category->getParentId() == 110){
                $tcRating = true;
            }

            if (in_array($_category->getParentId(), array(77, 103, 104, 110, 117))) {//103-AT), 104-AMO, 110-TC, 117-Others
                $rating .= $_category->getName(); break;
            }

        }

        $preparedBy = $curriculum->getPreparedBy();

        if($tcRating){
            $tc = true;
            $footer8015 = Mage::helper('bs_formtemplate')->getFormtemplate('8015_footer_tc');
            $footer8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016_footer_tc');

            $template8015 = Mage::helper('bs_formtemplate')->getFormtemplate('8015-TC');
            $template8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016-TC');
            $template8030 = Mage::helper('bs_formtemplate')->getFormtemplate('8030-TC');
        }

        $checkboxes = array(
            'newstaff' => $curriculum->getCNewStaff(),
            'mandatory' => $curriculum->getCMandatory(),
            'recurrent' => $curriculum->getCRecurrent(),
            'jobspecific' => $curriculum->getJobSpecific()


        );

        $instructorList = $curriculum->getCInstructorList();
        $newInstructors = array();
        if ($instructorList != '') {
            if (strpos("mk" . strtolower($instructorList), 'vae')) {

                if(strpos($instructorList, ",")){
                    $instructorList = explode(",", $instructorList);
                }else {
                    $instructorList = explode(" ", $instructorList);
                }


                foreach ($instructorList as $id) {
                    $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', trim($id));
                    if ($cus = $customer->getFirstItem()) {
                        $customer = Mage::getModel('customer/customer')->load($cus->getId());
                        $newInstructors[] = array(
                            'name' => strtoupper(Mage::helper('bs_traininglist')->convertToUnsign($customer->getName())),
                            'id' => $customer->getVaecoId()
                        );
                    }
                }


            }
        } else {
            $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'instructor_list')->getFirstItem();
            if ($shortcut->getId()) {
                $instructorList = $shortcut->getDescription();
            }

        }

        $facility = $curriculum->getCFacility();
        if ($facility == '') {
            $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'facility')->getFirstItem();
            if ($shortcut->getId()) {
                $facility = $shortcut->getDescription();
            }
        }


        $compliance = $curriculum->getAttributeText('c_compliance_with');
        $compliance = explode(",", $compliance);
        $compliance = array_map('trim', $compliance);

        $others = '';
        if (in_array('MTOE', $compliance)) {
            $checkboxes['mtoe'] = 1;
        }
        if (in_array('AMOTP', $compliance)) {
            $checkboxes['amotp'] = 1;
        }
        if (in_array('RSTP', $compliance)) {
            $checkboxes['rstp'] = 1;
        }
        if (in_array('Others', $compliance)) {
            $checkboxes['others'] = 1;
            $others = $curriculum->getCComplianceOther();
        }

        $frequency = $curriculum->getAttributeText('c_frequency');

        if ($frequency != '') {
            $checkboxes['frequency'] = 1;
        } else {
            $frequency = '';
        }

        $remedial = $curriculum->getCRemedial();

        if ($remedial) {
            $checkboxes['remedialyes'] = 1;
        } else {
            $checkboxes['remedialno'] = 1;
        }


        $purpose = $curriculum->getCPurpose();
        if ($purpose == 146) {
            $checkboxes['ratingsupplement'] = 1;
        } elseif ($purpose == 147) {
            $checkboxes['capabilitysupplement'] = 1;
        } elseif ($purpose == 148) {
            $checkboxes['otherpurpose'] = 1;
        }


        if ($curriculum->getCClassroom()) {
            $checkboxes['classroom'] = 1;
        }

        if ($curriculum->getCSeminar()) {
            $checkboxes['seminar'] = 1;
        }

        if ($curriculum->getCSelfStudy()) {
            $checkboxes['selfstudy'] = 1;
        }
        if ($curriculum->getCCaseStudy()) {
            $checkboxes['casestudy'] = 1;
        }
        if ($curriculum->getCEmbedded()) {
            $checkboxes['embedded'] = 1;
        }
        if ($curriculum->getCTasktraining()) {
            $checkboxes['tasktraining'] = 1;
        }

        $cObjectives = $curriculum->getCObjectives();

        if ($cObjectives != '') {
            $cObjectives = explode("\r\n", $cObjectives);

            $listvars['objectives'] = array();
            foreach ($cObjectives as $cObjective) {
                if(trim($cObjective) != ''){
                    $listvars['objectives'][] = $cObjective;
                }
            }

        }

        $durationData = array(
            'Training days: N/A',
            'Training hours: N/A'
        );
        $duration = $curriculum->getCDuration();
        $durationCustom = $curriculum->getCDurationCustom();

        if (strlen($duration) > 15) {//this must be custom duration for special course
            $durationData = array(
                'Training days/ training hours: ' . $duration

            );
        } else {
            $duration = floatval($duration);
            if ($duration > 0) {
                $day = ceil(($duration / 8)*2)/2;

                if($isVietnamese){
                    $durationData = array(
                        'Training days: ' . $day . ' ngày',
                        'Training hours: ' . $duration . ' giờ'
                    );
                }else {
                    $durationData = array(
                        'Training days: ' . $day . ' days',
                        'Training hours: ' . $duration . ' hours'
                    );
                }
            }
        }


        if ($durationCustom) {
            $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'duration')->getFirstItem();
            if ($shortcut->getId()) {
                $customDuration = $shortcut->getDescription();
            }

            $durationData[] = $customDuration;
        }

        if ($curriculum->getCOnline()) {
            /*$shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_duration')->getFirstItem();
            if ($shortcut->getId()) {
                $customDuration = $shortcut->getDescription();
            }*/

            $durationData = array(
                'Training days: TBD',
                'Training hours: TBD'
            );

            //$durationData = explode("\r\n", $customDuration);
        }
        $listvars['duration'] = $durationData;


        $capacityData = array(
            'Recommended: N/A',
            'Minimum: N/A',
            'Maximum: N/A'
        );

        $capacity = $curriculum->getCCapacity();
        if ($capacity != '') {
            $capacity = explode(",", $capacity);
            if (count($capacity) == 3) {
                $recommend = $capacity[0];
                $recommendInt = (int)$recommend;
                if ($recommendInt > 0) {
                    if ($recommendInt < 10) {
                        $recommendtxt = '0' . $recommendInt;
                    } else {
                        $recommendtxt = $recommendInt;
                    }
                    $textRecommend = "Recommended: {$recommendtxt} trainees";
                    if($isVietnamese){
                        $textRecommend = "Recommended: {$recommendtxt} học viên";
                    }
                } else {
                    $textRecommend = "Recommended: N/A";
                }


                $minimum = $capacity[1];
                $minimumInt = (int)$minimum;
                if ($minimumInt > 0) {
                    if ($minimumInt < 10) {
                        $minimumtxt = '0' . $minimumInt;
                    } else {
                        $minimumtxt = $minimumInt;
                    }
                    $textMinimum = "Minimum: {$minimumtxt} trainees";
                    if($isVietnamese){
                        $textMinimum = "Minimum: {$minimumtxt} học viên";
                    }
                } else {
                    $textMinimum = "Minimum: N/A";
                }

                $maximum = $capacity[2];
                $maximumInt = (int)$maximum;
                if ($maximumInt > 0) {
                    if ($maximumInt < 10) {
                        $maximumtxt = '0' . $maximumInt;
                    } else {
                        $maximumtxt = $maximumInt;
                    }
                    $textMaximum = "Maximum: {$maximumtxt} trainees";
                    if($isVietnamese){
                        $textMaximum = "Maximum: {$maximumtxt} học viên";
                    }
                } else {
                    $textMaximum = "Maximum: N/A";
                }

                $recommendArray = explode("-", $recommend);
                if (count($recommendArray) == 2) {
                    $textRecommend = "Recommended: {$recommendArray[0]} trainees for theoretical (Maximum {$recommendArray[1]} trainees/ 1 group for practical)";
                    if($isVietnamese){
                        $textRecommend = "Recommended: {$recommendArray[0]} học viên học lý thuyết (Tối đa {$recommendArray[1]} học viên/ 1 nhóm thực hành)";
                    }
                }

                $minimumArray = explode("-", $minimum);
                if (count($minimumArray) == 2) {
                    $textMinimum = "Minimum: {$minimumArray[0]} trainees for theoretical (Maximum {$minimumArray[1]} trainees/ 1 group for practical)";
                    if($isVietnamese){
                        $textMinimum = "Minimum: {$minimumArray[0]} học viên học lý thuyết (Tối thiểu {$minimumArray[1]} học viên/ 1 nhóm thực hành)";
                    }
                }

                $maximumArray = explode("-", $maximum);
                if (count($maximumArray) == 2) {
                    $textMaximum = "Maximum: {$maximumArray[0]} trainees for theoretical (Maximum {$maximumArray[1]} trainees/ 1 group for practical)";
                    if($isVietnamese){
                        $textMaximum = "Maximum: {$maximumArray[0]} học viên học lý thuyết (Maximum {$maximumArray[1]} học viên/ 1 nhóm thực hành)";
                    }
                }


                $capacityData = array(
                    $textRecommend,
                    $textMinimum,
                    $textMaximum
                );
            }


        }

        $listvars['capacity'] = $capacityData;

        $knowledge = $curriculum->getCKnowledge();
        if ($knowledge == '') {
            $knowledge = 'N/A';
        }

        $skill = $curriculum->getCSkill();
        if ($skill == '') {
            $skill = 'N/A';
        }

        $experience = $curriculum->getCExperience();
        if ($experience == '') {
            $experience = 'N/A';
        }

        $docwise = 'N/A';
        $docwiseRequire = $curriculum->getCDocwise();
        if ($docwiseRequire) {
            $docwise = 'Holding a valid DOCWISE Certificate';

        }
        if ($curriculum->getCDocwiseCustom() != '') {
            $docwise = $curriculum->getCDocwiseCustom();
        }

        $prerequisitesData = array(
            'Knowledge: ' . $knowledge,
            'Skill: ' . $skill,
            'Experience: ' . $experience,
            'English: ' . $docwise
        );


        $listvars['prerequisites'] = $prerequisitesData;

        //get doc info



        $manualDate = $curriculum->getCManualDate();
        $manualDate = Mage::getModel('core/date')->date("d M Y", $manualDate);
        $docRev = $curriculum->getAttributeText('c_manual_rev');
        $docDate = '';
        $docPage = (int)$curriculum->getCManualPage();
        if($docPage == 0){
            $docPage = 'A/R';
        }elseif($docPage > 0 && $docPage < 10){
            $docPage = '0'.$docPage;
        }

        /*$amm = false;
        if ($document->getId()) {
            $docRev = Mage::getSingleton('bs_curriculumdoc/curriculumdoc_attribute_source_cdocrev')->getOptionText($document->getCdocRev());
            $docPage = $document->getCdocPage();

            $docDate = $document->getCdocDate();
            $docDate = Mage::getModel('core/date')->date("d M Y", $docDate);

            $amm = $document->getCdocAmm();


        }*/



        $totalPage = 0;
        $aR = false;
        $tableDoc = array();


        $docTitle = $curriculum->getCName();
        $docTitle = trim($docTitle);

        $titleArr = explode(" ", $docTitle);
        if($titleArr[count($titleArr)-1] == 'Training'){
            $docTitle .= ' Manual';
        }else {
            $docTitle .= ' Training Manual';
        }

        if($curriculum->getCVietnamese()){
            $docTitle = 'Giáo trình đào tạo '.$curriculum->getCName();
        }



        //get all document

        $documents = Mage::getModel('bs_curriculumdoc/curriculumdoc')->getCollection()->addFieldToFilter('cdoc_type', 65)->addCurriculumFilter($curriculum)->setOrder('position', 'ASC');

        foreach ($documents as $item) {
            $dtitle = $item->getCdocName();
            $dpage = (int)$item->getCdocPage();
            $totalPage += $dpage;
            if($dpage == 0){
                $dpage = 'A/R';
                $aR = true;
            }elseif($dpage > 0 && $dpage <10){
                $dpage = '0'.$dpage;
            }
            $tableDoc[] = array(
                'item' => $dtitle,
                'page' => $dpage
            );

        }





        //Get Worksheet info
        $wsInfo = '';
        $wsPage = 0;
        $contentTitle = '';
        $wsCode = '';
        $wscontent = null;
        $worksheetInfo = array();
        $docInfo = $docTitle . ' (as revised); ';
        $ws = Mage::getModel('bs_worksheet/worksheet')->getCollection()->addCurriculumFilter($curriculum);
        if (count($ws)) {
            $worksheet = $ws->getFirstItem();
            $wsName = $worksheet->getWsName();
            $wsCode = $worksheet->getWsCode();
            $wsPage = (int)$worksheet->getWsPage();

            $totalPage += $wsPage;

            $wsFile = $worksheet->getWsFile();


            $rev = Mage::getModel('bs_worksheet/worksheet_attribute_source_wsrevision')->getOptionText($worksheet->getWsRevision());
            if ($rev == '') {
                $rev = '00';
            }
            $wsInfo = "Training Worksheet: \"{$wsName}\", code \"{$wsCode}/YYZZ\"";
            if($isVietnamese){
                $wsInfo = "Phiếu đào tạo thực hành: \"{$wsName}\", code \"{$wsCode}/YYZZ\"";
            }

            //$wsDoc = Mage::getModel('bs_worksheetdoc/worksheetdoc')->getCollection()->addWorksheetFilter($worksheet);

            $content8016 = '';

            if ($wsFile) {
                $wsFile = Mage::helper('bs_worksheet/worksheet')->getFileBaseDir() . $wsFile;

                $worksheetInfo = array(
                    'title' => $wsName,
                    'code' => $wsCode,
                    'rev' => $rev,
                    'prepared_by'   => $preparedBy

                );

                $wscontent = array(array('src' => $wsFile));
            }



        }


        $qualificationWs = '';
        $isTaskTrainingCourse = $curriculum->getCTasktrainingCourse();
        if ($isTaskTrainingCourse) {
            //$docInfo = '';
            if ($wsInfo != '') {
                $qualificationWs = 'Complete the ' . $wsInfo . ', and';
                if($isVietnamese){
                    $qualificationWs = 'Hoàn thành ' . $wsInfo . ', và';
                }
            }
            if ($wsCode != '') {
                if($isVietnamese){
                    $contentTitle = ' phiếu đào tạo thực hành đính kèm ' . $wsCode . '/YYZZ';
                }else {
                    $contentTitle = ' attached Training Worksheet code ' . $wsCode . '/YYZZ';
                }
            }
        } else {
            if($isVietnamese){
                $contentTitle = 'Theo nội dung sau:';
            }else {
                $contentTitle = ' I.A.W the following table:';
            }
        }


        $toolInfo = 'A/R';
        $tool = $curriculum->getCTool();
        if ($tool != '') {
            $toolInfo = $tool;
        }
        $materialsData = array(
            'Document handout: ' . $docInfo,
            'Tool/equipment: ' . $toolInfo
        );


        $listvars['materials'] = $materialsData;


        $examType = 'Exam type: ';
        $passMark = 'Pass mark: ';
        $passQualification = '';

        $examTypeText = array();

        $examTypes = $curriculum->getAttributeText('c_exam_type');
        if ($examTypes != '') {


            $examTypes = explode(",", $examTypes);
            $count = count($examTypes);

            foreach ($examTypes as $type) {
                if (strpos(strtolower('mk' . $type), "multiple")) {

                    if($isVietnamese){
                        $examTypeText[] = 'Thi trắc nghiệm';
                        $passQualification .= 'Đạt phần thi trắc nghiệm';
                        $passMark .= '75% phần trắc nghiệm, ';
                    }else {
                        $examTypeText[] = $type;
                        $passQualification .= 'Passed all exams';
                        $passMark .= '75% for Multiple Choice, ';
                    }
                }
                if (strpos(strtolower('mk' . $type), "practical")) {
                    $add = '';

                    if ($count <= 1) {
                        if($isVietnamese){
                            $add .= 'Đạt phần đánh giá thực hành';
                        }else {
                            $add .= 'Passed practical assessment';
                        }
                    } else {
                        if($isVietnamese){
                            $add .= '/ đánh giá thực hành';
                        }else {
                            $add .= '/ practical assessment';
                        }
                    }
                    $passQualification .= $add;
                    if($isVietnamese){
                        $examTypeText[] = 'Thi thực hành';
                        $passMark .= "Đạt phần đánh giá thực hành, ";
                    }else {
                        $examTypeText[] = $type;
                        $passMark .= "Passed Practical Assessment, ";
                    }
                }
                if (strpos(strtolower('mk' . $type), "essay")) {
                    $add = '';
                    $examTypeText[] = $type;
                    if ($count <= 1) {
                        $add .= 'Passed essay';
                    } else {
                        $add .= '/ essay';
                    }
                    $passQualification .= $add;


                    $passMark .= "Passed Essay Question, ";
                }
            }

            $passMark = substr($passMark, 0, -2);

        } else {
            $examTypeText[] = 'N/A';
            $passMark .= 'N/A';
        }
        if ($passQualification != '') {
            if($isVietnamese){
                $passQualification .= ', và';
            }else{
                $passQualification .= ', and';
            }
        }

        $examData = array(
            $examType.implode(", ",$examTypeText),
            $passMark
        );

        $listvars['exam'] = $examData;


        if($isVietnamese){
            $qualificationTitle = 'Học viên được công nhận hoàn thành khoá học ';
        }else {
            $qualificationTitle = 'The trainee will be qualified as completed the course ';
        }

        $hasCertCompletion = $curriculum->getCCertCompletion();
        if ($hasCertCompletion) {
            if($isVietnamese){
                $qualificationTitle .= 'và được nhận chứng chỉ hoàn thành ';
            }else {
                $qualificationTitle .= 'and received the Certificate of Course Completion ';
            }
        }

        $hasCertRecognition = $curriculum->getCCertRecognition();
        if ($hasCertRecognition) {
            $qualificationTitle .= 'and received the Certificate of Course Recognition ';
        }

        $hasCertAttendance = $curriculum->getCCertAttendance();
        if ($hasCertAttendance) {
            $qualificationTitle .= 'and received the Attestation of Course Attendance ';
        }

        if($isVietnamese){
            $qualificationTitle .= 'nếu';
        }else {
            $qualificationTitle .= 'if';
        }

        if (!$curriculum->getCOnline()) {
            if($isVietnamese){
                $qualificationData = array(
                    'Tham dự tối thiểu 90% thời lượng của khoá học, và',

                );
                $qualificationData[] = 'Hoàn thành nội dung khoá học, và';
            }else {
                $qualificationData = array(
                    'Attended minimum of 90% course duration, and',

                );
                $qualificationData[] = 'Completed the course contents, and';
            }
        } else {
            $onlineShortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'online_course')->getFirstItem();
            $shortcutContent = explode("\r\n", $onlineShortcut->getDescription());
            if (count($shortcutContent)) {
                foreach ($shortcutContent as $item) {
                    $qualificationData[] = $item;
                }

            }
        }

        if ($passQualification != '') {
            $qualificationData[] = $passQualification;
        }
        if ($qualificationWs != '') {
            $qualificationData[] = $qualificationWs;
        }

        $isPractical = $curriculum->getCPractical();
        if ($isPractical) {
            $qualificationData[] = 'Per Performed required tasks in related Training Worksheet and is confirmed by Instructors of completion of the those tasks, and';
        }

        if($curriculum->getCExamCustom() != ''){
            $qualificationData[] = $curriculum->getCExamCustom().', and';
        }

        if($isVietnamese){
            $qualificationData[] = 'Không bị đuổi học';
        }else {
            $qualificationData[] = 'Not expelled from the course';
        }



        $listvars['qualification'] = $qualificationData;

        $material8020 = $docInfo;


        //Now we go with Subjects and they contents

        $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->setOrder('subject_order', 'ASC')->setOrder('entity_id', 'ASC');
        $subjectData = array();
        $totalHour = 0;
        $totalExamHour = 0;
        if (count($subjects)) {
            $i = 1;
            foreach ($subjects as $sub) {
                $subject = Mage::getModel('bs_subject/subject')->load($sub->getId());
                $subName = $subject->getSubjectName();
                if ($subject->getSubjectOnlycontent()) {
                    $subName = '';
                }
                $subLevel = $subject->getSubjectLevel();
                $subHour = (float)$subject->getSubjectHour();
                $totalHour += $subHour;
                $subContent = $subject->getSubjectContent();
                $subRemark = $subject->getSubjectNote();
                $bolder = true;

                if ($subContent != '') {
                    $subContent = explode("\r\n", $subContent);
                }

                //Now get subject content
                $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sub->getId())->setOrder('subcon_order', 'ASC')->setOrder('entity_id', 'ASC');

                $count = count($subjectContents);

                if ($count) {
                    $subLevel = '';
                }

                $b = 'b';

                if($typtraining){
                    $b = 'no';
                }
                if($subject->getSubjectExam()){
                    $b = 'no';
                    $totalExamHour += $subHour;
                }

                //For type training, should not use Bolder


                $subjectData[] = array(
                    'no' => $i,
                    'name' => $subName,
                    'level' => $subLevel,
                    'hour' => ($subHour > 0) ? $subHour : 'TBD',
                    'remark' => $subRemark,
                    'list' => $subContent,
                    'bolder' => $b
                );


                if ($count) {
                    $j = 1;
                    foreach ($subjectContents as $subcon) {

                        $subconContent = $subcon->getSubconContent();
                        if ($subconContent != '') {
                            $subconContent = explode("\r\n", $subconContent);
                        }

                        $subjectData[] = array(
                            'no' => $i . '.' . $j,
                            'name' => $subcon->getSubconTitle(),
                            'level' => (int)$subcon->getSubconLevel() > 0 ? (int)$subcon->getSubconLevel() : '',
                            'hour' => (float)$subcon->getSubconHour() > 0 ? (float)$subcon->getSubconHour() : 'TBD',
                            'remark' => $subcon->getSubconNote(),
                            'list' => $subconContent,
                            //'bolder' => false
                        );

                        $j++;
                    }

                }

                $i++;
            }

            $ttHourDisplay = 'TBD';
            if($totalHour){
                $ttHourDisplay = $totalHour;
            }

            $subjectData[] = array(
                'no' => '',
                'name' => 'Total',
                'level' => '',
                'hour' => $ttHourDisplay,
                'remark' => '',

                'bolder' => 'b'
            );

        }

        //if we have subjects then we need to check the curriculum duration to match the hour
        if($totalHour > 0){
            $trainingHour = $totalHour - $totalExamHour;
            if(abs($totalHour - (float)$duration) > 0){

                //Lets save the curriculum then request to generate again
                $curriculum->setCDuration($totalHour)->save();
                $this->_getSession()->addNotice(
                    Mage::helper('bs_traininglist')->__('We found that the total hours of all subjects is different from the duration and we have corrected it. Please generate again!')
                );
                $this->_redirect(
                    '*/*/edit',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        '_current' => true
                    )
                );

                return;

            }
        }

        $cRev = $curriculum->getAttributeText('c_rev');
        if ($cRev == '') {
            $cRev = '00';
        }

        $evaluatedBy = 'Vũ Trường Thành';
        $approvedBy = '';
        if($tc){
            $evaluatedBy = 'Nguyễn Bá Việt';
            $approvedBy = 'Vũ Trường Thành';
        }

        $description = $curriculum->getCDescription();
        $description = explode("\r\n", $description);

        $listvars['description'] = $description;

        $objectiveTitle = 'Upon completion of the course, the trainee will be able to';
        if($isVietnamese){
            $objectiveTitle = 'Sau khi hoàn thành khoá đào tạo, học viên nắm được';
        }

        $data = array(
            'title' => $curriculum->getCName(),
            'code' => $curriculum->getCCode(),
            'rev' => $cRev,
            'rating' => $rating,
            'others' => $others,
            'objectives_title' => $objectiveTitle,
            'frequency_title' => $frequency,
            'qualification_title' => $qualificationTitle,
            'content_title' => $contentTitle,
            'material8020' => $material8020,
            'instructor_list' => $instructorList,
            'facility' => $facility,
            'evaluated_by' => $evaluatedBy,
            'approved_by'   => $approvedBy,
            'prepared_by'   => $preparedBy
        );


        if ($wsInfo != '') {
            $wsPageDis = '0';
            if($wsPage > 0 && $wsPage < 10){
                $wsPageDis = '0'.$wsPage;
            }else {
                $wsPageDis = $wsPage;
            }
            $tableDoc[] = array(
                'item' => $wsInfo,
                'page' => ($wsPageDis != '0')?$wsPageDis:''
            );

        }

        /*if ($amm) {
            $tableDoc[] = array(
                'item' => 'AMM/CMM/SRM... revised',
                'page' => 'A/R'
            );

            $totalPage = 'A/R';
        }*/


        if($aR){
            $totalPage = 'A/R';
        }

        $data8030 = array(
            'title' => $curriculum->getCName(),
            'doc_title' => $docTitle,
            'code' => $curriculum->getCCode(),
            'issue' => '01',
            'rev' => $docRev,
            'date' => $manualDate,
            'total' => $totalPage,
            'prepared_by'   => $preparedBy
        );

        $tableDocFinal = array($tableDoc);


        $tableHtml = null;

        $spacing = trim($curriculum->getRowSpacing());
        if($spacing != ''){
            $spacing = explode("-",$spacing);
        }

        if (count($subjectData)) {
            $tableHtml = array(
                'variable' => 'WORDML',
                'content' => Mage::helper('bs_traininglist/wordxml')->prepareFifteenTable($subjectData,$spacing)
            );


        }else {
            $data['WORDML'] = '';
        }


        $tableData = null;
        if (count($newInstructors)) {
            $template8020 = $template8020NewInstructor;

            $tableData = array($newInstructors);
        }


        try {
            $files = array();

            if(in_array('8020', $forms)){
                $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8020_TRAINING COURSE CAPACITY SELF-EVALUATION SHEET-'.mt_rand(1,10000), $template8020, $data, $tableData, $checkboxes, $listvars, null, null,null,null,null,$preview);

                if($compress){
                    $files[] = $res['url'];
                }else {
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );
                }
            }



            if ($isTaskTrainingCourse) {
                $data['content'] = '';
                $content = null;
            }

            if(in_array('8015', $forms)){
                if($tc){
                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8015_TRAINING COURSE CURRICULUM-'.mt_rand(1,100000), $template8015, $data, null, $checkboxes, $listvars, null, null, null, $tableHtml, null,$preview);
                }else {
                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8015_TRAINING COURSE CURRICULUM-'.mt_rand(1,1000000), $template8015, $data, null, $checkboxes, $listvars, null, $footer8015, null, $tableHtml, null,$preview);
                }

                if($compress){
                    $files[] = $res['url'];
                }else {
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );
                }
            }




            if (count($worksheetInfo)) {
                if(in_array('8016', $forms)){
                    if($tc){
                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8016_TRAINING WORKSHEET-'.mt_rand(1,100000), $template8016, $worksheetInfo, null, null, null, null, null,null,null,$wscontent[0]['src'], $preview);
                    }else {
                        $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8016_TRAINING WORKSHEET-'.mt_rand(1,100000), $template8016, $worksheetInfo, null, null, null, $wscontent, $footer8016, null,null,null,$preview);
                    }

                    if($compress){
                        $files[] = $res['url'];
                    }else {
                        $this->_getSession()->addSuccess(
                            Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                        );
                    }
                }

            }


            if(in_array('8030', $forms)){
                $res = Mage::helper('bs_traininglist/docx')->generateDocx($curriculum->getCCode() . '_8030_TRAINING COURSE DOCUMENTATION-'.mt_rand(1,100000), $template8030, $data8030, $tableDocFinal,null,null,null,null,null,null,null,$preview);
                if($compress){
                    $files[] = $res['url'];
                }else {
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );
                }
            }


            if($compress){
                return $files;
            }



        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateSeventeenAction()
    {
        try {

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8017');

            $categoryId = $this->getRequest()->getParam('id');

            $rootCat = Mage::getModel('catalog/category')->load($categoryId);

            $name = $rootCat->getName();

            $checkboxes = array();
            if (strpos("moke".strtolower($name), "ato")) {
                $checkboxes['mtoe'] = 1;
            }elseif (strpos("moke".strtolower($name), "amo")) {
                $checkboxes['amotp'] = 1;
                $checkboxes['rstp'] = 1;
            }else {
                $checkboxes['others'] = 1;
            }



            $categories = $rootCat->getChildrenCategories();
            $result = array();
            $i=1;
            foreach ($categories as $cat1) {


                $category1 = Mage::getModel('catalog/category')->load($cat1->getId());
                $name1 = $category1->getName();
                if(!strpos($name1, "course")){
                    $name1 .= " Courses";
                }
                if($category1->getDescription() != ''){
                    $name1 .= '--desc--'.$category1->getDescription();
                }
                $result[] = $i.'. '.$name1;
                if(count($category1->getChildrenCategories())){
                    $j=1;
                    foreach ($category1->getChildrenCategories() as $cat2) {
                        $category2 = Mage::getModel('catalog/category')->load($cat2->getId());
                        $name2 = $category2->getName();
                        if(!strpos($name2, "course")){
                            $name2 .= " Courses";
                        }
                        if($category2->getDescription() != ''){
                            $name2 .= '--desc--'.$category2->getDescription();
                        }
                        $result[] = $i.'.'.$j.' '.$name2;
                        if(count($category2->getChildrenCategories())){
                            $k=1;
                            foreach ($category2->getChildrenCategories() as $cat3) {
                                $category3 = Mage::getModel('catalog/category')->load($cat3->getId());
                                $name3 = $category3->getName();
                                if(!strpos($name3, "course")){
                                    $name3 .= " Courses";
                                }
                                if($category3->getDescription() != ''){
                                    $name3 .= '--desc--'.$category3->getDescription();
                                }
                                $result[] = $i.'.'.$j.'.'.$k.' '.$name3;

                                //get all curriculums
                                $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category3->getId());


                                if($curriculums && count($curriculums)){
                                    $curs = array();
                                    foreach ($curriculums as $curId) {
                                        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                                        if($curriculum->getStatus()){
                                            $rev = $curriculum->getAttributeText('c_rev');
                                            if($rev == ''){
                                                $rev = '00';
                                            }
                                            $curs[] = array(
                                                'name'  => $curriculum->getCName(),
                                                'code'  => $curriculum->getCCode(),
                                                'rev'   => $rev
                                            );
                                        }
                                    }
                                    $result[] = $curs;

                                }

                                $k++;
                            }

                        }else {
                            //get all curriculums
                            $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category2->getId());


                            if($curriculums && count($curriculums)){
                                $curs = array();
                                foreach ($curriculums as $curId) {
                                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                                    if($curriculum->getStatus()){
                                        $rev = $curriculum->getAttributeText('c_rev');
                                        if($rev == ''){
                                            $rev = '00';
                                        }
                                        $curs[] = array(
                                            'name'  => $curriculum->getCName(),
                                            'code'  => $curriculum->getCCode(),
                                            'rev'   => $rev
                                        );
                                    }
                                }
                                $result[] = $curs;

                            }
                        }

                        $j++;

                    }

                }else {
                    //get all curriculums
                    $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category1->getId());


                    if($curriculums && count($curriculums)){
                        $curs = array();
                        foreach ($curriculums as $curId) {
                            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                            if($curriculum->getStatus()){
                                $rev = $curriculum->getAttributeText('c_rev');
                                if($rev == ''){
                                    $rev = '00';
                                }
                                $curs[] = array(
                                    'name'  => $curriculum->getCName(),
                                    'code'  => $curriculum->getCCode(),
                                    'rev'   => $rev
                                );
                            }


                        }
                        $result[] = $curs;

                    }
                }

                $i++;

            }

            $today = Mage::getModel('core/date')->date("d/m/Y", time());

            $data['date'] = $today;

            $contentHtml = array(
                'type' => 'replace',
                'content' => Mage::helper('bs_traininglist/wordxml')->prepareSeventeen($result),
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name.' TRAINING LIST', $template, $data, null, $checkboxes,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        }

        $refreshTree = 'false';
        $url = $this->getUrl('*/catalog_category/edit', array('_current' => true, 'id' => $categoryId));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );

       // $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        //$this->_redirect('*/*/index');
    }

    public function generateEighteenAction()
    {
        try {

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8018');
            $title = 'INSTRUCTOR ROSTER';
            $titleVi = 'DANH SÁCH GIÁO VIÊN';

            $categoryId = $this->getRequest()->getParam('id');

            $rootCat = Mage::getModel('catalog/category')->load($categoryId);

            $name = $rootCat->getName();

            $result = Mage::helper('bs_traininglist/roster')->getEighteenData($rootCat);

            $today = Mage::getModel('core/date')->date("d/m/Y", time());

            $data['date'] = $today;
            $data['title'] = $title;
            $data['title_vi'] = $titleVi;

            $contentWordML = Mage::helper('bs_traininglist/wordxml')->prepareRosterData($result);

            $contentHtml = array(
                'type' => 'replace',
                'content' => $contentWordML,
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx($title, $template, $data, null, null,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        }

        $refreshTree = 'false';
        $url = $this->getUrl('*/catalog_category/edit', array('_current' => true, 'id' => $categoryId));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );

        // $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        //$this->_redirect('*/*/index');
    }

    public function generateEighteenTasktrainingAction()
    {
        try {

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('8018');
            $title = 'TASK TRAINING INSTRUCTOR ROSTER';
            $titleVi = 'DANH SÁCH HƯỚNG DẪN VIÊN THỰC HÀNH';

            $categoryId = $this->getRequest()->getParam('id');

            $rootCat = Mage::getModel('catalog/category')->load($categoryId);

            $name = $rootCat->getName();




            $result = Mage::helper('bs_traininglist/roster')->getEighteenTasktrainingData($rootCat);


            $today = Mage::getModel('core/date')->date("d/m/Y", time());

            $data['date'] = $today;
            $data['title'] = $title;
            $data['title_vi'] = $titleVi;

            $contentWordML = Mage::helper('bs_traininglist/wordxml')->prepareRosterData($result);

            $contentHtml = array(
                'type' => 'replace',
                'content' => $contentWordML,
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx($title, $template, $data, null, null,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        }

        $refreshTree = 'false';
        $url = $this->getUrl('*/catalog_category/edit', array('_current' => true, 'id' => $categoryId));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );

        // $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        //$this->_redirect('*/*/index');
    }

    public function generateAirportAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {

            $this->generateAirport(array($id));
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function massGenerateAirportsAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            $this->generateAirport($courses);

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateAirport($courses)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('GE-ASSESSMENT');

        $region = 'BẮC';
        $city = 'Hà Nội';
        $airport = 'Nội Bài';


        $day = Mage::getModel('core/date')->date("d", time());
        $month = Mage::getModel('core/date')->date("m", time());
        $year = Mage::getModel('core/date')->date("Y", time());

        $date = Mage::getModel('core/date')->date("d/m/Y", time());


        $tableData = array();
        foreach ($courses as $courseId) {
            $course = Mage::getModel('catalog/product')->load($courseId);

            $place = $course->getConductingPlace();
            if($place == 208){
                $region = 'NAM';
                $city = 'Hồ Chí Minh';
                $airport = 'Tân Sơn Nhất';

            }elseif($place == 207 || $place == 206){
                $region = 'TRUNG';
                $city = 'Đà Nẵng';
                $airport = 'Đà Nẵng';
            }

            $startDate = $course->getCourseStartDate();
            $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

            $finishDate = $course->getCourseFinishDate();
            $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

            if ($finishDate == $startDate) {
                $duration = $startDate;

            } else {
                $duration = $startDate . '-' . $finishDate;
            }

            $function = $course->getName();


            $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course)->setOrder('position', 'ASC');



            if (count($trainees)) {

                foreach ($trainees as $_trainee) {

                    $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());

                    $vaecoId = $trainee->getVaecoId();
                    if ($trainee->getTraineeDob() != '') {
                        $dob = Mage::getModel('core/date')->date("d/m/Y", $trainee->getTraineeDob());
                    }
                    $pob = $trainee->getTraineePob();
                    $check = array();
                    if(strpos($pob, "-")){
                        $check = explode("-", $pob);
                    }elseif(strpos($pob, ",")){
                        $check = explode(",", $pob);
                    }
                    if(count($check)){
                        $pob = trim($check[count($check)-1]);
                    }

                    $tableData[] = array(
                        'name' => $trainee->getTraineeName(),
                        'pob' => $pob,
                        'place' => $airport,
                        'duration' => $duration,
                        'function' => $function,
                        'date' => $date,
                    );


                }



            }


        }

        $tableData = array($tableData);




        $data = array(
            'REGION' => $region,
            'city' => $city,
            'day' => $day,
            'month'=> $month,
            'year'  => $year,

        );

        $name = 'PHIEU GIAM SAT KIEM TRA THUC HANH';

        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, $tableData);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function generateLocAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $course = Mage::getModel('catalog/product')->load($id);
            $this->generateLoc($course);
        }
        $this->_redirect(
            '*/catalog_product/edit',
            array(
                'id' => $this->getRequest()->getParam('id'),
                '_current' => true
            )
        );

    }

    public function generateMaterialAction()
    {
        try {

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('material');

            $categoryId = $this->getRequest()->getParam('id');

            $rootCat = Mage::getModel('catalog/category')->load($categoryId);

            $name = $rootCat->getName();

            $checkboxes = array();
            if (strpos("moke".strtolower($name), "ato")) {
                $checkboxes['mtoe'] = 1;
            }elseif (strpos("moke".strtolower($name), "amo")) {
                $checkboxes['amotp'] = 1;
                $checkboxes['rstp'] = 1;
            }else {
                $checkboxes['others'] = 1;
            }



            $categories = $rootCat->getChildrenCategories();
            $result = array();
            $i=1;
            foreach ($categories as $cat1) {


                $category1 = Mage::getModel('catalog/category')->load($cat1->getId());
                $name1 = $category1->getName();
                if($category1->getDescription() != ''){
                    $name1 .= '--desc--'.$category1->getDescription();
                }
                $result[] = $i.'. '.$name1;
                if(count($category1->getChildrenCategories())){
                    $j=1;
                    foreach ($category1->getChildrenCategories() as $cat2) {
                        $category2 = Mage::getModel('catalog/category')->load($cat2->getId());
                        $name2 = $category2->getName();
                        if($category2->getDescription() != ''){
                            $name2 .= '--desc--'.$category2->getDescription();
                        }
                        $result[] = $i.'.'.$j.' '.$name2;
                        if(count($category2->getChildrenCategories())){
                            $k=1;
                            foreach ($category2->getChildrenCategories() as $cat3) {
                                $category3 = Mage::getModel('catalog/category')->load($cat3->getId());
                                $name3 = $category3->getName();
                                if($category3->getDescription() != ''){
                                    $name3 .= '--desc--'.$category3->getDescription();
                                }
                                $result[] = $i.'.'.$j.'.'.$k.' '.$name3;

                                //get all curriculums
                                $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category3->getId());


                                if($curriculums && count($curriculums)){
                                    $curs = array();
                                    foreach ($curriculums as $curId) {
                                        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                                        if($curriculum->getStatus()){
                                            $rev = $curriculum->getAttributeText('c_rev');
                                            $manRev = $curriculum->getAttributeText('c_manual_rev');
                                            $date = Mage::getModel('core/date')->date("d/m/Y", $curriculum->getCManualDate());
                                            $cName = $curriculum->getCName();
                                            $cName = str_replace("&", "&amp;", $cName);
                                            $cCode = $curriculum->getCCode();
                                            $cCode = str_replace("&", "&amp;", $cCode);
                                            $add = ' Training Manual';
                                            if(preg_match('/(training)$/i', $cName)){
                                                $add = ' Manual';
                                            }
                                            $manName = $cName.$add;

                                            if($rev == ''){
                                                $rev = '00';
                                            }
                                            $curs[] = array(
                                                'name'  => $cName,
                                                'code'  => $cCode,
                                                'rev'   => $rev,
                                                'man_name'  => $manName,
                                                'man_code'  => $curriculum->getCCode().'-MAN',
                                                'man_rev'   => $manRev,
                                                'man_date'  => $date
                                            );
                                        }
                                    }
                                    $result[] = $curs;

                                }

                                $k++;
                            }

                        }else {
                            //get all curriculums
                            $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category2->getId());


                            if($curriculums && count($curriculums)){
                                $curs = array();
                                foreach ($curriculums as $curId) {
                                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                                    if($curriculum->getStatus()){
                                        $rev = $curriculum->getAttributeText('c_rev');
                                        $manRev = $curriculum->getAttributeText('c_manual_rev');
                                        $date = Mage::getModel('core/date')->date("d/m/Y", $curriculum->getCManualDate());
                                        $cName = $curriculum->getCName();
                                        $cName = str_replace("&", "&amp;", $cName);
                                        $cCode = $curriculum->getCCode();
                                        $cCode = str_replace("&", "&amp;", $cCode);
                                        $add = ' Training Manual';
                                        if(preg_match('/(training)$/i', $cName)){
                                            $add = ' Manual';
                                        }
                                        $manName = $cName.$add;

                                        if($rev == ''){
                                            $rev = '00';
                                        }
                                        $curs[] = array(
                                            'name'  => $cName,
                                            'code'  => $cCode,
                                            'rev'   => $rev,
                                            'man_name'  => $manName,
                                            'man_code'  => $curriculum->getCCode().'-MAN',
                                            'man_rev'   => $manRev,
                                            'man_date'  => $date
                                        );
                                    }
                                }
                                $result[] = $curs;

                            }
                        }

                        $j++;

                    }

                }else {
                    //get all curriculums
                    $curriculums = Mage::helper('bs_traininglist')->getCurriculumByCategory($category1->getId());


                    if($curriculums && count($curriculums)){
                        $curs = array();
                        foreach ($curriculums as $curId) {
                            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curId);
                            if($curriculum->getStatus()){
                                $rev = $curriculum->getAttributeText('c_rev');
                                $manRev = $curriculum->getAttributeText('c_manual_rev');
                                $date = Mage::getModel('core/date')->date("d/m/Y", $curriculum->getCManualDate());
                                $cName = $curriculum->getCName();
                                $cName = str_replace("&", "&amp;", $cName);
                                $cCode = $curriculum->getCCode();
                                $cCode = str_replace("&", "&amp;", $cCode);
                                $add = ' Training Manual';
                                if(preg_match('/(training)$/i', $cName)){
                                    $add = ' Manual';
                                }
                                $manName = $cName.$add;

                                if($rev == ''){
                                    $rev = '00';
                                }
                                $curs[] = array(
                                    'name'  => $cName,
                                    'code'  => $cCode,
                                    'rev'   => $rev,
                                    'man_name'  => $manName,
                                    'man_code'  => $curriculum->getCCode().'-MAN',
                                    'man_rev'   => $manRev,
                                    'man_date'  => $date
                                );
                            }


                        }
                        $result[] = $curs;

                    }
                }

                $i++;

            }

            $today = Mage::getModel('core/date')->date("d/m/Y", time());

            $data['date'] = $today;

            $contentHtml = array(
                'type' => 'replace',
                'content' => Mage::helper('bs_traininglist/wordxml')->prepareMaterialList($result),
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name.' MATERIAL CONTROL LIST', $template, $data, null, $checkboxes,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        }

        $refreshTree = 'false';
        $url = $this->getUrl('*/catalog_category/edit', array('_current' => true, 'id' => $categoryId));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );

        // $this->getResponse()->setRedirect($this->getUrl('*/catalog_category/', array('_current'=>true, 'id'=>null)));
        //$this->_redirect('*/*/index');
    }

    public function massGenerateLocAction()
    {

        $courses = (array)$this->getRequest()->getParam('product');
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        try {
            foreach ($courses as $courseId) {
                $course = Mage::getSingleton('catalog/product')
                    //->setStoreId($storeId)
                    ->load($courseId);

                $this->generateLoc($course);

            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/catalog_product/', array('store' => $storeId));
    }

    public function generateLoc($course)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('loc');

        $sku = $course->getSku();

        $name = $sku . '_LIST OF DOCS';


        $checkboxes = array('hr_dispatch' => true);

        $content = 'Tin nhắn ';
        $date = '';
        $planNo = '';
        $hrNo = '';
        $reportNo = '';
        $hrDisType = $course->getHrDisType();
        if($hrDisType == 399){
            $content = $course->getHrDispatchNo();
        }

        if($course->getHrDispatchDate() != ''){
            $date = Mage::getModel('core/date')->date("d/m/Y", $course->getHrDispatchDate());
        }

        if($course->getPlanDispatchNo() != ''){
            $checkboxes['plan_dispatch'] = true;

            $planNo = $course->getPlanDispatchNo();
        }

        if($course->getHrDecisionNo() != ''){
            $checkboxes['hr_r'] = true;
            $hrNo = $course->getHrDecisionNo();
        }

        if($course->getCourseReport()){
            $checkboxes['report'] = true;
            $reportNo = $course->getReportDispatchNo();
        }


        $startDate = $course->getCourseStartDate();
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

        $finishDate = $course->getCourseFinishDate();
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);





        $today = Mage::getModel('core/date')->date("d/m/Y", time());



        $data = array(
            'name' => $course->getName(),
            'code' => $course->getSku(),
            'start' => $startDate,
            'finish'    => $finishDate,
            'content'   => $content,
            'date'      => $date,
            'plan_no'   => $planNo,
            'hr_no'     => $hrNo,
            'report_no' => $reportNo,

        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data, null, $checkboxes);
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function getFullNameAction()
    {


        $result = array();
        $vaecoId = $this->getRequest()->getPost('vaeco_id');
        if (strlen($vaecoId) == 4) {
            $vaecoId = 'VAE0' . $vaecoId;
        } elseif (strlen($vaecoId) == 5) {
            $vaecoId = 'VAE' . $vaecoId;
        }

        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();

        if ($customer->getId()) {
            $result['vaecoid'] = $vaecoId;
            $result['fullname'] = $customer->getFirstname() . ' ' . $customer->getLastname();
            $result['phone'] = $customer->getPhone();
            $result['username'] = $customer->getUsername();
            $result['position'] = $customer->getPosition();

            $division = $customer->getDivision();
            $groupId = $customer->getGroupId();
            $group = Mage::getModel('customer/group')->load($groupId);

            $result['department'] = $division . ' - ' . $group->getCustomerGroupCodeVi();
        }


        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getCourseNameAction()
    {


        $result = array();
        $courseCode = $this->getRequest()->getPost('course_code');
        $conductingPlace = $this->getRequest()->getPost('conducting_place');

        $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()
            ->addAttributeToSelect('c_name')
            ->addAttributeToFilter('c_code', $courseCode)
            ->addAttributeToFilter('c_history', 0)->getFirstItem();

        if ($curriculum->getId()) {
            $result['course_name'] = $curriculum->getCName();
            $result['curriculum_code'] = $curriculum->getCCode();

            //Now we get all the courses that match

            $year = date("y", Mage::getModel('core/date')->timestamp(time()));

            $courseCode .= '/' . $year;

            $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('sku')
                ->addAttributeToFilter('sku', array('like' => $courseCode . '%'));

            $courseNumber = array();
            $lastNumber = 1;
            $courses = array();
            if ($products->count()) {
                //filter by conducting place
                if ($conductingPlace == 'HAN') {

                }
                $lastNumber = $products->count();
                $lastNumber += 1;


                foreach ($products as $product) {
                    $courses[] = $product->getSku();
                }

            }
            if ($lastNumber < 10) {
                $lastNumber = '0' . $lastNumber;
            }


            $courseCode .= $lastNumber;

            $result['course_code'] = $courseCode;

            if(count($courses)){
                $result['courses'] = 'Conducted courses: '.implode(", ", $courses);
            }




        }


        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * new curriculum action
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
     * edit curriculum action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $curriculumId = (int)$this->getRequest()->getParam('id');
        $curriculum = $this->_initCurriculum();
        if ($curriculumId && !$curriculum->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_traininglist')->__('This training curriculum no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getCurriculumData(true)) {
            $curriculum->setData($data);
        }
        $this->_title($curriculum->getCName());
        Mage::dispatchEvent(
            'bs_traininglist_curriculum_edit_action',
            array('curriculum' => $curriculum)
        );
        $this->loadLayout();
        if ($curriculum->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('bs_traininglist')->__('Default Values'))
                    ->setWebsiteIds($curriculum->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current' => true,
                                'active_tab' => null,
                                'tab' => null,
                                'store' => null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save training curriculum action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $storeId = $this->getRequest()->getParam('store');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $redirectBackTo = $this->getRequest()->getParam('backto', false);

        $curriculumId = $this->getRequest()->getParam('id');
        $isEdit = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $curriculum = $this->_initCurriculum();
            $curriculum->setAttributeSetId($curriculum->getDefaultAttributeSetId());

            $curriculumData = $this->getRequest()->getPost('curriculum', array());
            $curriculumData['c_history'] = 0;

            $check = Mage::helper('bs_traininglist')->checkCurriculum($curriculumData['c_code'], $curriculum->getId());
            if(!$check){
                //check if this is new or edit
                if ($curriculum->getId()) {//edit, we only care with revision
                    $currentRev = (int)$curriculum->getData('c_rev');
                    $newRev = (int)$curriculumData['c_rev'];

                    if ($newRev > $currentRev) {
                        //save current curriculum as historical
                        $newCurriculum = Mage::getModel('bs_traininglist/curriculum');
                        $oldData = $curriculum->getData();
                        unset($oldData['entity_id']);
                        $oldData['c_history'] = 1;
                        $oldData['c_code'] = $oldData['c_code'].'-HISTORY'.Mage::getModel('core/date')->date("dmY");
                        $newCurriculum->addData($oldData);
                        //@TODO: need to get this working
                        $newCurriculum->setProductsData($curriculum->getProductsData());
                        $newCurriculum->setCategoriesData($curriculum->getCategoriesData());

                        $newCurriculum->save();
                    }

                }


                $curriculum->addData($curriculumData);


                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $curriculum->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $curriculum->setCategoriesData($categories);
                }
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $curriculum->setData($attributeCode, false);
                    }
                }
                try {
                    $curriculum->save();

                    //save Position
                    $positions = $data['position'];
                    $shortcodes = $data['shortcode'];
                    foreach ($positions as $key => $value) {
                        $subject = Mage::getModel('bs_subject/subject')->load($key);
                        $subject->setSubjectOrder($value)->setSubjectShortcode($shortcodes[$key])->save();
                    }

                    //save Docname
                    $docnames = $data['docname'];
                    $docpages = $data['docpage'];

                    $i=0;
                    foreach ($docnames as $key => $value) {
                        $doc = Mage::getModel('bs_curriculumdoc/curriculumdoc')->load($key);
                        $doc->setCdocName($value)->setCdocPage($docpages[$key])->save();

                        $i++;
                    }

                    $curriculumId = $curriculum->getId();
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Training Curriculum was saved')
                    );
                } catch (Mage_Core_Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage())
                        ->setCurriculumData($curriculumData);
                    $redirectBack = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError(
                        Mage::helper('bs_traininglist')->__('Error saving training curriculum')
                    )
                        ->setCurriculumData($curriculumData);
                    $redirectBack = true;
                }
            }else {
                $this->_getSession()->addError(
                    Mage::helper('bs_traininglist')->__('This curriculum already existed!')
                )
                    ->setCurriculumData($curriculumData);
                $redirectBack = true;
            }



        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id' => $curriculumId,
                    '_current' => true
                )
            );
        } else {
            if ($redirectBackTo) {
                $this->_redirect('*/traininglist_curriculum_' . $redirectBackTo . '/', array('store' => $storeId));
            } else {
                $this->_redirect('*/*/', array('store' => $storeId));
            }

        }
    }

    /**
     * delete training curriculum
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $redirectBackTo = $this->getRequest()->getParam('backto', false);

            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($id);
            try {
                $curriculum->delete();

                //Delete all related subjects
                $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $id);
                if($subjects->count()){
                    foreach ($subjects as $subject) {
                        $subjectId = $subject->getId();
                        $subject->delete();

                        //Delete all subcontent
                        $subcons = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $subjectId);
                        if($subcons->count()){
                            $subcons->walk('delete');
                        }

                    }
                }


                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('The training curriculum has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        if ($redirectBackTo) {
            $this->getResponse()->setRedirect(
                $this->getUrl('*/traininglist_curriculum_' . $redirectBackTo . '/', array('store' => $this->getRequest()->getParam('store')))
            );
        } else {
            $this->getResponse()->setRedirect(
                $this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store')))
            );
        }


    }

    public function duplicateAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {


            $cu = Mage::getModel('bs_traininglist/curriculum')->load($id);
            try {

                $newCu = Mage::getModel('bs_traininglist/curriculum');
                $data = $cu->getData();
                $data['entity_id'] = null;
                $data['c_name'] = $data['c_name'].' - Duplicated!';
                $data['c_code'] = $data['c_code'].'-DUPLICATED';

                $newCu->setData($data);
                $newCu->save();

                $newId = $newCu->getId();

                //Now set subject data
                $fromSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$id)->setOrder('subject_order', 'ASC');

                if($fromSubjects->count()){
                    $i=10;
                    foreach ($fromSubjects as $sub) {
                        $oldId = $sub->getId();
                        $name = $sub->getSubjectName();
                        $level = $sub->getSubjectLevel();
                        $hour = $sub->getSubjectHour();
                        $content = $sub->getSubjectContent();
                        $shortcode = $sub->getSubjectShortcode();
                        $exam = $sub->getSubjectExam();
                        $onlyConent = $sub->getSubjectOnlycontent();
                        $order = $sub->getSubjectOrder();



                        $subject = Mage::getModel('bs_subject/subject');
                        $subject->setCurriculumId($newId)
                            ->setSubjectName($name)
                            //->setSubjectCode('')
                            ->setSubjectLevel($level)
                            ->setSubjectHour($hour)
                            ->setSubjectContent($content)
                            ->setSubjectShortcode($shortcode)
                            ->setSubjectExam($exam)
                            ->setSubjectOnlycontent($onlyConent)
                            ->setSubjectOrder($order)
                            ->save();

                        $newSId = $subject->getId();
                        $subject->setSubjectCode($newSId.'-'.Mage::helper('bs_traininglist')->generateRandomString(3))->save();


                        $subcontent = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $oldId)->setOrder('subcon_order','ASC')->setOrder('entity_id', 'ASC');
                        if($subcontent->count()){
                            $j=10;
                            foreach ($subcontent as $subcon) {
                                $sc = Mage::getModel('bs_subject/subjectcontent');
                                $sc->setSubjectId($newSId)
                                    ->setSubconTitle($subcon->getSubconTitle())
                                    //->setSubconCode('')
                                    ->setSubconLevel($subcon->getSubconLevel())
                                    ->setSubconHour($subcon->getSubconHour())
                                    ->setSubconContent($subcon->getSubconContent())
                                    ->setSubconOrder($subcon->getSubconOrder())
                                    ->save();
                                ;
                                $newScId = $sc->getId();
                                $sc->setSubconCode($newSId.'-'.$newScId.'-'.Mage::helper('bs_traininglist')->generateRandomString(3))->save();

                                $j += 10;
                            }

                        }




                        $i += 10;
                    }

                }

                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $readConnection = $resource->getConnection('core_read');
                $tableCuCat = $resource->getTableName('bs_traininglist/curriculum_category');
                $tableCuIns = $resource->getTableName('bs_instructor/instructor_curriculum');

                //set curriculumn -- category data:  curriculum_id category_id position
                $cuCat = $readConnection->fetchAll("SELECT * FROM {$tableCuCat} WHERE curriculum_id = {$id} ORDER BY position ASC");
                if(count($cuCat)){

                    try {
                        $sqlcC = "INSERT INTO {$tableCuCat} (curriculum_id, category_id, position) VALUES ";
                        $sqlcArray = array();
                        $i=1;
                        foreach ($cuCat as $cuId) {
                            $sqlcArray[] = "({$newId},{$cuId['category_id']},{$cuId['position']})";
                        }
                        $sqlcC .= implode(",", $sqlcArray).";";
                        $writeConnection->query($sqlcC);
                    } catch (Exception $e) {
                        $this->_getSession()->addError($e->getMessage());
                    }



                }

                //set curriculum -- instructor data:  instructor_id curriculum_id	position


                $cuIns = $readConnection->fetchAll("SELECT * FROM {$tableCuIns} WHERE curriculum_id = {$id} ORDER BY position ASC");

                if(count($cuIns)){
                    //Make sure we delete all current data relate to new Id? No?
                    try {
                        $sql = "INSERT INTO {$tableCuIns} (curriculum_id, instructor_id, position) VALUES ";
                        $sqlArray = array();
                        $i=1;
                        foreach ($cuIns as $cuId) {
                            $sqlArray[] = "({$newId},{$cuId['instructor_id']},{$cuId['position']})";
                        }
                        $sql .= implode(",", $sqlArray).";";
                        $writeConnection->query($sql);
                    } catch (Exception $e) {
                        $this->_getSession()->addError($e->getMessage());
                    }


                }
                $this->getResponse()->setRedirect(
                    $this->getUrl('*/traininglist_curriculum/edit', array('id' => $newId))
                );

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('The training curriculum has been duplicated.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }


    }

    /**
     * mass delete training curriculum
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        $back = $this->getRequest()->getParam('back', false);
        if (!is_array($curriculumIds)) {
            $this->_getSession()->addError($this->__('Please select training curriculum.'));
        } else {
            try {
                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getSingleton('bs_traininglist/curriculum')->load($curriculumId);
                    Mage::dispatchEvent(
                        'bs_traininglist_controller_curriculum_delete',
                        array('curriculum' => $curriculum)
                    );
                    $curriculum->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('Total of %d record(s) have been deleted.', count($curriculumIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        if($back){

            $this->_redirect('*/'.$back);
            return;

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
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getSingleton('bs_traininglist/curriculum')->load($curriculumId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplacetitleAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_title');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($curriculumIds as $curriculumId) {
                        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                        $name = $curriculum->getCName();
                        $name = str_replace($search, $replace, $name);
                        $curriculum
                            ->setCName($name)
                            ->setIsMassupdate(true)
                            ->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                    );
                }else {
                    $this->_getSession()->addNotice(
                        $this->__('Invalid format for replacement!')
                    );
                }



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplacecodeAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_code');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($curriculumIds as $curriculumId) {
                        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                        $code = $curriculum->getCCode();
                        $code = str_replace($search, $replace, $code);
                        $curriculum
                            ->setCCode($code)
                            ->setIsMassupdate(true)
                            ->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                    );
                }else {
                    $this->_getSession()->addNotice(
                        $this->__('Invalid format for replacement!')
                    );
                }



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplaceacAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('applicable');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCAircraft($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplacepurposeAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('purpose');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCPurpose($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplacecomplianceAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('compliance');
                $param = trim($param);



                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCComplianceWith($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function updatePositionAction(){
        if ($id = $this->getRequest()->getParam('id')) {


            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($id);
            try {

                $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->setOrder('entity_id', 'ASC');
                if($subjects->count()){
                    $i=10;
                    foreach ($subjects as $sub) {
                        $hour = (float)$sub->getSubjectHour();
                        $title = $sub->getSubjectName();

                        $code = Mage::helper('bs_traininglist')->getShortcode($title);

                        //save position
                        $sub->setSubjectOrder($i)->setSubjectShortcode($code)->save();



                        $i += 10;
                    }

                }

                $this->getResponse()->setRedirect(
                    $this->getUrl('*/traininglist_curriculum/edit', array('id' => $id))
                );

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('The training curriculum has been updated!')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }

    public function massUpdatepositionAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('update_position');
                $param = trim($param);



                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);

                    $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->setOrder('entity_id', 'ASC');
                    if($subjects->count()){
                        $i=10;
                        foreach ($subjects as $sub) {
                            $hour = (float)$sub->getSubjectHour();
                            $title = $sub->getSubjectName();

                            $code = Mage::helper('bs_traininglist')->getShortcode($title);

                            //save position
                            $sub->setSubjectOrder($i)->setSubjectShortcode($code)->save();



                            $i += 10;
                        }

                    }


                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateDocwiseAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('docwise');
                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);

                    $curriculum
                        ->setCDocwiseCustom($param)
                        ->setCDocwise(true)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function updateDocPositionAction(){
        if ($id = $this->getRequest()->getParam('id')) {
            try {

                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $readConnection = $resource->getConnection('core_read');
                $tableCuDoc = $resource->getTableName('bs_curriculumdoc/curriculumdoc_curriculum');

                $docs = $writeConnection->query("UPDATE {$tableCuDoc} SET `position`=99 WHERE curriculum_id = {$id}");



                $this->getResponse()->setRedirect(
                    $this->getUrl('*/traininglist_curriculum/edit', array('id' => $id))
                );

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_traininglist')->__('The positions have been updated!')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }

    public function massUpdateRevisionAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('revision');

        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCRev($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    public function massApprovedDateAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        //$flag = (int)$this->getRequest()->getParam('revision');

        try {

            $date = $this->getRequest()->getParam('c_approved_date');
            $dates = array('input_date'=>$date);

            $dates = $this->_filterDates($dates,array('input_date'));

            $date = $dates['input_date'];

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCApprovedDate($date)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    public function massManualDateAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        //$flag = (int)$this->getRequest()->getParam('revision');

        try {

            $date = $this->getRequest()->getParam('c_manual_date');
            $dates = array('input_date'=>$date);

            $dates = $this->_filterDates($dates,array('input_date'));

            $date = $dates['input_date'];

            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCManualDate($date)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    public function massManualRevisionAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('c_manual_rev');

        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCManualRev($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    public function massUpdateNewstaffAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('c_new_staff');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCNewStaff($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateMandatoryAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('c_mandatory');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCMandatory($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateRecurrentAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('c_recurrent');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setCRecurrent($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateJobspecificAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('job_specific');

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $curriculum
                        ->setJobSpecific($param)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massGetInfoAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {

                $result = '<table>';

                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $name = $curriculum->getCName();
                    $code = $curriculum->getCCode();
                    $result .= '<tr><td>'.$name.'</td><td>'.$code.'</td></tr>';

                }
                $result .= '</table>';
                $this->_getSession()->addSuccess(
                    $this->__($result)
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massMakeLowercaseAction()
    {
        $curriculumIds = $this->getRequest()->getParam('curriculum');
        if (!is_array($curriculumIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traininglist')->__('Please select training curriculum.')
            );
        } else {
            try {
                foreach ($curriculumIds as $curriculumId) {
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                    $name = $curriculum->getCName();
                    $name = Mage::helper('bs_traininglist')->lowercase($name, true);
                    $curriculum
                        ->setCName($name)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d training curriculum were successfully updated.', count($curriculumIds))
                );



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traininglist')->__('There was an error updating training curriculum.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/curriculum/curriculum');
    }

    /**
     * Export curriculums in CSV format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName = 'curriculums.csv';
        $content = $this->getLayout()->createBlock('bs_traininglist/adminhtml_curriculum_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export training curriculum in Excel format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName = 'curriculum.xls';
        $content = $this->getLayout()->createBlock('bs_traininglist/adminhtml_curriculum_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export training curriculum in XML format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName = 'curriculum.xml';
        $content = $this->getLayout()->createBlock('bs_traininglist/adminhtml_curriculum_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function wysiwygAction()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'bs_traininglist/adminhtml_traininglist_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id' => $storeId,
                'store_media_url' => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * mass Applicable for A/C change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumAircraftAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_aircraft');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumAircraft($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * mass Course Purpose change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumPurposeAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_purpose');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumPurpose($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * mass Frequency change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumFrequencyAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_frequency');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumFrequency($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * mass Remedial? change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumRemedialAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_remedial');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumRemedial($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * mass Prerequisite Docwise change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumDocwiseAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_docwise');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumDocwise($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * mass Course Revision change
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCurriculumRevisionAction()
    {
        $curriculumIds = (array)$this->getRequest()->getParam('curriculum');
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $flag = (int)$this->getRequest()->getParam('flag_c_revision');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($curriculumIds as $curriculumId) {
                $curriculum = Mage::getSingleton('bs_traininglist/curriculum')
                    ->setStoreId($storeId)
                    ->load($curriculumId);
                $curriculum->setCurriculumRevision($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('bs_traininglist')->__('Total of %d record(s) have been updated.', count($curriculumIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_traininglist')->__('An error occurred while updating the training curriculum.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.product')
            ->setCurriculumProducts($this->getRequest()->getPost('curriculum_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsgridAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.product')
            ->setCurriculumProducts($this->getRequest()->getPost('curriculum_products', null));
        $this->renderLayout();
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesJsonAction()
    {
        $this->_initCurriculum();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('bs_traininglist/adminhtml_curriculum_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }


}
