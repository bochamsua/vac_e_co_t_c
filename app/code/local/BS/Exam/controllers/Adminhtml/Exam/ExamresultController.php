<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Result admin controller
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Adminhtml_Exam_ExamresultController extends BS_Exam_Controller_Adminhtml_Exam
{
    /**
     * init the exam result
     *
     * @access protected
     * @return BS_Exam_Model_Examresult
     */
    protected function _initExamresult()
    {
        $examresultId  = (int) $this->getRequest()->getParam('id');
        $examresult    = Mage::getModel('bs_exam/examresult');
        if ($examresultId) {
            $examresult->load($examresultId);
        }
        Mage::register('current_examresult', $examresult);
        return $examresult;
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
             ->_title(Mage::helper('bs_exam')->__('Exam Results'));
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

    public function fillAction(){
        $courseId = $this->getRequest()->getParam('product_id');
        $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($courseId)->getFirstItem();
        if($curriculum->getId()){
            //First we check for any existing result
            $examresult = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $courseId);
            if($examresult->count()){
                Mage::getSingleton('adminhtml/session')->addNotice(
                    Mage::helper('bs_exam')->__('There are existing results. The operation is interrupted!')
                );

            }else {
                $subjects = Mage::getModel('bs_subject/subject')
                    ->getCollection()
                    ->addFieldToFilter('curriculum_id', $curriculum->getId())
                    ->addFieldToFilter('status', 1)
                    //->addFieldToFilter('subject_exam', 0)
                    ->addFieldToFilter('require_exam', 1)
                    ->setOrder('subject_order', 'ASC')
                    ->setOrder('entity_id', 'ASC')
                ;
                if($subjects->count()){

                    //get all trainees
                    $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($courseId)->setOrder('position', 'ASC');
                    if($trainees->count()){
                        try {
                            foreach ($trainees as $tn) {

                                foreach ($subjects as $sub) {

                                    $exre = Mage::getModel('bs_exam/examresult');
                                    $exre->setCourseId($courseId)->setTraineeId($tn->getId())->setSubjectId($sub->getId());
                                    $exre->save();
                                }
                            }
                            Mage::getSingleton('adminhtml/session')->addSuccess(
                                Mage::helper('bs_exam')->__('Subjects were successfully filled.')
                            );
                        }catch (Exception $e){
                            Mage::getSingleton('adminhtml/session')->addSuccess(
                                $e->getMessage()
                            );
                        }

                    }



                }
            }

        }



        $this->_redirect('*/catalog_product/edit', array('id' => $courseId, 'back'=>'edit', 'tab'=>'product_info_tabs_examresults'));
        return;
    }

    public function clearAction(){
        $courseId = $this->getRequest()->getParam('product_id');

        $examresult = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $courseId);
        if($examresult->count()){

            $examresult->walk('delete');

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_exam')->__('All results have been cleared!')
            );

        }

        $this->_redirect('*/catalog_product/edit', array('id' => $courseId, 'back'=>'edit', 'tab'=>'product_info_tabs_examresults'));
        return;
    }


    public function updateTraineeSubjectsAction(){
        $result = array();

        $currentExamResult = (int)$this->getRequest()->getPost('current_examresult');


        $courseId = $this->getRequest()->getPost('course_id');
        $subjectId = $this->getRequest()->getPost('subject_id');
        $marks = $this->getRequest()->getPost('marks');
        $changeSubject = $this->getRequest()->getPost('change_subject');
        $result['subjects'] = '<option value="">There is no subject found</option>';
        $result['trainees'] = '<tr><td>Please select Mark Times option!</td></tr>';

        $mark = '';
        if($marks == 1){
            $mark = 'first_mark';
        }elseif ($marks == 2){
            $mark = 'second_mark';
        }elseif ($marks == 3){
            $mark = 'third_mark';
        }


        if($courseId){
            $curriculumId = '';


            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
            $curriculums->addProductFilter($courseId);

            if($cu = $curriculums->getFirstItem()){
                $curriculumId = $cu->getId();

                $subjects = Mage::getResourceModel('bs_subject/subject_collection')->addFieldToFilter('curriculum_id',$curriculumId)->addFieldToFilter('status', 1);
                if($subjects->count()){
                    $text = '';
                    foreach ($subjects as $sub) {

                        if($changeSubject == 'no'){
                            $additional = '';
                            if($sub->getId() == $subjectId){
                                $additional = 'selected="selected"';
                            }
                            $text  .= '<option '.$additional.' value="'.$sub->getId().'">'.$sub->getSubjectName().'</option>';
                        }else {
                            $text  .= '<option value="'.$sub->getId().'">'.$sub->getSubjectName().'</option>';
                        }


                    }
                    $result['subjects'] = $text;
                }
            }




            $trainees = Mage::getResourceModel('bs_trainee/trainee_collection')
                ->addAttributeToSelect('*')
                ->addProductFilter($courseId);
            if($currentExamResult > 0){
                $current = Mage::getModel('bs_exam/examresult')->load($currentExamResult);
                $trainees->addAttributeToFilter('entity_id', $current->getTraineeId());
            }
            //if we get only re-taking trainees
            if($mark == 'second_mark'){
                $examresults = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $courseId)->addFieldToFilter('subject_id', $subjectId)->addFieldToFilter('first_mark', array('lt'=>75));
                $examTraineeIds = array();
                if(count($examresults)){
                    foreach ($examresults as $exre) {
                        $examTraineeIds[$exre->getTraineeId()] = 1;
                    }
                    $examTraineeIds = array_keys($examTraineeIds);
                    $trainees->addAttributeToFilter('entity_id', array('in'=>$examTraineeIds));

                }
            }


            if($trainees->count()){
                $text = '';
                foreach ($trainees as $tn) {

                    $traineeMark = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $courseId)->addFieldToFilter('subject_id', $subjectId)->addFieldToFilter('trainee_id', $tn->getId())->getFirstItem();

                    $currentMark = '';
                    if($traineeMark->getId()){
                        if($mark == 'first_mark'){
                            $currentMark = $traineeMark->getFirstMark();
                        }elseif ($mark == 'second_mark'){
                            $currentMark = $traineeMark->getSecondMark();
                        }else {
                            $currentMark = $traineeMark->getThirdMark();
                        }
                    }

                    $add = $tn->getTraineeCode();

                    if($tn->getVaecoId() != ''){
                        $add = $tn->getVaecoId();
                    }

                    $text .= '<tr><td class="label"><label for="examresult_note">'.$tn->getTraineeName().' - '.$add.'</label></td> <td class="value"><input name="examresult[trainee]['.$tn->getId().']" value="'.$currentMark.'" class=" input-text" type="text"></td></tr>';
                    //$text  .= '<option value="'.$tn->getId().'">'.$tn->getTraineeName().' - '.$add.'</option>';
                }


                if($mark != ''){
                    $result['trainees'] = $text;
                }

            }



        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * edit exam result - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $examresultId    = $this->getRequest()->getParam('id');
        $examresult      = $this->_initExamresult();
        if ($examresultId && !$examresult->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_exam')->__('This exam result no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getExamresultData(true);
        if (!empty($data)) {
            $examresult->setData($data);
        }
        Mage::register('examresult_data', $examresult);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_exam')->__('Exam'))
             ->_title(Mage::helper('bs_exam')->__('Exam Results'));
        if ($examresult->getId()) {
            $traineeId = $examresult->getTraineeId();
            $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);
            $name = $trainee->getTraineeName();

            $this->_title($name);
        } else {
            $this->_title(Mage::helper('bs_exam')->__('Add exam result'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new exam result action
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
     * save exam result - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('examresult')) {
            try {
                /*$examresult = $this->_initExamresult();
                $examresult->addData($data);
                $examresult->save();*/

                $courseId = $data['course_id'];
                $subjectId = $data['subject_id'];
                $marks = $data['marks'];

                $mark = 'first_mark';
                if($marks == 2){
                    $mark = 'second_mark';
                }elseif ($marks == 3){
                    $mark = 'third_mark';
                }

                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $readConnection = $resource->getConnection('core_read');

                $tableExamResult = $resource->getTableName('bs_exam/examresult');

                //$sql = "DELETE FROM {$tableExamResult} WHERE course_id = {$courseId} AND subject_id = {$subjectId} AND {$mark} != ''";
                //$writeConnection->query($sql);



                $trainees = $data['trainee'];
                if(count($trainees)){
                    foreach ($trainees as $key => $value) {
                        $examresult = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $courseId)->addFieldToFilter('subject_id', $subjectId)->addFieldToFilter('trainee_id', $key)->getFirstItem();

                        if(!$examresult->getId()){
                            $examresult = Mage::getModel('bs_exam/examresult');
                        }
                        $examresult->setCourseId($courseId)->setSubjectId($subjectId)->setTraineeId($key);

                        if($mark == 'first_mark'){
                            $examresult->setFirstMark($value);
                        }elseif ($mark == 'second_mark'){
                            $examresult->setSecondMark($value);
                        }else {
                            $examresult->setThirdMark($value);
                        }

                        $examresult->save();



                    }

                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.examresult_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Exam Result was successfully saved. %s', $add)
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $examresult->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setExamresultData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was a problem saving the exam result.')
                );
                Mage::getSingleton('adminhtml/session')->setExamresultData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Unable to find exam result to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete exam result - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $examresult = Mage::getModel('bs_exam/examresult');
                $examresult->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Exam Result was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting exam result.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_exam')->__('Could not find exam result to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete exam result - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $examresultIds = $this->getRequest()->getParam('examresult');
        if (!is_array($examresultIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select exam results to delete.')
            );
        } else {
            try {
                foreach ($examresultIds as $examresultId) {
                    $examresult = Mage::getModel('bs_exam/examresult');
                    $examresult->setId($examresultId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_exam')->__('Total of %d exam results were successfully deleted.', count($examresultIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error deleting exam results.')
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
        $examresultIds = $this->getRequest()->getParam('examresult');
        if (!is_array($examresultIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('Please select exam results.')
            );
        } else {
            try {
                foreach ($examresultIds as $examresultId) {
                $examresult = Mage::getSingleton('bs_exam/examresult')->load($examresultId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exam results were successfully updated.', count($examresultIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_exam')->__('There was an error updating exam results.')
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
        $fileName   = 'examresult.csv';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_examresult_grid')
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
        $fileName   = 'examresult.xls';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_examresult_grid')
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
        $fileName   = 'examresult.xml';
        $content    = $this->getLayout()->createBlock('bs_exam/adminhtml_examresult_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/examresult');
    }
}
