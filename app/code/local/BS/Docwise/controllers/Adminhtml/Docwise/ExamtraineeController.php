<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Trainee admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_ExamtraineeController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the exam trainee
     *
     * @access protected
     * @return BS_Docwise_Model_Examtrainee
     */
    protected function _initExamtrainee()
    {
        $examtraineeId  = (int) $this->getRequest()->getParam('id');
        $examtrainee    = Mage::getModel('bs_docwise/examtrainee');
        if ($examtraineeId) {
            $examtrainee->load($examtraineeId);
        }
        Mage::register('current_examtrainee', $examtrainee);
        return $examtrainee;
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
             ->_title(Mage::helper('bs_docwise')->__('Exam Trainees'));
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
     * edit exam trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $examtraineeId    = $this->getRequest()->getParam('id');
        $examtrainee      = $this->_initExamtrainee();
        if ($examtraineeId && !$examtrainee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This exam trainee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getExamtraineeData(true);
        if (!empty($data)) {
            $examtrainee->setData($data);
        }
        Mage::register('examtrainee_data', $examtrainee);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Exam Trainees'));
        if ($examtrainee->getId()) {
            $this->_title($examtrainee->getExamId());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add exam trainee'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new exam trainee action
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
     * save exam trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('examtrainee')) {
            try {
                $examtrainee = $this->_initExamtrainee();
                $examtrainee->addData($data);
                $examtrainee->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Exam Trainee was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $examtrainee->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setExamtraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the exam trainee.')
                );
                Mage::getSingleton('adminhtml/session')->setExamtraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find exam trainee to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete exam trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $examtrainee = Mage::getModel('bs_docwise/examtrainee');
                $examtrainee->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Exam Trainee was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting exam trainee.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find exam trainee to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete exam trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $examtraineeIds = $this->getRequest()->getParam('examtrainee');
        if (!is_array($examtraineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exam trainees to delete.')
            );
        } else {
            try {
                foreach ($examtraineeIds as $examtraineeId) {
                    $examtrainee = Mage::getModel('bs_docwise/examtrainee');
                    $examtrainee->setId($examtraineeId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d exam trainees were successfully deleted.', count($examtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting exam trainees.')
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
        $examtraineeIds = $this->getRequest()->getParam('examtrainee');
        if (!is_array($examtraineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select exam trainees.')
            );
        } else {
            try {
                foreach ($examtraineeIds as $examtraineeId) {
                $examtrainee = Mage::getSingleton('bs_docwise/examtrainee')->load($examtraineeId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d exam trainees were successfully updated.', count($examtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating exam trainees.')
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
        $fileName   = 'examtrainee.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_examtrainee_grid')
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
        $fileName   = 'examtrainee.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_examtrainee_grid')
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
        $fileName   = 'examtrainee.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_examtrainee_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/examtrainee');
    }
}
