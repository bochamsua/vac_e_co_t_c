<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy admin controller
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Adminhtml_Subjectcopy_SubjectcopyController extends BS_SubjectCopy_Controller_Adminhtml_SubjectCopy
{
    /**
     * init the subject copy
     *
     * @access protected
     * @return BS_SubjectCopy_Model_Subjectcopy
     */
    protected function _initSubjectcopy()
    {
        $subjectcopyId  = (int) $this->getRequest()->getParam('id');
        $subjectcopy    = Mage::getModel('bs_subjectcopy/subjectcopy');
        if ($subjectcopyId) {
            $subjectcopy->load($subjectcopyId);
        }
        Mage::register('current_subjectcopy', $subjectcopy);
        return $subjectcopy;
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
        $this->_title(Mage::helper('bs_subjectcopy')->__('Subject Copy'))
             ->_title(Mage::helper('bs_subjectcopy')->__('Subject Copies'));
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
     * edit subject copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $subjectcopyId    = $this->getRequest()->getParam('id');
        $subjectcopy      = $this->_initSubjectcopy();
        if ($subjectcopyId && !$subjectcopy->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_subjectcopy')->__('This subject copy no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getSubjectcopyData(true);
        if (!empty($data)) {
            $subjectcopy->setData($data);
        }
        Mage::register('subjectcopy_data', $subjectcopy);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_subjectcopy')->__('Subject Copy'))
             ->_title(Mage::helper('bs_subjectcopy')->__('Subject Copies'));
        if ($subjectcopy->getId()) {
            $this->_title($subjectcopy->getCFrom());
        } else {
            $this->_title(Mage::helper('bs_subjectcopy')->__('Add subject copy'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new subject copy action
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
     * save subject copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('subjectcopy')) {
            try {
                $subjectcopy = $this->_initSubjectcopy();
                $subjectcopy->addData($data);

                //Now importing
                $cuFromId = $data['c_from'];
                $cuToId = $data['c_to'];
                $includeSub = $data['include_sub'];
                $replace = $data['replace_all'];

                $cuTo = Mage::getModel('bs_traininglist/curriculum')->load($cuToId);
                $cuCode = $cuTo->getCCode();


                //If we have content to import, we ignore the copy
                if($data['import'] != ''){
                    $import = explode("\r\n", $data['import']);
                    $k=10;
                    if($replace){
                        $removeSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$cuToId);
                        foreach ($removeSubjects as $rs) {
                            $id = $rs->getId();
                            $rs->delete();
                            $removeSubcons = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $id);
                            $removeSubcons->walk('delete');
                        }

                    }else {
                        //need to count existing ones
                        $removeSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$cuToId);
                        if($removeSubjects->count()){
                            $k = $removeSubjects->count() * 10;
                            $k = $k +10;
                        }

                    }

                    foreach ($import as $item) {
                        $item = explode(",", $item);
                        if(count($item) == 3){
                            $title = trim($item[0]);
                            $lvl = intval(trim($item[1]));
                            $hour = floatval(trim($item[2]));

                            $t = $k/10;

                            $code = $cuCode.'-'.$t.Mage::helper('bs_traininglist')->generateRandomString();
                            $shortcode = $this->getShortcode($title);

                            $subject = Mage::getModel('bs_subject/subject');
                            $subject->setCurriculumId($cuToId)
                                ->setSubjectName($title)
                                ->setSubjectCode($code)
                                ->setSubjectLevel($lvl)
                                ->setSubjectHour($hour)
                                ->setSubjectShortcode($shortcode)
                                ->setSubjectOrder($k)
                                ->save();

                            $k += 10;

                        }
                    }


                }else {
                    $fromSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$cuFromId)->setOrder('entity_id', 'ASC');
                    if($fromSubjects->count()){
                        $i=1;

                        if($replace){
                            $removeSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$cuToId);
                            foreach ($removeSubjects as $rs) {
                                $id = $rs->getId();
                                $rs->delete();
                                $removeSubcons = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $id);
                                $removeSubcons->walk('delete');
                            }

                        }else {
                            //need to count existing ones
                            $removeSubjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id',$cuToId);
                            if($removeSubjects->count()){
                                $i = $removeSubjects->count();
                            }

                        }


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

                            $code = $cuCode.'-'.$i.Mage::helper('bs_traininglist')->generateRandomString();

                            $subject = Mage::getModel('bs_subject/subject');
                            $subject->setCurriculumId($cuToId)
                                ->setSubjectName($name)
                                ->setSubjectCode($code)
                                ->setSubjectLevel($level)
                                ->setSubjectHour($hour)
                                ->setSubjectContent($content)
                                ->setSubjectShortcode($shortcode)
                                ->setSubjectExam($exam)
                                ->setSubjectOnlycontent($onlyConent)
                                ->setSubjectOrder($order)
                                ->save();

                            $newId = $subject->getId();



                            if($includeSub){
                                //get all subject contents
                                $subcontent = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $oldId)->setOrder('subcon_order','ASC')->setOrder('entity_id', 'ASC');
                                if($subcontent->count()){
                                    $j=10;
                                    foreach ($subcontent as $subcon) {
                                        $sc = Mage::getModel('bs_subject/subjectcontent');
                                        $sc->setSubjectId($newId)
                                            ->setSubconTitle($subcon->getSubconTitle())
                                            ->setSubconCode($code.'-'.$j.Mage::helper('bs_traininglist')->generateRandomString(3))
                                            ->setSubconLevel($subcon->getSubconLevel())
                                            ->setSubconHour($subcon->getSubconHour())
                                            ->setSubconContent($subcon->getSubconContent())
                                            ->setSubconOrder($subcon->getSubconOrder())
                                            ->save();
                                        ;

                                        $j += 10;
                                    }

                                }
                            }




                            $i++;
                        }

                    }
                }








                $subjectcopy->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.subject_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subjectcopy')->__('Subject Copy was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $subjectcopy->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setSubjectcopyData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was a problem saving the subject copy.')
                );
                Mage::getSingleton('adminhtml/session')->setSubjectcopyData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subjectcopy')->__('Unable to find subject copy to save.')
        );
        $this->_redirect('*/*/');
    }

    public function getShortcode($title){
        $title = "moke".$title;
        $title = strtolower($title);

        $code = '';

        if(strpos($title, "english")){
            $code = 'TAE';
        }

        for($i=20; $i>1; $i--){
            if(strpos($title, "module ".$i)){
                if(strpos($title, "airframe")){
                    $code = 'M'.$i.'-A'; break;
                }elseif(strpos($title, "electrical")){
                    $code = 'M'.$i.'-E'; break;
                }elseif(strpos($title, "avionic")){
                    $code = 'M'.$i.'-AV'; break;
                }elseif(strpos($title, "- systems part")){
                    $code = 'M'.$i.'-S'; break;
                }else {
                    $code = 'M'.$i; break;
                }

            }
        }

        for($i=80; $i>=0; $i--){
            if($i< 10){
                $i = '0'.$i;
            }
            if(strpos($title, "ata ".$i)){
                $code = 'ATA'.$i; break;

            }elseif(strpos($title, "document ".$i)){
                $code = 'DOC'; break;
            }
        }

        if($code != ''){
            return $code;
        }

        return '';
    }
    /**
     * delete subject copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $subjectcopy = Mage::getModel('bs_subjectcopy/subjectcopy');
                $subjectcopy->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subjectcopy')->__('Subject Copy was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was an error deleting subject copy.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subjectcopy')->__('Could not find subject copy to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete subject copy - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $subjectcopyIds = $this->getRequest()->getParam('subjectcopy');
        if (!is_array($subjectcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subjectcopy')->__('Please select subject copies to delete.')
            );
        } else {
            try {
                foreach ($subjectcopyIds as $subjectcopyId) {
                    $subjectcopy = Mage::getModel('bs_subjectcopy/subjectcopy');
                    $subjectcopy->setId($subjectcopyId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subjectcopy')->__('Total of %d subject copies were successfully deleted.', count($subjectcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was an error deleting subject copies.')
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
        $subjectcopyIds = $this->getRequest()->getParam('subjectcopy');
        if (!is_array($subjectcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subjectcopy')->__('Please select subject copies.')
            );
        } else {
            try {
                foreach ($subjectcopyIds as $subjectcopyId) {
                $subjectcopy = Mage::getSingleton('bs_subjectcopy/subjectcopy')->load($subjectcopyId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject copies were successfully updated.', count($subjectcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was an error updating subject copies.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Include Subcontent change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massIncludeSubAction()
    {
        $subjectcopyIds = $this->getRequest()->getParam('subjectcopy');
        if (!is_array($subjectcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subjectcopy')->__('Please select subject copies.')
            );
        } else {
            try {
                foreach ($subjectcopyIds as $subjectcopyId) {
                $subjectcopy = Mage::getSingleton('bs_subjectcopy/subjectcopy')->load($subjectcopyId)
                    ->setIncludeSub($this->getRequest()->getParam('flag_include_sub'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject copies were successfully updated.', count($subjectcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was an error updating subject copies.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Replace All Existing Subjects change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massReplaceAllAction()
    {
        $subjectcopyIds = $this->getRequest()->getParam('subjectcopy');
        if (!is_array($subjectcopyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subjectcopy')->__('Please select subject copies.')
            );
        } else {
            try {
                foreach ($subjectcopyIds as $subjectcopyId) {
                $subjectcopy = Mage::getSingleton('bs_subjectcopy/subjectcopy')->load($subjectcopyId)
                    ->setReplaceAll($this->getRequest()->getParam('flag_replace_all'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject copies were successfully updated.', count($subjectcopyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subjectcopy')->__('There was an error updating subject copies.')
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
        $fileName   = 'subjectcopy.csv';
        $content    = $this->getLayout()->createBlock('bs_subjectcopy/adminhtml_subjectcopy_grid')
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
        $fileName   = 'subjectcopy.xls';
        $content    = $this->getLayout()->createBlock('bs_subjectcopy/adminhtml_subjectcopy_grid')
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
        $fileName   = 'subjectcopy.xml';
        $content    = $this->getLayout()->createBlock('bs_subjectcopy/adminhtml_subjectcopy_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/subject/subjectcopy');
    }
}
