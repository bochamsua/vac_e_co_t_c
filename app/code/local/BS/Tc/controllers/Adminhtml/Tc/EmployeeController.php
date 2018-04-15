<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Employee admin controller
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Adminhtml_Tc_EmployeeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->setUsedModuleName('BS_Tc');
    }

    /**
     * init the employee
     *
     * @access protected 
     * @return BS_Tc_Model_Employee
     * @author Bui Phong
     */
    protected function _initEmployee()
    {
        $this->_title($this->__('TC'))
             ->_title($this->__('Manage Employees'));

        $employeeId  = (int) $this->getRequest()->getParam('id');
        $employee    = Mage::getModel('bs_tc/employee')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($employeeId) {
            $employee->load($employeeId);
        }
        Mage::register('current_employee', $employee);
        return $employee;
    }

    /**
     * default action for employee controller
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_title($this->__('TC'))
             ->_title($this->__('Manage Employees'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new employee action
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
     * edit employee action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $employeeId  = (int) $this->getRequest()->getParam('id');
        $employee    = $this->_initEmployee();
        if ($employeeId && !$employee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tc')->__('This employee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getEmployeeData(true)) {
            $employee->setData($data);
        }
        $this->_title($employee->getEname());
        Mage::dispatchEvent(
            'bs_tc_employee_edit_action',
            array('employee' => $employee)
        );
        $this->loadLayout();
        if ($employee->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('bs_tc')->__('Default Values'))
                    ->setWebsiteIds($employee->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save employee action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $employeeId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $employee     = $this->_initEmployee();
            $employeeData = $this->getRequest()->getPost('employee', array());
            $employee->addData($employeeData);
            $employee->setAttributeSetId($employee->getDefaultAttributeSetId());
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $employee->setData($attributeCode, false);
                }
            }
            try {
                $employee->save();
                $employeeId = $employee->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('Employee was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setEmployeeData($employeeData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('bs_tc')->__('Error saving employee')
                )
                ->setEmployeeData($employeeData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $employeeId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete employee
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $employee = Mage::getModel('bs_tc/employee')->load($id);
            try {
                $employee->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('The employees has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete employees
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $employeeIds = $this->getRequest()->getParam('employee');
        if (!is_array($employeeIds)) {
            $this->_getSession()->addError($this->__('Please select employees.'));
        } else {
            try {
                foreach ($employeeIds as $employeeId) {
                    $employee = Mage::getSingleton('bs_tc/employee')->load($employeeId);
                    Mage::dispatchEvent(
                        'bs_tc_controller_employee_delete',
                        array('employee' => $employee)
                    );
                    $employee->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('Total of %d record(s) have been deleted.', count($employeeIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
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
        $employeeIds = $this->getRequest()->getParam('employee');
        if (!is_array($employeeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tc')->__('Please select employees.')
            );
        } else {
            try {
                foreach ($employeeIds as $employeeId) {
                $employee = Mage::getSingleton('bs_tc/employee')->load($employeeId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d employees were successfully updated.', count($employeeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tc')->__('There was an error updating employees.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_tc/employee');
    }

    /**
     * Export employees in CSV format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'employees.csv';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_employee_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export employees in Excel format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'employee.xls';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_employee_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export employees in XML format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'employee.xml';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_employee_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'bs_tc/adminhtml_tc_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }
}
