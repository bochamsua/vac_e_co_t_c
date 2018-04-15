<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Tool admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_WtoolController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the tool
     *
     * @access protected
     * @return BS_Logistics_Model_Wtool
     */
    protected function _initWtool()
    {
        $wtoolId  = (int) $this->getRequest()->getParam('id');
        $wtool    = Mage::getModel('bs_logistics/wtool');
        if ($wtoolId) {
            $wtool->load($wtoolId);
        }
        Mage::register('current_wtool', $wtool);
        return $wtool;
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
             ->_title(Mage::helper('bs_logistics')->__('Tool'));
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
     * edit tool - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $wtoolId    = $this->getRequest()->getParam('id');
        $wtool      = $this->_initWtool();
        if ($wtoolId && !$wtool->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This tool no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWtoolData(true);
        if (!empty($data)) {
            $wtool->setData($data);
        }
        Mage::register('wtool_data', $wtool);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Tool'));
        if ($wtool->getId()) {
            $this->_title($wtool->getName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add tool'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new tool action
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
     * save tool - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('wtool')) {
            try {

                $workshopId = $data['workshop_id'];
                $typeId = $data['grouptype_id'];
                $containerId = $data['wgroupitem_id'];

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);

                    foreach ($import as $line) {
                        if(strpos($line, "--")){
                            $item = explode("--", $line);
                        }else {
                            $item = explode("\t", $line);
                        }
                        $item = array_map('trim', $item);

                        $count = count($item);
                        $name = null;
                        $nameVi = null;
                        $part = null;
                        $qty = null;
                        $status = null;
                        if($count == 3){//Name -- P/N, Type -- Qty

                            $name = $item[0];
                            $part = $item[1];
                            $qty = $item[2];

                        }elseif($count == 4){//Name -- Vietnamese Name -- P/N, Type -- Qty

                            $name = $item[0];
                            $nameVi = $item[1];
                            $part = $item[2];
                            $qty = $item[3];
                        }elseif($count == 5){//Name -- Vietnamese Name -- P/N, Type -- Qty -- Status

                            $name = $item[0];
                            $nameVi = $item[1];
                            $part = $item[2];
                            $qty = $item[3];
                            $status = $item[4];
                        }

                        if($name){

                            //clear
                            $newData = $data;

                            $nextCode = Mage::helper('bs_logistics')->getNextEquipmentCode($workshopId,$typeId, $containerId);
                            $newData['code'] = $nextCode;
                            $newData['name'] = $name;
                            $newData['name_vi'] = $nameVi;
                            $newData['part_number'] = $part;
                            $newData['tool_status'] = $status;
                            $newData['qty'] = $qty;
                            $newData['note'] = null;
                            $newData['entity_id'] = null;

                            $wgroupitem = Mage::getModel('bs_logistics/wtool');
                            $wgroupitem->addData($newData);

                            $wgroupitem->save();

                        }

                    }

                }else {
                    $wtool = $this->_initWtool();

                    //generate code here
                    if(!$wtool->getId()){


                        $nextCode = Mage::helper('bs_logistics')->getNextEquipmentCode($workshopId,$typeId, $containerId);
                        $data['code'] = $nextCode;
                    }


                    $wtool->addData($data);
                    $wtool->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Tool was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $wtool->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWtoolData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the tool.')
                );
                Mage::getSingleton('adminhtml/session')->setWtoolData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find tool to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete tool - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $wtool = Mage::getModel('bs_logistics/wtool');
                $wtool->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Tool was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting tool.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find tool to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete tool - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $wtoolIds = $this->getRequest()->getParam('wtool');
        if (!is_array($wtoolIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select tool to delete.')
            );
        } else {
            try {
                foreach ($wtoolIds as $wtoolId) {
                    $wtool = Mage::getModel('bs_logistics/wtool');
                    $wtool->setId($wtoolId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d tool were successfully deleted.', count($wtoolIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting tool.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massGrouptypeIdAction()
    {
        $wtoolIds = $this->getRequest()->getParam('wtool');
        if (!is_array($wtoolIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select tool.')
            );
        } else {
            try {
                foreach ($wtoolIds as $wtoolId) {
                    $wtool = Mage::getSingleton('bs_logistics/wtool')->load($wtoolId)
                        ->setGrouptypeId($this->getRequest()->getParam('flag_grouptype_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tool were successfully updated.', count($wtoolIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating tool.')
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
        $wtoolIds = $this->getRequest()->getParam('wtool');
        if (!is_array($wtoolIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select tool.')
            );
        } else {
            try {
                foreach ($wtoolIds as $wtoolId) {
                $wtool = Mage::getSingleton('bs_logistics/wtool')->load($wtoolId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tool were successfully updated.', count($wtoolIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating tool.')
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
        $wtoolIds = $this->getRequest()->getParam('wtool');
        if (!is_array($wtoolIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select tool.')
            );
        } else {
            try {
                foreach ($wtoolIds as $wtoolId) {
                    $wtool = Mage::getSingleton('bs_logistics/wtool')->load($wtoolId)
                        ->setWorkshopId($this->getRequest()->getParam('flag_workshop_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tool were successfully updated.', count($wtoolIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating tool.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass group item change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWgroupitemIdAction()
    {
        $wtoolIds = $this->getRequest()->getParam('wtool');
        if (!is_array($wtoolIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select tool.')
            );
        } else {
            try {
                foreach ($wtoolIds as $wtoolId) {
                $wtool = Mage::getSingleton('bs_logistics/wtool')->load($wtoolId)
                    ->setWgroupitemId($this->getRequest()->getParam('flag_wgroupitem_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tool were successfully updated.', count($wtoolIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating tool.')
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
        $fileName   = 'wtool.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wtool_grid')
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
        $fileName   = 'wtool.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wtool_grid')
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
        $fileName   = 'wtool.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wtool_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/workshop/wtool');
    }
}
