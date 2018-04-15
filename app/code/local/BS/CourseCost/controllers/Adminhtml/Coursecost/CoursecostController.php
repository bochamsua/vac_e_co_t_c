<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost admin controller
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Adminhtml_Coursecost_CoursecostController extends BS_CourseCost_Controller_Adminhtml_CourseCost
{
    /**
     * init the course cost
     *
     * @access protected
     * @return BS_CourseCost_Model_Coursecost
     */
    protected function _initCoursecost()
    {
        $coursecostId  = (int) $this->getRequest()->getParam('id');
        $coursecost    = Mage::getModel('bs_coursecost/coursecost');
        if ($coursecostId) {
            $coursecost->load($coursecostId);
        }
        Mage::register('current_coursecost', $coursecost);
        return $coursecost;
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
        $this->_title(Mage::helper('bs_coursecost')->__('Course Cost'))
             ->_title(Mage::helper('bs_coursecost')->__('Course Costs'));
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
     * edit course cost - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $coursecostId    = $this->getRequest()->getParam('id');
        $coursecost      = $this->_initCoursecost();
        if ($coursecostId && !$coursecost->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_coursecost')->__('This course cost no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCoursecostData(true);
        if (!empty($data)) {
            $coursecost->setData($data);
        }
        Mage::register('coursecost_data', $coursecost);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_coursecost')->__('Course Cost'))
             ->_title(Mage::helper('bs_coursecost')->__('Course Costs'));
        if ($coursecost->getId()) {
            $this->_title($coursecost->getQty());
        } else {
            $this->_title(Mage::helper('bs_coursecost')->__('Add course cost'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new course cost action
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
     * save course cost - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('coursecost')) {
            try {
                $check = reset($data);
                if(!is_array($check)){//editing case

                    $data = $this->calculateCost($data);
                    $coursecost = $this->_initCoursecost();
                    $coursecost->addData($data);
                    $coursecost->save();
                }else {
                    $data = $this->sortData($data);
                    $i = 1;
                    foreach ($data as $item) {
                        $item['coursecost_order'] = $i;
                        $item = $this->calculateCost($item);
                        $coursecost = Mage::getModel('bs_coursecost/coursecost');
                        $coursecost->addData($item);
                        $coursecost->save();

                        $i++;
                    }
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.coursecost_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Course Cost was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCoursecostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was a problem saving the course cost.')
                );
                Mage::getSingleton('adminhtml/session')->setCoursecostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Unable to find course cost to save.')
        );
        $this->_redirect('*/*/');
    }

    public function calculateCost($data){

        $itemId = (int)$data['costitem_id'];
        $qty = (float)$data['qty'];
        $item = Mage::getModel('bs_coursecost/costitem')->load($itemId);

        if($qty == 0){
            $qty = $data['qty'] = 1;
        }
        
        if((int)$item->getItemCost() > 0 ){//this is fixed price, && intval($data['coursecost_cost']) == 0
            $totalCost = $qty * $item->getItemCost() * 1000;
            $data['coursecost_cost'] = $totalCost;
        }else { // make sure we muliply with thousand? No
            //$data['coursecost_cost'] = $data['coursecost_cost'] * 1000;
            
        }


        return $data;
    }

    public function sortData($array){
        $keys = array_keys($array);
        asort($keys);
        $newArray = array();
        foreach ($keys as $key) {
            $newArray[$key] = $array[$key];
        }

        return $newArray;
    }

    /**
     * delete course cost - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $coursecost = Mage::getModel('bs_coursecost/coursecost');
                $coursecost->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Course Cost was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting course cost.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Could not find course cost to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function clearAction()
    {
        try {

            $productId = $this->getRequest()->getParam('product_id');

            $shedules = Mage::getModel('bs_coursecost/coursecost')->getCollection()->addFieldToFilter('course_id', $productId);
            if($shedules->count()){
                $shedules->walk('delete');
            }

            $add = '';
            if($this->getRequest()->getParam('popup')){
                $add = '<script>window.opener.coursecost_gridJsObject.reload(); window.close()</script>';
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_coursecost')->__('All course costs were successfully deleted. %s', $add)
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_coursecosts'));
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_coursecosts'));
            return;
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('There was a problem saving the course cost.')
            );
            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_coursecosts'));
            return;
        }

        $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_coursecosts'));
    }

    public function copyAction()
    {
        $result = array();
        $fromSku = $this->getRequest()->getPost('sku');
        $to = $this->getRequest()->getPost('to_id');

        $course = Mage::getModel('catalog/product')->loadByAttribute('sku', $fromSku);

        if ($course->getId()) {

            try {
                $costs = Mage::getModel('bs_coursecost/coursecost')->getCollection()->addFieldToFilter('course_id', $course->getId());
                if ($costs->count()) {
                    foreach ($costs as $cost) {
                        $data = $cost->getData();
                        $data['entity_id'] = null;
                        $data['course_id'] = $to;

                        $newCost = Mage::getModel('bs_coursecost/coursecost');
                        $newCost->addData($data);
                        $newCost->save();
                    }
                }

                $result['url'] = '';

            } catch (Exception $e) {
                $result['status'] = $e->getMessage();
            }
        }


        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getGroupItemsAction(){
        $result = array();

        $groupId = $this->getRequest()->getPost('group_id');
        
        $result['items'] = '';
        if($groupId){

            $items = Mage::getModel('bs_coursecost/costitem')->getCollection()->addFieldToFilter('costgroup_id', $groupId);

            if($items->count()){
                $result['items'] .= '<select class=" select" name="coursecost[costitem_id]" id="coursecost_costitem_id">';

                foreach ($items as $item) {
                    $result['items'] .= '<option value="'.$item->getId().'">'.$item->getItemName().'</option>';
                    
                }

                $result['items'] .= '</select>';

            }

        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    /**
     * mass delete course cost - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $coursecostIds = $this->getRequest()->getParam('coursecost');
        if (!is_array($coursecostIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select course costs to delete.')
            );
        } else {
            try {
                foreach ($coursecostIds as $coursecostId) {
                    $coursecost = Mage::getModel('bs_coursecost/coursecost');
                    $coursecost->setId($coursecostId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Total of %d course costs were successfully deleted.', count($coursecostIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting course costs.')
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
        $coursecostIds = $this->getRequest()->getParam('coursecost');
        if (!is_array($coursecostIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select course costs.')
            );
        } else {
            try {
                foreach ($coursecostIds as $coursecostId) {
                $coursecost = Mage::getSingleton('bs_coursecost/coursecost')->load($coursecostId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course costs were successfully updated.', count($coursecostIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating course costs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass manage cost group change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCostgroupIdAction()
    {
        $coursecostIds = $this->getRequest()->getParam('coursecost');
        if (!is_array($coursecostIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select course costs.')
            );
        } else {
            try {
                foreach ($coursecostIds as $coursecostId) {
                $coursecost = Mage::getSingleton('bs_coursecost/coursecost')->load($coursecostId)
                    ->setCostgroupId($this->getRequest()->getParam('flag_costgroup_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course costs were successfully updated.', count($coursecostIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating course costs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass manage group items change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCostitemIdAction()
    {
        $coursecostIds = $this->getRequest()->getParam('coursecost');
        if (!is_array($coursecostIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select course costs.')
            );
        } else {
            try {
                foreach ($coursecostIds as $coursecostId) {
                $coursecost = Mage::getSingleton('bs_coursecost/coursecost')->load($coursecostId)
                    ->setCostitemId($this->getRequest()->getParam('flag_costitem_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course costs were successfully updated.', count($coursecostIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating course costs.')
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
        $fileName   = 'coursecost.csv';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_coursecost_grid')
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
        $fileName   = 'coursecost.xls';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_coursecost_grid')
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
        $fileName   = 'coursecost.xml';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_coursecost_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bs_coursecost/coursecost');
    }
}
