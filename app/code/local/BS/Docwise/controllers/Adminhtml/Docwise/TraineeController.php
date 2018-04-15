<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_TraineeController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the trainee
     *
     * @access protected
     * @return BS_Docwise_Model_Trainee
     */
    protected function _initTrainee()
    {
        $traineeId  = (int) $this->getRequest()->getParam('id');
        $trainee    = Mage::getModel('bs_docwise/trainee');
        if ($traineeId) {
            $trainee->load($traineeId);
        }
        Mage::register('current_trainee', $trainee);
        return $trainee;
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
             ->_title(Mage::helper('bs_docwise')->__('Trainees'));
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
     * edit trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $traineeId    = $this->getRequest()->getParam('id');
        $trainee      = $this->_initTrainee();
        if ($traineeId && !$trainee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This trainee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTraineeData(true);
        if (!empty($data)) {
            $trainee->setData($data);
        }
        Mage::register('trainee_data', $trainee);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Trainees'));
        if ($trainee->getId()) {
            $this->_title($trainee->getTraineeName());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add trainee'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new trainee action
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
     * save trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('trainee')) {
            try {
                $data = $this->_filterDates($data, array('dob'));
                $trainee = $this->_initTrainee();
                $trainee->addData($data);
                $trainee->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Trainee was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $trainee->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the trainee.')
                );
                Mage::getSingleton('adminhtml/session')->setTraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find trainee to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $trainee = Mage::getModel('bs_docwise/trainee');
                $trainee->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Trainee was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting trainee.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find trainee to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select trainees to delete.')
            );
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                    $trainee = Mage::getModel('bs_docwise/trainee');
                    $trainee->setId($traineeId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d trainees were successfully deleted.', count($traineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting trainees.')
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
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select trainees.')
            );
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                $trainee = Mage::getSingleton('bs_docwise/trainee')->load($traineeId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainees were successfully updated.', count($traineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating trainees.')
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
        $fileName   = 'trainee.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_trainee_grid')
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
        $fileName   = 'trainee.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_trainee_grid')
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
        $fileName   = 'trainee.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_trainee_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function examsAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.exam');
        $this->renderLayout();
    }

    public function examsGridAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.exam');
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/trainee');
    }
}
