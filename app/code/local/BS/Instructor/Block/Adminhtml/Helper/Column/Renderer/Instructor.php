<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Helper_Column_Renderer_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $product = Mage::registry('current_product');

        $params = array(
            'product_id'=>$product->getId(),
            'instructor_id'=>$row->getId()
        );


        return '<a style="color: #eb5e00;" href="'.$this->getUrl('adminhtml/register_schedule/new', $params).'" target="_blank">'.$this->_getValue($row).' - Click here for schedule</a>';
    }
}
