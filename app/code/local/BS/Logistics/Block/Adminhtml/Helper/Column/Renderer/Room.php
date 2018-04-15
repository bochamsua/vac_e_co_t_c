<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Helper_Column_Renderer_Room extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
            'room_id'=>$row->getId()
        );


        return '<a style="color: #eb5e00;" href="'.$this->getUrl('adminhtml/register_schedule/new', $params).'" target="_blank">'.$this->_getValue($row).' - Click here for schedule</a>';
    }
}
