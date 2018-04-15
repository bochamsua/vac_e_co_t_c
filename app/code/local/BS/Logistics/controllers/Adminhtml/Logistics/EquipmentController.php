<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Equipment admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_EquipmentController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the equipment
     *
     * @access protected
     * @return BS_Logistics_Model_Equipment
     */
    protected function _initEquipment()
    {
        $equipmentId  = (int) $this->getRequest()->getParam('id');
        $equipment    = Mage::getModel('bs_logistics/equipment');
        if ($equipmentId) {
            $equipment->load($equipmentId);
        }
        Mage::register('current_equipment', $equipment);
        return $equipment;
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
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
            ->_title(Mage::helper('bs_logistics')->__('Equipments'));
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
     * edit equipment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $equipmentId    = $this->getRequest()->getParam('id');
        $equipment      = $this->_initEquipment();
        if ($equipmentId && !$equipment->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This equipment no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getEquipmentData(true);
        if (!empty($data)) {
            $equipment->setData($data);
        }
        Mage::register('equipment_data', $equipment);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
            ->_title(Mage::helper('bs_logistics')->__('Equipments'));
        if ($equipment->getId()) {
            $this->_title($equipment->getEquipmentName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add equipment'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new equipment action
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
     * save equipment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('equipment')) {
            try {
                $equipment = $this->_initEquipment();
                $equipment->addData($data);
                $equipment->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Equipment was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $equipment->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setEquipmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the equipment.')
                );
                Mage::getSingleton('adminhtml/session')->setEquipmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find equipment to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete equipment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $equipment = Mage::getModel('bs_logistics/equipment');
                $equipment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Equipment was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting equipment.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find equipment to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete equipment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $equipmentIds = $this->getRequest()->getParam('equipment');
        if (!is_array($equipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select equipments to delete.')
            );
        } else {
            try {
                foreach ($equipmentIds as $equipmentId) {
                    $equipment = Mage::getModel('bs_logistics/equipment');
                    $equipment->setId($equipmentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d equipments were successfully deleted.', count($equipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting equipments.')
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
        $equipmentIds = $this->getRequest()->getParam('equipment');
        if (!is_array($equipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select equipments.')
            );
        } else {
            try {
                foreach ($equipmentIds as $equipmentId) {
                    $equipment = Mage::getSingleton('bs_logistics/equipment')->load($equipmentId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d equipments were successfully updated.', count($equipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating equipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass classroom/examroom change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massClassroomIdAction()
    {
        $equipmentIds = $this->getRequest()->getParam('equipment');
        if (!is_array($equipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select equipments.')
            );
        } else {
            try {
                foreach ($equipmentIds as $equipmentId) {
                    $equipment = Mage::getSingleton('bs_logistics/equipment')->load($equipmentId)
                        ->setClassroomId($this->getRequest()->getParam('flag_classroom_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d equipments were successfully updated.', count($equipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating equipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass workshop change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWorkshopIdAction()
    {
        $equipmentIds = $this->getRequest()->getParam('equipment');
        if (!is_array($equipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select equipments.')
            );
        } else {
            try {
                foreach ($equipmentIds as $equipmentId) {
                    $equipment = Mage::getSingleton('bs_logistics/equipment')->load($equipmentId)
                        ->setWorkshopId($this->getRequest()->getParam('flag_workshop_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d equipments were successfully updated.', count($equipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating equipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass other room change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massOtherroomIdAction()
    {
        $equipmentIds = $this->getRequest()->getParam('equipment');
        if (!is_array($equipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select equipments.')
            );
        } else {
            try {
                foreach ($equipmentIds as $equipmentId) {
                    $equipment = Mage::getSingleton('bs_logistics/equipment')->load($equipmentId)
                        ->setOtherroomId($this->getRequest()->getParam('flag_otherroom_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d equipments were successfully updated.', count($equipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating equipments.')
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
        $fileName   = 'equipment.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_equipment_grid')
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
        $fileName   = 'equipment.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_equipment_grid')
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
        $fileName   = 'equipment.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_equipment_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/equipment');
    }
}
