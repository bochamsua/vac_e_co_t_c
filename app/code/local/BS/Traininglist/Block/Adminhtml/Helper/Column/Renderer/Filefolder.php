<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Filefolder extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $result = array();
        //$course = Mage::getModel('catalog/product')->load($row->getEntityId());
        $cur = Mage::getModel('bs_logistics/filefolder')->getCollection()->addProductFilter($row->getEntityId());

        if($cur->count()){
            foreach ($cur as $item) {
                $result[] = strtoupper($item->getFilefolderCode());
            }
        }


        return implode(", ", $result);




    }
}
