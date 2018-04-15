<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_WorkshopController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the workshop
     *
     * @access protected
     * @return BS_Logistics_Model_Workshop
     */
    protected function _initWorkshop()
    {
        $workshopId  = (int) $this->getRequest()->getParam('id');
        $workshop    = Mage::getModel('bs_logistics/workshop');
        if ($workshopId) {
            $workshop->load($workshopId);
        }
        Mage::register('current_workshop', $workshop);
        return $workshop;
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
             ->_title(Mage::helper('bs_logistics')->__('Workshops'));
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
     * edit workshop - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $workshopId    = $this->getRequest()->getParam('id');
        $workshop      = $this->_initWorkshop();
        if ($workshopId && !$workshop->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This workshop no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWorkshopData(true);
        if (!empty($data)) {
            $workshop->setData($data);
        }
        Mage::register('workshop_data', $workshop);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Workshops'));
        if ($workshop->getId()) {
            $this->_title($workshop->getWorkshopName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add workshop'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new workshop action
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
     * save workshop - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('workshop')) {
            try {
                $workshop = $this->_initWorkshop();
                $workshop->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $workshop->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $workshop->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Workshop was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $workshop->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkshopData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the workshop.')
                );
                Mage::getSingleton('adminhtml/session')->setWorkshopData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find workshop to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete workshop - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $workshop = Mage::getModel('bs_logistics/workshop');
                $workshop->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Workshop was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting workshop.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find workshop to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete workshop - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $workshopIds = $this->getRequest()->getParam('workshop');
        if (!is_array($workshopIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select workshops to delete.')
            );
        } else {
            try {
                foreach ($workshopIds as $workshopId) {
                    $workshop = Mage::getModel('bs_logistics/workshop');
                    $workshop->setId($workshopId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d workshops were successfully deleted.', count($workshopIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting workshops.')
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
        $workshopIds = $this->getRequest()->getParam('workshop');
        if (!is_array($workshopIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select workshops.')
            );
        } else {
            try {
                foreach ($workshopIds as $workshopId) {
                $workshop = Mage::getSingleton('bs_logistics/workshop')->load($workshopId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d workshops were successfully updated.', count($workshopIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating workshops.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Location change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWorkshopLocationAction()
    {
        $workshopIds = $this->getRequest()->getParam('workshop');
        if (!is_array($workshopIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select workshops.')
            );
        } else {
            try {
                foreach ($workshopIds as $workshopId) {
                $workshop = Mage::getSingleton('bs_logistics/workshop')->load($workshopId)
                    ->setWorkshopLocation($this->getRequest()->getParam('flag_workshop_location'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d workshops were successfully updated.', count($workshopIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating workshops.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function generateF32Action()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {

                $ws = Mage::getModel('bs_logistics/workshop')->load($this->getRequest()->getParam('id'));

                $this->generateF32($ws);

                $this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error generating workshop.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find workshop to generate.')
        );
        $this->_redirect('*/*/');
    }

    public function generateF32($ws){
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('TC-F32');

        $wsId = $ws->getId();
        $wsName = $ws->getWorkshopName();
        $wsCode = $ws->getWorkshopCode();


        $wsinfo = array(
            'title' => $wsName,
            //'code' => $wsCode,

        );

        //get Container in workshop

        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        //get all container Ids and sort by Code
        $typeIds = $readConnection->fetchCol("SELECT DISTINCT grouptype_id FROM bs_logistics_wgroupitem WHERE workshop_id = {$wsId} ORDER BY `code` ASC");

        $result = array();
        if(count($typeIds)){
            $i=1;
            foreach ($typeIds as $typeId) {

                $type = Mage::getModel('bs_logistics/grouptype')->load($typeId);


                $containers = Mage::getModel('bs_logistics/wgroupitem')->getCollection()->addFieldToFilter('workshop_id', $wsId)->addFieldToFilter('grouptype_id', $typeId)->setOrder('code', 'ASC');

                if($containers->count()){
                    $result[] = array(
                        'no'    => Mage::helper('bs_logistics')->getLatinIndex($i),
                        'name'  => Mage::helper('bs_traininglist')->uppercase($type->getName()),
                        'code'  => '',
                        'qty'   => $containers->count(),
                        'note'  => ''
                    );

                    $j=1;
                    foreach ($containers as $container) {
                        $result[] = array(
                            'no'    => $j,
                            'name'  => $container->getName(),
                            'code'  => $container->getCode(),
                            'qty'   => '01',
                            'note'  => $container->getNote()
                        );

                        $j++;
                    }
                }

                $i++;
            }
        }

        //Now get big equipment
        //get all items in tool table that don't have container

        //get all container Ids and sort by Code
        $typeIds = $readConnection->fetchCol("SELECT DISTINCT grouptype_id FROM bs_logistics_wtool WHERE workshop_id = {$wsId} AND wgroupitem_id = 0 ORDER BY `code` ASC");

        if(count($typeIds)){

            foreach ($typeIds as $typeId) {
                $tools = Mage::getModel('bs_logistics/wtool')->getCollection()
                    ->addFieldToFilter('workshop_id', $wsId)
                    ->addFieldToFilter('grouptype_id', $typeId)
                    ->addFieldToFilter('wgroupitem_id', 0)
                    ->setOrder('code', 'ASC')

                ;
                $type = Mage::getModel('bs_logistics/grouptype')->load($typeId);
                if($tools->count()){

                    $count = 0;
                    foreach ($tools as $tool) {
                        $count += $tool->getQty();

                    }

                    $result[] = array(
                        'no'    => Mage::helper('bs_logistics')->getLatinIndex($i),
                        'name'  => Mage::helper('bs_traininglist')->uppercase($type->getName()),
                        'code'  => '',
                        'qty'   => $count,
                        'note'  => ''
                    );

                    $j=1;

                    foreach ($tools as $tool) {
                        $result[] = array(
                            'no'    => $j,
                            'name'  => $tool->getName(),
                            'code'  => $tool->getCode(),
                            'qty'   => $tool->getQty(),
                            'note'  => $tool->getNote()
                        );

                        $j++;
                    }
                }

                $i++;
            }
        }

        $tableData = array($result);


        $res = Mage::helper('bs_traininglist/docx')->generateDocx($wsName . '_TC-F32', $template, $wsinfo,$tableData);

        $this->_getSession()->addSuccess(
            Mage::helper('bs_logistics')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
        );
    }


    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsAction()
    {
        $this->_initWorkshop();
        $this->loadLayout();
        $this->getLayout()->getBlock('workshop.edit.tab.product')
            ->setWorkshopProducts($this->getRequest()->getPost('workshop_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsgridAction()
    {
        $this->_initWorkshop();
        $this->loadLayout();
        $this->getLayout()->getBlock('workshop.edit.tab.product')
            ->setWorkshopProducts($this->getRequest()->getPost('workshop_products', null));
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
        $fileName   = 'workshop.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_workshop_grid')
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
        $fileName   = 'workshop.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_workshop_grid')
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
        $fileName   = 'workshop.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_workshop_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/workshop/workshop');
    }
}
