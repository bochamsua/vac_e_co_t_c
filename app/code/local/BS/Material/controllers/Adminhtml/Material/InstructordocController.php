<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document admin controller
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Adminhtml_Material_InstructordocController extends BS_Material_Controller_Adminhtml_Material
{
    /**
     * init the instructor doc
     *
     * @access protected
     * @return BS_Material_Model_Instructordoc
     */
    protected function _initInstructordoc()
    {
        $instructordocId  = (int) $this->getRequest()->getParam('id');
        $instructordoc    = Mage::getModel('bs_material/instructordoc');
        if ($instructordocId) {
            $instructordoc->load($instructordocId);
        }
        Mage::register('current_instructordoc', $instructordoc);
        return $instructordoc;
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
        $this->_title(Mage::helper('bs_material')->__('Manage Materials'))
             ->_title(Mage::helper('bs_material')->__('Instructor Documents'));
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
     * edit instructor doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $instructordocId    = $this->getRequest()->getParam('id');
        $instructordoc      = $this->_initInstructordoc();
        if ($instructordocId && !$instructordoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_material')->__('This instructor doc no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInstructordocData(true);
        if (!empty($data)) {
            $instructordoc->setData($data);
        }
        Mage::register('instructordoc_data', $instructordoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_material')->__('Manage Materials'))
             ->_title(Mage::helper('bs_material')->__('Instructor Documents'));
        if ($instructordoc->getId()) {
            $this->_title($instructordoc->getIdocName());
        } else {
            $this->_title(Mage::helper('bs_material')->__('Add instructor doc'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor doc action
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
     * save instructor doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('instructordoc')) {
            try {
                $data = $this->_filterDates($data, array('idoc_date'));
                $instructordoc = $this->_initInstructordoc();
                $instructordoc->addData($data);

                $idocFileName = $this->_uploadAndGetName(
                    'idoc_file',
                    Mage::helper('bs_material/instructordoc')->getFileBaseDir(),
                    $data
                );
                $instructordoc->setData('idoc_file', $idocFileName);


                $instructors = $this->getRequest()->getPost('instructors', -1);
                if ($instructors != -1) {
                    $instructordoc->setInstructorsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($instructors));
                }else {
                    if(isset($data['hidden_instructor_id']) && $data['hidden_instructor_id'] > 0){
                        $instructordoc->setInstructorsData(
                            array(
                                $data['hidden_instructor_id'] => array(
                                    'position' => ""
                                )
                            )
                        );

                        $url = $this->getUrl('*/instructor_instructor/edit', array('id'=>$data['hidden_instructor_id'], 'back'=> 'edit', 'tab'=>'instructor_info_tabs_instructordocs'));
                    }
                }
                $instructordoc->save();

                $add = '';

                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.location.href=\''.$url.'\'; window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_material')->__('Instructor Document was successfully saved %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $instructordoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['idoc_file']['value'])) {
                    $data['idoc_file'] = $data['idoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInstructordocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['idoc_file']['value'])) {
                    $data['idoc_file'] = $data['idoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_material')->__('There was a problem saving the instructor doc.')
                );
                Mage::getSingleton('adminhtml/session')->setInstructordocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_material')->__('Unable to find instructor doc to save.')
        );
        $this->_redirect('*/*/');
    }

    public function massApprovedDateAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('instructordoc');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_material')->__('Please select doc.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('approved_date');
                $dates = array('input_date'=>$date);

                $dates = $this->_filterDates($dates,array('input_date'));

                $date = $dates['input_date'];

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_material/instructordoc')->load($taskfunctionId)
                        ->setIdocDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d docs were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating doc.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * delete instructor doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $instructordoc = Mage::getModel('bs_material/instructordoc');
                $instructordoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_material')->__('Instructor Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_material')->__('There was an error deleting instructor doc.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_material')->__('Could not find instructor doc to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $instructordocIds = $this->getRequest()->getParam('instructordoc');
        if (!is_array($instructordocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_material')->__('Please select instructor docs to delete.')
            );
        } else {
            try {
                foreach ($instructordocIds as $instructordocId) {
                    $instructordoc = Mage::getModel('bs_material/instructordoc');
                    $instructordoc->setId($instructordocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_material')->__('Total of %d instructor docs were successfully deleted.', count($instructordocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_material')->__('There was an error deleting instructor docs.')
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
        $instructordocIds = $this->getRequest()->getParam('instructordoc');
        if (!is_array($instructordocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_material')->__('Please select instructor docs.')
            );
        } else {
            try {
                foreach ($instructordocIds as $instructordocId) {
                $instructordoc = Mage::getSingleton('bs_material/instructordoc')->load($instructordocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor docs were successfully updated.', count($instructordocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_material')->__('There was an error updating instructor docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of instructors action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsAction()
    {
        $this->_initInstructordoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructordoc.edit.tab.instructor')
            ->setInstructordocInstructors($this->getRequest()->getPost('instructordoc_instructors', null));
        $this->renderLayout();
    }

    /**
     * get grid of instructors action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsgridAction()
    {
        $this->_initInstructordoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructordoc.edit.tab.instructor')
            ->setInstructordocInstructors($this->getRequest()->getPost('instructordoc_instructors', null));
        $this->renderLayout();
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
        $fileName   = 'instructordoc.csv';
        $content    = $this->getLayout()->createBlock('bs_material/adminhtml_instructordoc_grid')
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
        $fileName   = 'instructordoc.xls';
        $content    = $this->getLayout()->createBlock('bs_material/adminhtml_instructordoc_grid')
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
        $fileName   = 'instructordoc.xml';
        $content    = $this->getLayout()->createBlock('bs_material/adminhtml_instructordoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/instructordoc');
    }
}
