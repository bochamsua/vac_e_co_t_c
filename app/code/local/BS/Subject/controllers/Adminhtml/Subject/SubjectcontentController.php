<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content admin controller
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Adminhtml_Subject_SubjectcontentController extends BS_Subject_Controller_Adminhtml_Subject
{
    /**
     * init the subject content
     *
     * @access protected
     * @return BS_Subject_Model_Subjectcontent
     */
    protected function _initSubjectcontent()
    {
        $subjectcontentId  = (int) $this->getRequest()->getParam('id');
        $subjectcontent    = Mage::getModel('bs_subject/subjectcontent');
        if ($subjectcontentId) {
            $subjectcontent->load($subjectcontentId);
        }
        Mage::register('current_subjectcontent', $subjectcontent);
        return $subjectcontent;
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
             ->_title(Mage::helper('bs_subject')->__('Subject Contents'));
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
     * edit subject content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $subjectcontentId    = $this->getRequest()->getParam('id');
        $subjectcontent      = $this->_initSubjectcontent();
        if ($subjectcontentId && !$subjectcontent->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_subject')->__('This subject content no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getSubjectcontentData(true);
        if (!empty($data)) {
            $subjectcontent->setData($data);
        }
        Mage::register('subjectcontent_data', $subjectcontent);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_subject')->__('Subject'))
             ->_title(Mage::helper('bs_subject')->__('Subject Contents'));
        if ($subjectcontent->getId()) {
            $this->_title($subjectcontent->getSubconTitle());
        } else {
            $this->_title(Mage::helper('bs_subject')->__('Add subject content'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new subject content action
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
     * save subject content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('subjectcontent')) {
            try {
                $subjectcontent = $this->_initSubjectcontent();

                $code = $data['subcon_code'];
                $subjectId = $data['subject_id'];
                $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                $subjectCode = $subject->getSubjectCode();

                $increment = 1;

                $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id',$subjectId);
                if(count($subjectContents)){
                    $increment = count($subjectContents) + 1;
                }

                if($code == ''){//new subject doesnt have the value in this field, we will add this here


                    $code = $subjectCode.'-'.$increment.Mage::helper('bs_traininglist')->generateRandomString(4);
                    $data['subcon_code'] = $code;

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

                        if($count == 3){//Name--hour

                            $name = trim($item[0]);
                            $level= (int)trim($item[1]);
                            $hour = (float)trim($item[2]);

                        }

                        if($name){
                            $subs = Mage::getModel('bs_subject/subjectcontent');
                            $subs->setSubconTitle($name);
                            $subs->setSubjectId($subjectId);
                            $subscode = $subjectCode.'-'.$i.Mage::helper('bs_traininglist')->generateRandomString();
                            $subs->setSubconCode($subscode);
                            if($level>0){
                                $subs->setSubconLevel($level);
                            }
                            if($hour>0){
                                $subs->setSubconHour($hour);
                            }

                            $subs->setSubconOrder($i);

                            $subs->save();

                            $i += 10;

                        }






                    }

                }else {

                    $subjectcontent->addData($data);
                    $subjectcontent->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.subjectcontentGridJsObject.reload();window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subject')->__('Subject Content was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $subjectcontent->getId()));
                    return;
                }
                $this->_redirect('*/subject_subject/edit', array('id'=>$subjectcontent->getSubjectId(), 'tab'=>'subject_info_tabs_subcontent'));
                //$this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setSubjectcontentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was a problem saving the subject content.')
                );
                Mage::getSingleton('adminhtml/session')->setSubjectcontentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subject')->__('Unable to find subject content to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete subject content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $subjectcontent = Mage::getModel('bs_subject/subjectcontent');
                $subjectcontent->setId($this->getRequest()->getParam('id'))->delete();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.subjectcontentGridJsObject.reload();window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subject')->__('Subject Content was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error deleting subject content.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_subject')->__('Could not find subject content to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete subject content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $subjectcontentIds = $this->getRequest()->getParam('subjectcontent');
        if (!is_array($subjectcontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subject contents to delete.')
            );
        } else {
            try {
                foreach ($subjectcontentIds as $subjectcontentId) {
                    $subjectcontent = Mage::getModel('bs_subject/subjectcontent');
                    $subjectcontent->setId($subjectcontentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_subject')->__('Total of %d subject contents were successfully deleted.', count($subjectcontentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error deleting subject contents.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplacetitleAction()
    {
        $subjectcontentIds = $this->getRequest()->getParam('subjectcontent');
        if (!is_array($subjectcontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select training curriculum.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_title');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($subjectcontentIds as $sId) {
                        $subcon = Mage::getModel('bs_subject/subjectcontent')->load($sId);
                        $name = $subcon->getSubconTitle();
                        $name = str_replace($search, $replace, $name);
                        $subcon
                            ->setSubconTitle($name)
                            ->setIsMassupdate(true)
                            ->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d subjects were successfully updated.', count($subjectcontentIds))
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

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $subjectcontentIds = $this->getRequest()->getParam('subjectcontent');
        if (!is_array($subjectcontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subject contents.')
            );
        } else {
            try {
                foreach ($subjectcontentIds as $subjectcontentId) {
                $subjectcontent = Mage::getSingleton('bs_subject/subjectcontent')->load($subjectcontentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject contents were successfully updated.', count($subjectcontentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error updating subject contents.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass subject change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massSubjectIdAction()
    {
        $subjectcontentIds = $this->getRequest()->getParam('subjectcontent');
        if (!is_array($subjectcontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_subject')->__('Please select subject contents.')
            );
        } else {
            try {
                foreach ($subjectcontentIds as $subjectcontentId) {
                $subjectcontent = Mage::getSingleton('bs_subject/subjectcontent')->load($subjectcontentId)
                    ->setSubjectId($this->getRequest()->getParam('flag_subject_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject contents were successfully updated.', count($subjectcontentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_subject')->__('There was an error updating subject contents.')
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
        $fileName   = 'subjectcontent.csv';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subjectcontent_grid')
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
        $fileName   = 'subjectcontent.xls';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subjectcontent_grid')
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
        $fileName   = 'subjectcontent.xml';
        $content    = $this->getLayout()->createBlock('bs_subject/adminhtml_subjectcontent_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/subject/subjectcontent');
    }


}
