<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin controller
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Adminhtml_Bank_SubjectController extends BS_Bank_Controller_Adminhtml_Bank
{
    /**
     * init the subject
     *
     * @access protected
     * @return BS_Bank_Model_Subject
     */
    protected function _initSubject()
    {
        $subjectId  = (int) $this->getRequest()->getParam('id');
        $subject    = Mage::getModel('bs_bank/subject');
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
        $this->_title(Mage::helper('bs_bank')->__('Questions Bank'))
             ->_title(Mage::helper('bs_bank')->__('Subject'));
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
                Mage::helper('bs_bank')->__('This subject no longer exists.')
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
        $this->_title(Mage::helper('bs_bank')->__('Questions Bank'))
             ->_title(Mage::helper('bs_bank')->__('Subject'));
        if ($subject->getId()) {
            $this->_title($subject->getSubjectName());
        } else {
            $this->_title(Mage::helper('bs_bank')->__('Add subject'));
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
                $subject->addData($data);
                $subject->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_bank')->__('Subject was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $subject->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setSubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_bank')->__('There was a problem saving the subject.')
                );
                Mage::getSingleton('adminhtml/session')->setSubjectData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_bank')->__('Unable to find subject to save.')
        );
        $this->_redirect('*/*/');
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
                $subject = Mage::getModel('bs_bank/subject');
                $subject->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_bank')->__('Subject was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_bank')->__('There was an error deleting subject.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_bank')->__('Could not find subject to delete.')
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
                Mage::helper('bs_bank')->__('Please select subject to delete.')
            );
        } else {
            try {
                foreach ($subjectIds as $subjectId) {
                    $subject = Mage::getModel('bs_bank/subject');
                    $subject->setId($subjectId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_bank')->__('Total of %d subject were successfully deleted.', count($subjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_bank')->__('There was an error deleting subject.')
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
        $subjectIds = $this->getRequest()->getParam('subject');
        if (!is_array($subjectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_bank')->__('Please select subject.')
            );
        } else {
            try {
                foreach ($subjectIds as $subjectId) {
                $subject = Mage::getSingleton('bs_bank/subject')->load($subjectId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d subject were successfully updated.', count($subjectIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_bank')->__('There was an error updating subject.')
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
        $content    = $this->getLayout()->createBlock('bs_bank/adminhtml_subject_grid')
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
        $content    = $this->getLayout()->createBlock('bs_bank/adminhtml_subject_grid')
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
        $content    = $this->getLayout()->createBlock('bs_bank/adminhtml_subject_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_bank/subject');
    }
}
