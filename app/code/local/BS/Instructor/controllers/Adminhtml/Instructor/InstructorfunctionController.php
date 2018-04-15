<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin controller
 *
 * @category    BS
 * @package     BS_Instructor
 * @author Bui Phong
 */
class BS_Instructor_Adminhtml_Instructor_InstructorfunctionController extends BS_Instructor_Controller_Adminhtml_Instructor
{
    /**
     * init the instructor function
     *
     * @access protected
     * @return BS_Instructor_Model_Instructorfunction
     */
    protected function _initInstructorfunction()
    {
        $instructorfunctionId  = (int) $this->getRequest()->getParam('id');
        $instructorfunction    = Mage::getModel('bs_instructor/instructorfunction');
        if ($instructorfunctionId) {
            $instructorfunction->load($instructorfunctionId);
        }
        Mage::register('current_instructorfunction', $instructorfunction);
        return $instructorfunction;
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
        $this->_title(Mage::helper('bs_instructor')->__('Instructor'))
             ->_title(Mage::helper('bs_instructor')->__('Instructor Function'));
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
     * edit instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $instructorfunctionId    = $this->getRequest()->getParam('id');
        $instructorfunction      = $this->_initInstructorfunction();
        if ($instructorfunctionId && !$instructorfunction->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructor')->__('This instructor function no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInstructorfunctionData(true);
        if (!empty($data)) {
            $instructorfunction->setData($data);
        }
        Mage::register('instructorfunction_data', $instructorfunction);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_instructor')->__('Instructor'))
             ->_title(Mage::helper('bs_instructor')->__('Instructor Function'));
        if ($instructorfunction->getId()) {
            $this->_title($instructorfunction->getApprovedCourse());
        } else {
            $this->_title(Mage::helper('bs_instructor')->__('Add instructor function'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor function action
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
     * save instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('instructorfunction')) {
            try {
                $instructorfunction = $this->_initInstructorfunction();
                $instructorfunction->addData($data);
                $instructorfunction->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructor')->__('Instructor Function was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $instructorfunction->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInstructorfunctionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was a problem saving the instructor function.')
                );
                Mage::getSingleton('adminhtml/session')->setInstructorfunctionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructor')->__('Unable to find instructor function to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $instructorfunction = Mage::getModel('bs_instructor/instructorfunction');
                $instructorfunction->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructor')->__('Instructor Function was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was an error deleting instructor function.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructor')->__('Could not find instructor function to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $instructorfunctionIds = $this->getRequest()->getParam('instructorfunction');
        if (!is_array($instructorfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructor')->__('Please select instructor function to delete.')
            );
        } else {
            try {
                foreach ($instructorfunctionIds as $instructorfunctionId) {
                    $instructorfunction = Mage::getModel('bs_instructor/instructorfunction');
                    $instructorfunction->setId($instructorfunctionId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructor')->__('Total of %d instructor function were successfully deleted.', count($instructorfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was an error deleting instructor function.')
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
        $instructorfunctionIds = $this->getRequest()->getParam('instructorfunction');
        if (!is_array($instructorfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructor')->__('Please select instructor function.')
            );
        } else {
            try {
                foreach ($instructorfunctionIds as $instructorfunctionId) {
                $instructorfunction = Mage::getSingleton('bs_instructor/instructorfunction')->load($instructorfunctionId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($instructorfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massApprovedDocAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('instructorfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $doc = $this->getRequest()->getParam('approved_doc');

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_instructor/instructorfunction')->load($taskfunctionId)
                        ->setApprovedDoc($doc)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massApprovedDateAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('instructorfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('approved_date');
                $dates = array('input_date'=>$date);

                $dates = $this->_filterDates($dates,array('input_date'));

                $date = $dates['input_date'];

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_instructor/instructorfunction')->load($taskfunctionId)
                        ->setApprovedDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massExpireDateAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('instructorfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('expire_date');
                $dates = array('input_date'=>$date);
                $dates = $this->_filterDates($dates,array('input_date'));
                $date = $dates['input_date'];

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_instructor/instructorfunction')->load($taskfunctionId)
                        ->setExpireDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
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
        $fileName   = 'instructorfunction.csv';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructorfunction_grid')
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
        $fileName   = 'instructorfunction.xls';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructorfunction_grid')
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
        $fileName   = 'instructorfunction.xml';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructorfunction_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/instructorfunction');
    }
}
