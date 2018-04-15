<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Helper_Column_Renderer_Document extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $productId = Mage::registry('current_product')->getId();
        $traineeId = $row->getId();

        $url = $this->getUrl('*/traineedoc_traineedoc/new', array('trainee_id'=>$traineeId, 'product_id'=>$productId, 'popup'=>true));


        return '<a style="color: #eb5e00;" href="#" onclick="'.'window.open(\''.$url.'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'.'">Add Doc</a>';
    }
}
