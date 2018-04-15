<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Instructor Feedback admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_IfeedbackController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the instructor feedback
     *
     * @access protected
     * @return BS_KST_Model_Ifeedback
     */
    protected function _initIfeedback()
    {
        $ifeedbackId  = (int) $this->getRequest()->getParam('id');
        $ifeedback    = Mage::getModel('bs_kst/ifeedback');
        if ($ifeedbackId) {
            $ifeedback->load($ifeedbackId);
        }
        Mage::register('current_ifeedback', $ifeedback);
        return $ifeedback;
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
             ->_title(Mage::helper('bs_kst')->__('Instructor Feedbacks'));
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
     * edit instructor feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $ifeedbackId    = $this->getRequest()->getParam('id');
        $ifeedback      = $this->_initIfeedback();
        if ($ifeedbackId && !$ifeedback->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This instructor feedback no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getIfeedbackData(true);
        if (!empty($data)) {
            $ifeedback->setData($data);
        }
        Mage::register('ifeedback_data', $ifeedback);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Instructor Feedbacks'));
        if ($ifeedback->getId()) {
            $this->_title($ifeedback->getName());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add instructor feedback'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor feedback action
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
     * save instructor feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('ifeedback')) {
            try {
                $ifeedback = $this->_initIfeedback();
                $ifeedback->addData($data);
                $ifeedback->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Instructor Feedback was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $ifeedback->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setIfeedbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the instructor feedback.')
                );
                Mage::getSingleton('adminhtml/session')->setIfeedbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find instructor feedback to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete instructor feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $ifeedback = Mage::getModel('bs_kst/ifeedback');
                $ifeedback->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Instructor Feedback was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting instructor feedback.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find instructor feedback to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor feedback - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks to delete.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                    $ifeedback = Mage::getModel('bs_kst/ifeedback');
                    $ifeedback->setId($ifeedbackId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d instructor feedbacks were successfully deleted.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting instructor feedbacks.')
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
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria one change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaOneAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaOne($this->getRequest()->getParam('flag_criteria_one'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria two change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaTwoAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaTwo($this->getRequest()->getParam('flag_criteria_two'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria Three change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaThreeAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaThree($this->getRequest()->getParam('flag_criteria_three'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria Four change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaFourAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaFour($this->getRequest()->getParam('flag_criteria_four'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria Five change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaFiveAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaFive($this->getRequest()->getParam('flag_criteria_five'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Criteria Six change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCriteriaSixAction()
    {
        $ifeedbackIds = $this->getRequest()->getParam('ifeedback');
        if (!is_array($ifeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select instructor feedbacks.')
            );
        } else {
            try {
                foreach ($ifeedbackIds as $ifeedbackId) {
                $ifeedback = Mage::getSingleton('bs_kst/ifeedback')->load($ifeedbackId)
                    ->setCriteriaSix($this->getRequest()->getParam('flag_criteria_six'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor feedbacks were successfully updated.', count($ifeedbackIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating instructor feedbacks.')
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
        $fileName   = 'ifeedback.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_ifeedback_grid')
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
        $fileName   = 'ifeedback.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_ifeedback_grid')
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
        $fileName   = 'ifeedback.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_ifeedback_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/ifeedback');
    }
}
