<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group Item admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_WgroupitemController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the group item
     *
     * @access protected
     * @return BS_Logistics_Model_Wgroupitem
     */
    protected function _initWgroupitem()
    {
        $wgroupitemId  = (int) $this->getRequest()->getParam('id');
        $wgroupitem    = Mage::getModel('bs_logistics/wgroupitem');
        if ($wgroupitemId) {
            $wgroupitem->load($wgroupitemId);
        }
        Mage::register('current_wgroupitem', $wgroupitem);
        return $wgroupitem;
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
             ->_title(Mage::helper('bs_logistics')->__('Equipment'));
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
     * edit group item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $wgroupitemId    = $this->getRequest()->getParam('id');
        $wgroupitem      = $this->_initWgroupitem();
        if ($wgroupitemId && !$wgroupitem->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This group item no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWgroupitemData(true);
        if (!empty($data)) {
            $wgroupitem->setData($data);
        }
        Mage::register('wgroupitem_data', $wgroupitem);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Equipment'));
        if ($wgroupitem->getId()) {
            $this->_title($wgroupitem->getName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add Equipment'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new group item action
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
     * save group item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('wgroupitem')) {
            try {
                $workshopId = $data['workshop_id'];
                $typeId = $data['grouptype_id'];


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
                        $name = false;
                        $nameVi = null;
                        if($count == 1){//Name

                            $name = $item[0];

                        }elseif($count == 2){//Name--Vietnamese Name

                            $name = $item[0];
                            $nameVi = $item[1];
                        }

                        if($name){

                            //clear
                            $newData = $data;

                            $nextCode = Mage::helper('bs_logistics')->getNextContainerCode($workshopId,$typeId);
                            $newData['code'] = $nextCode;
                            $newData['name'] = $name;
                            $newData['name_vi'] = $nameVi;
                            $newData['entity_id'] = null;

                            $wgroupitem = Mage::getModel('bs_logistics/wgroupitem');
                            $wgroupitem->addData($newData);

                            $wgroupitem->save();

                        }

                    }

                }else {
                    $wgroupitem = $this->_initWgroupitem();

                    //generate code here
                    if(!$wgroupitem->getId()){

                        $nextCode = Mage::helper('bs_logistics')->getNextContainerCode($workshopId,$typeId);
                        $data['code'] = $nextCode;
                    }


                    $wgroupitem->addData($data);
                    $wgroupitem->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Group Item was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $wgroupitem->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWgroupitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the group item.')
                );
                Mage::getSingleton('adminhtml/session')->setWgroupitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find group item to save.')
        );
        $this->_redirect('*/*/');
    }

    public function generateToolListAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {

                $ws = Mage::getModel('bs_logistics/wgroupitem')->load($this->getRequest()->getParam('id'));

                $this->generateToolList($ws);

                $this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error generating tool list.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find tool list to generate.')
        );
        $this->_redirect('*/*/');
    }

    public function generateToolList($container){
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('2063');

        $cId = $container->getId();
        $name = $container->getName();
        $code = $container->getCode();

        $container = Mage::getModel('bs_logistics/wgroupitem')->load($cId);
        $workshop = $container->getParentWorkshop()->getWorkshopName();


        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $checkDate = $now->format('d/m/Y');

        $todayDate = $now->format('Y-m-d');
        $todayDate .= ' 00:00:00.000000';

        $today = new DateTime($todayDate, $myTimezone);


        $interval = new DateInterval('P1M');

        $today->add($interval);

        $dueDate = $today->format('d/m/Y');

        //$date = Mage::getModel('core/date')->date("d/m/Y", now());

        $data = array(
            'code' => $code,
            'workshop' => $workshop,
            'check_date'    => $checkDate,
            'due_date'  => $dueDate

        );

        $result = array();
        $tools = Mage::getModel('bs_logistics/wtool')->getCollection()
            ->addFieldToFilter('workshop_id', $container->getWorkshopId())
            ->addFieldToFilter('wgroupitem_id', $cId)
            ->setOrder('code', 'ASC')
            ;

        if($tools->count()){
            foreach ($tools as $tool) {
                $result[] = array(
                    'name'  => $tool->getName(),
                    'part'  => $tool->getPartNumber(),
                    'qty'   => $tool->getQty(),
                    'status'    => $tool->getToolStatus()
                );
            }
        }

        $tableData = array($result);


        $res = Mage::helper('bs_traininglist/docx')->generateDocx($name . '_Tool List', $template, $data,$tableData);

        $this->_getSession()->addSuccess(
            Mage::helper('bs_logistics')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
        );
    }

    /**
     * delete group item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $wgroupitem = Mage::getModel('bs_logistics/wgroupitem');
                $wgroupitem->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Group Item was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting group item.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find group item to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete group item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $wgroupitemIds = $this->getRequest()->getParam('wgroupitem');
        if (!is_array($wgroupitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select group items to delete.')
            );
        } else {
            try {
                foreach ($wgroupitemIds as $wgroupitemId) {
                    $wgroupitem = Mage::getModel('bs_logistics/wgroupitem');
                    $wgroupitem->setId($wgroupitemId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d group items were successfully deleted.', count($wgroupitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting group items.')
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
        $wgroupitemIds = $this->getRequest()->getParam('wgroupitem');
        if (!is_array($wgroupitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select group items.')
            );
        } else {
            try {
                foreach ($wgroupitemIds as $wgroupitemId) {
                $wgroupitem = Mage::getSingleton('bs_logistics/wgroupitem')->load($wgroupitemId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d group items were successfully updated.', count($wgroupitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating group items.')
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
        $wgroupitemIds = $this->getRequest()->getParam('wgroupitem');
        if (!is_array($wgroupitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select group items.')
            );
        } else {
            try {
                foreach ($wgroupitemIds as $wgroupitemId) {
                    $wgroupitem = Mage::getSingleton('bs_logistics/wgroupitem')->load($wgroupitemId)
                        ->setWorkshopId($this->getRequest()->getParam('flag_workshop_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d group items were successfully updated.', count($wgroupitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating group items.')
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
        $wgroupitemIds = $this->getRequest()->getParam('wgroupitem');
        if (!is_array($wgroupitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select group items.')
            );
        } else {
            try {
                foreach ($wgroupitemIds as $wgroupitemId) {
                    $wgroupitem = Mage::getSingleton('bs_logistics/wgroupitem')->load($wgroupitemId)
                        ->setGrouptypeId($this->getRequest()->getParam('flag_grouptype_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d group items were successfully updated.', count($wgroupitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating group items.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }


    /**
     * mass group change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWgroupIdAction()
    {
        $wgroupitemIds = $this->getRequest()->getParam('wgroupitem');
        if (!is_array($wgroupitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select group items.')
            );
        } else {
            try {
                foreach ($wgroupitemIds as $wgroupitemId) {
                $wgroupitem = Mage::getSingleton('bs_logistics/wgroupitem')->load($wgroupitemId)
                    ->setWgroupId($this->getRequest()->getParam('flag_wgroup_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d group items were successfully updated.', count($wgroupitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating group items.')
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
        $fileName   = 'wgroupitem.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroupitem_grid')
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
        $fileName   = 'wgroupitem.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroupitem_grid')
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
        $fileName   = 'wgroupitem.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroupitem_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/workshop/wgroupitem');
    }
}
