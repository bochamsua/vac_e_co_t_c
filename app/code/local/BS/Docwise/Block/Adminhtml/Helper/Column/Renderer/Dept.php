<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * parent entities column renderer
 * @category   BS
 * @package    BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Helper_Column_Renderer_Dept extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $dept = $row->getExamRequestDept();
        $dept = explode(",", $dept);

        $str = array();

        foreach ($dept as $id) {
            $department = Mage::getModel('customer/group')->load($id);
            $str[] = $department->getCustomerGroupNameVi();
        }

        return implode(",", $str);
    }
}
