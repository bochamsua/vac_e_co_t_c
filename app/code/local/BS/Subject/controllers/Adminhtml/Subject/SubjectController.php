<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin controller
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Adminhtml_Subject_SubjectController extends BS_Subject_Controller_Adminhtml_Subject
{
    /**
     * init the subject
     *
     * @access protected
     * @return BS_Subject_Model_Subject
     */
    protected function _initSubject()
    {
        $subjectId  = (int) $this->getRequest()->getParam('id');
        $subject    = Mage::getModel('bs_subject/subject');
        if ($subjectId) {
            $subject->load($subjectId);
        }
        Mage::register('current_subject', $subject);
        return $subject;
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
        $this->_title(Mage::helper('bs_subject')->__('Subject'))
             ->_title(Mage::helper('bs_subject')->__('Subjects'));
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
     * edit subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $subjectId    = $this->getRequest()->getParam('id');
        $subject      = $this->_initSubject();
        if ($subjectId && !$subject->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_subject')->__('This subject no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getSubjectData(true);
        if (!empty($data)) {
            $subject->setData($data);
        }
        Mage::register('subject_data', $subject);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_subject')->__('Subject'))
             ->_title(Mage::helper('bs_subject')->__('Subjects'));
        if ($subject->getId()) {
            $this->_title($subject->getSubjectName());
        } else {
            $this->_title(Mage::helper('bs_subject')->__('Add subject'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new subject action
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
     * save subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('subject')) {
            try {
                $subject = $this->_initSubject();

                $increment = 1;
                //get all subjects that belong to this curriculum


                $curriculumId = $data['curriculum_id'];
                $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                $curriculumCode = $curriculum->getCCode();

                $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$curriculumId);
                if(count($subjects)){
                    $increment = count($subjects) + 1;
                }

                $code = $data['subject_code'];
                if($code == ''){//new subject doesnt have the value in this field, we will add this here
                    $code = $curriculumCode.'-'.$increment.Mage::helper('bs_traininglist')->generateRandomString();
                    $data['subject_code'] = $code;
                }




                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $i=$increment*10;
                    foreach ($import as $line) {
                        if(strpos($line, "--")){
                            $item = explode("--", $line);
                        }else {
                            $item = explode("\t", $line);
                        }

                        $count = count($item);
                        $name = false;
                        $level = false;
                        $hour = false;
                        $exam = false;
                        $order = false;
                        if($count == 2){//Name--hour

                            $name = trim($item[0]);
                            $hour = (float)trim($item[1]);

                        }elseif($count == 3){//Name--hour--exam

                            $name = trim($item[0]);
                            $hour = (float)trim($item[1]);
                            $exam = (int)trim($item[2]);
                        }elseif($count == 5){//Name--level--hour--training chapter--require exam

                            $name = trim($item[0]);
                            $level= (int)trim($item[1]);
                            $hour = (float)trim($item[2]);
                            $training = (int)trim($item[3]);
                            $exam = (int)trim($item[4]);

                        }elseif($count == 6){//Name--level--hour--training chapter--require exam--order

                            $name = trim($item[0]);
                            $level= (int)trim($item[1]);
                            $hour = (float)trim($item[2]);
                            $training = (int)trim($item[3]);
                            $exam = (int)trim($item[4]);
                            $order = (int)trim($item[5]);
                        }

                        if($name){
                            $subs = Mage::getModel('bs_subject/subject');
                            $subs->setSubjectName($name);
                            $subs->setCurriculumId($curriculumId);
                            $subscode = $curriculumCode.'-'.$i.Mage::helper('bs_traininglist')->generateRandomString();
                            $subs->setSubjectCode($subscode);
                            if($level>0){
                                $subs->setSubjectLevel($level);
                            }
                            if($hour>0){
                                $subs->setSubjectHour($hour);
                            }
                            if($exam==1){
                                $subs->setRequireExam($exam);
                            }
                            if($order>0){
                                $subs->setSubjectOrder($order);
                            }else {
                                $subs->setSubjectOrder($i);
                            }
                            $subs->setStatus($training);
                            $subs->save();

                            $i += 10;

                        }

                    }

                }else {

                    //Auto increase order
                    if((int)$data['subject_order'] == 0){
                        $data['subject_order'] = $increment * 10;
                    }


                    if($data['subject_ws']){
                        $ws = Mage::getModel('bs_worksheet/worksheet')->getCollection()->addCurriculumFilter($curriculum);
                        if($ws->getFirstItem()->getId()){
                            $worksheet = $ws->getFirstItem();
                            $wsName = $worksheet->getWsName();
                            $wsCode = $worksheet->getWsCode();

                            $content = "Practical Training I.A.W Training Worksheet: {$wsName} (as revised)";

                            $data['subject_content'] = $content;
                        }
                    }
                    $subject->addData($data);
                    $subject->save();
                }

                //save subcon order
                $positions = $this->getRequest()->getPost('position');
                $names = $this->getRequest()->getPost('name');
                foreach ($positions as $key => $value) {
                    $subcon = Mage::getModel('bs_subject/subjectcontent')->load($key);
                    if(isset($names[$key])){
                        $subcon->setSubconTitle($names[$key]);
                    }
                    $subcon->setSubconOrder($value)->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.subject_gridJsObject.reload(); window.close()</script>';
                }

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_subject')->__('Subject was saved. %s', $add)
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $subject->getId()));
                    return;
                }
                $this->_redirect('*/traininglist_curriculum/edit', array('id'=>$subject->getCurriculumId(), 'back'=>'edit', 'tab'=>'curriculum_info_tabs_subjects'));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setSubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was a problem saving the subject.')
                );
                Mage::getSingleton('adminhtml/session')->setSubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subject')->__('Unable to find subject to save.')
        );
        $this->_redirect('*/*/');
    }

    public function clearAction()
    {
        try {

            $cId = $this->getRequest()->getParam('curriculum_id');

            $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $cId);
            if($subjects->count()){
                foreach ($subjects as $subject) {

                    $subcons = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $subject->getId());
                    if($subcons->count()){
                        $subcons->walk('delete');

                    }
                    $subject->delete();
                }
                //$shedules->walk('delete');
            }

            $add = '';
            if($this->getRequest()->getParam('popup')){
                $add = '<script>window.opener.subject_gridJsObject.reload(); window.close()</script>';
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_register')->__('All subjects were successfully deleted. %s', $add)
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/traininglist_curriculum/edit', array('id'=>$cId, 'tab'=>'curriculum_info_tabs_subjects'));
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            $this->_redirect('*/traininglist_curriculum/edit', array('id'=>$cId, 'tab'=>'curriculum_info_tabs_subjects'));
            return;
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('There was a problem deleting subjects.')
            );
            $this->_redirect('*/traininglist_curriculum/edit', array('id'=>$cId, 'tab'=>'curriculum_info_tabs_subjects'));
            return;
        }

        $this->_redirect('*/traininglist_curriculum/edit', array('id'=>$cId, 'tab'=>'curriculum_info_tabs_subjects'));
    }

    public function clearSubAction()
    {
        try {

            $sId = $this->getRequest()->getParam('subject_id');

            $subcons = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sId);
            if($subcons->count()){
                $subcons->walk('delete');
            }


            $add = '';
            if($this->getRequest()->getParam('popup')){
                $add = '<script>window.opener.subjectcontentGridJsObject.reload(); window.close()</script>';
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_register')->__('All subject contents were successfully deleted. %s', $add)
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/subject_subject/edit', array('id'=>$sId, 'tab'=>'subject_tabs_subcontent'));
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            $this->_redirect('*/subject_subject/edit', array('id'=>$sId, 'tab'=>'subject_tabs_subcontent'));
            return;
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('There was a problem deleting subject contents.')
            );
            $this->_redirect('*/subject_subject/edit', array('id'=>$sId, 'tab'=>'subject_tabs_subcontent'));
            return;
        }

        $this->_redirect('*/subject_subject/edit', array('id'=>$sId, 'tab'=>'subject_tabs_subcontent'));
    }

    /**
     * delete subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $subject = Mage::getModel('bs_subject/subject');
                $subject->setId($this->getRequest()->getParam('id'))->delete();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.subject_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subject')->__('Subject was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error deleting subject.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subject')->__('Could not find subject to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete subject - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subjects to delete.')
            );
        } else {
            try {
                foreach ($subjectIds as $subjectId) {
                    $subject = Mage::getModel('bs_subject/subject');
                    $subject->setId($subjectId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subject')->__('Total of %d subjects were successfully deleted.', count($subjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error deleting subjects.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplaceTitleAction()
    {
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subject.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_title');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($subjectIds as $subjectId) {
                        $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                        $name = $subject->getSubjectName();
                        $name = str_replace($search, $replace, $name);
                        $subject
                            ->setSubjectName($name)
                            ->setIsMassupdate(true)
                            ->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d subjects were successfully updated.', count($subjectIds))
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
                    Mage::helper('bs_subject')->__('There was an error updating subject.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplaceContentAction()
    {
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subject.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_content');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($subjectIds as $subjectId) {
                        $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                        $name = $subject->getSubjectContent();
                        $name = str_replace($search, $replace, $name);
                        $subject
                            ->setSubjectContent($name)
                            ->setIsMassupdate(true)
                            ->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d subjects were successfully updated.', count($subjectIds))
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
                    Mage::helper('bs_subject')->__('There was an error updating subject.')
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
    public function massExamAction()
    {
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subjects.')
            );
        } else {
            try {
                foreach ($subjectIds as $subjectId) {
                $subject = Mage::getModel('bs_subject/subject')->load($subjectId)
                            ->setRequireExam($this->getRequest()->getParam('require_exam'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subjects were successfully updated.', count($subjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error updating subjects.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subjects.')
            );
        } else {
            try {
                foreach ($subjectIds as $subjectId) {
                    $subject = Mage::getSingleton('bs_subject/subject')->load($subjectId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subjects were successfully updated.', count($subjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error updating subjects.')
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
        $fileName   = 'subject.csv';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subject_grid')
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
        $fileName   = 'subject.xls';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subject_grid')
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
        $fileName   = 'subject.xml';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subject_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsAction()
    {
        $this->_initSubject();
        $this->loadLayout();
        $this->getLayout()->getBlock('subject.edit.tab.curriculum');
        $this->renderLayout();
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsgridAction()
    {
        $this->_initSubject();
        $this->loadLayout();
        $this->getLayout()->getBlock('subject.edit.tab.curriculum');
        $this->renderLayout();
    }

    public function subjectcontentsAction()
    {
        $this->_initSubject();
        $this->loadLayout();
        $this->getLayout()->getBlock('subject.edit.tab.subjectcontent');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }

    /**
     * course schedule grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function subjectcontentsGridAction()
    {
        $this->_initSubject();
        $this->loadLayout();
        $this->getLayout()->getBlock('subject.edit.tab.subjectcontent');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/subject');
    }

}
