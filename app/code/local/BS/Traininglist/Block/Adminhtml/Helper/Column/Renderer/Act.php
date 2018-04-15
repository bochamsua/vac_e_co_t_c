<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Material
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Act extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $id = $row->getId();
        $editLink = $this->getColumn()->getEditLink();
        $deleteLink = $this->getColumn()->getDeleteLink();
        return '<a style="color: #eb5e00;" href="#" onclick="window.open(\''.$this->getUrl($editLink, array('id'=>$id, 'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;">Edit</a> | <a style="color: #eb5e00;" href="#" onclick="window.open(\''.$this->getUrl($deleteLink, array('id'=>$id, 'popup'=>true)).'\',\'\',\'width=10,height=10,resizable=0,scrollbars=0\'); return false;">Delete</a>';
    }
}
