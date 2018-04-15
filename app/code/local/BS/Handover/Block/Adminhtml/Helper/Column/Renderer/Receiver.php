<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_Handover_Block_Adminhtml_Helper_Column_Renderer_Receiver extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    /**
     * render the column
     *
     * @access public
     * @param Varien_Object $row
     * @return string
     * @author Bui Phong
     */
    public function render(Varien_Object $row)
    {
        $result = '';
        $receiver = $row->getReceiver();
        $id = trim($receiver);
        if(strlen($id) == 5){
            $id = "VAE".$id;
        }elseif (strlen($id) == 4){
            $id = "VAE0".$id;
        }
        $id = strtoupper($id);
        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
        if($customer->getId()) {
            $cus = Mage::getModel('customer/customer')->load($customer->getId());
            $name = $cus->getName();

            $div = $cus->getDivision();
            $departmentId = $cus->getGroupId();

            $group = Mage::getModel('customer/group')->load($departmentId);
            $dept = $group->getCustomerGroupCodeVi();

            $result = $name  .' - '.$id. ' - '. $div .' - '.$dept;
        }


        return $result;
    }
}
