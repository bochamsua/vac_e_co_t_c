<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Trainee Feedback admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_TfeedbackController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the trainee feedback
     *
     * @access protected
     * @return BS_KST_Model_Tfeedback
     */
    protected function _initTfeedback()
    {
        $tfeedbackId  = (int) $this->getRequest()->getParam('id');
        $tfeedback    = Mage::getModel('bs_kst/tfeedback');
        if ($tfeedbackId) {
            $tfeedback->load($tfeedbackId);
        }
        Mage::register('current_tfeedback', $tfeedback);
        return $tfeedback;
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
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Trainee Feedbacks'));
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
     * edit trainee feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $tfeedbackId    = $this->getRequest()->getParam('id');
        $tfeedback      = $this->_initTfeedback();
        if ($tfeedbackId && !$tfeedback->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This trainee feedback no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTfeedbackData(true);
        if (!empty($data)) {
            $tfeedback->setData($data);
        }
        Mage::register('tfeedback_data', $tfeedback);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Trainee Feedbacks'));
        if ($tfeedback->getId()) {
            $this->_title($tfeedback->getContent());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add trainee feedback'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new trainee feedback action
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
     * save trainee feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('tfeedback')) {
            try {
                $tfeedback = $this->_initTfeedback();
                $tfeedback->addData($data);
                $tfeedback->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Trainee Feedback was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $tfeedback->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTfeedbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the trainee feedback.')
                );
                Mage::getSingleton('adminhtml/session')->setTfeedbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find trainee feedback to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete trainee feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $tfeedback = Mage::getModel('bs_kst/tfeedback');
                $tfeedback->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Trainee Feedback was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting trainee feedback.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find trainee feedback to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete trainee feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $tfeedbackIds = $this->getRequest()->getParam('tfeedback');
        if (!is_array($tfeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select trainee feedbacks to delete.')
            );
        } else {
            try {
                foreach ($tfeedbackIds as $tfeedbackId) {
                    $tfeedback = Mage::getModel('bs_kst/tfeedback');
                    $tfeedback->setId($tfeedbackId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d trainee feedbacks were successfully deleted.', count($tfeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting trainee feedbacks.')
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
        $tfeedbackIds = $this->getRequest()->getParam('tfeedback');
        if (!is_array($tfeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select trainee feedbacks.')
            );
        } else {
            try {
                foreach ($tfeedbackIds as $tfeedbackId) {
                $tfeedback = Mage::getSingleton('bs_kst/tfeedback')->load($tfeedbackId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainee feedbacks were successfully updated.', count($tfeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating trainee feedbacks.')
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
        $fileName   = 'tfeedback.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_tfeedback_grid')
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
        $fileName   = 'tfeedback.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_tfeedback_grid')
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
        $fileName   = 'tfeedback.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_tfeedback_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/tfeedback');
    }
}
