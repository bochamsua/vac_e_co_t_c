<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * TC Task admin controller
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Adminhtml_Report_TctaskController extends BS_Report_Controller_Adminhtml_Report
{
    /**
     * init the tc task
     *
     * @access protected
     * @return BS_Report_Model_Tctask
     */
    protected function _initTctask()
    {
        $tctaskId  = (int) $this->getRequest()->getParam('id');
        $tctask    = Mage::getModel('bs_report/tctask');
        if ($tctaskId) {
            $tctask->load($tctaskId);
        }
        Mage::register('current_tctask', $tctask);
        return $tctask;
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
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('TC Tasks'));
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
     * edit tc task - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $tctaskId    = $this->getRequest()->getParam('id');
        $tctask      = $this->_initTctask();
        if ($tctaskId && !$tctask->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_report')->__('This tc task no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTctaskData(true);
        if (!empty($data)) {
            $tctask->setData($data);
        }
        Mage::register('tctask_data', $tctask);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('TC Tasks'));
        if ($tctask->getId()) {
            $this->_title($tctask->getTctaskName());
        } else {
            $this->_title(Mage::helper('bs_report')->__('Add tc task'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new tc task action
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
     * save tc task - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('tctask')) {
            try {
                $tctask = $this->_initTctask();

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $instructorId = $data['staff_id'];
                    foreach ($import as $line) {
                        if(strpos($line, "--")){
                            $item = explode("--", $line);
                        }else {
                            $item = explode("\t", $line);
                        }

                        $count = count($item);
                        $supervisor = 5;
                        if($count > 1){


                            $code = trim($item[0]);
                            $content = trim($item[1]);
                            if(isset($item[2])){
                                $supervisor = intval(trim($item[2]));
                            }


                            $task = Mage::getModel('bs_report/tctask');
                            $task->setTctaskName($content)
                                ->setTctaskCode($code)
                                ->setSupervisorId($supervisor)
                                ->save()
                                ;
                        }



                    }

                }else {
                    $tctask->addData($data);
                    $tctask->save();
                }

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('TC Task was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $tctask->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTctaskData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was a problem saving the tc task.')
                );
                Mage::getSingleton('adminhtml/session')->setTctaskData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Unable to find tc task to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete tc task - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $tctask = Mage::getModel('bs_report/tctask');
                $tctask->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('TC Task was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting tc task.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Could not find tc task to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete tc task - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $tctaskIds = $this->getRequest()->getParam('tctask');
        if (!is_array($tctaskIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select tc tasks to delete.')
            );
        } else {
            try {
                foreach ($tctaskIds as $tctaskId) {
                    $tctask = Mage::getModel('bs_report/tctask');
                    $tctask->setId($tctaskId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Total of %d tc tasks were successfully deleted.', count($tctaskIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting tc tasks.')
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
        $tctaskIds = $this->getRequest()->getParam('tctask');
        if (!is_array($tctaskIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select tc tasks.')
            );
        } else {
            try {
                foreach ($tctaskIds as $tctaskId) {
                $tctask = Mage::getSingleton('bs_report/tctask')->load($tctaskId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tc tasks were successfully updated.', count($tctaskIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating tc tasks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massSupervisorAction()
    {
        $tctaskIds = $this->getRequest()->getParam('tctask');
        if (!is_array($tctaskIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select tasks.')
            );
        } else {
            try {
                $supervisor = $this->getRequest()->getParam('supervisor');
                foreach ($tctaskIds as $tctaskId) {
                    $tctask = Mage::getModel('bs_report/tctask')->load($tctaskId);

                    $tctask->setSupervisorId($supervisor)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tasks were successfully updated.', count($tctaskIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating tasks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massSouthernSupervisorAction()
    {
        $tctaskIds = $this->getRequest()->getParam('tctask');
        if (!is_array($tctaskIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select tasks.')
            );
        } else {
            try {
                $supervisor = $this->getRequest()->getParam('southern_supervisor');
                foreach ($tctaskIds as $tctaskId) {
                    $tctask = Mage::getModel('bs_report/tctask')->load($tctaskId);

                    $tctask->setSouthernSupervisorId($supervisor)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tasks were successfully updated.', count($tctaskIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating tasks.')
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
        $fileName   = 'tctask.csv';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_tctask_grid')
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
        $fileName   = 'tctask.xls';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_tctask_grid')
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
        $fileName   = 'tctask.xml';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_tctask_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_report/tctask');
    }
}
