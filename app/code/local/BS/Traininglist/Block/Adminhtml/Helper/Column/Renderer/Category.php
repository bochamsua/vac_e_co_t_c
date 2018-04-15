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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($row->getEntityId());
        $cats = $curriculum->getSelectedCategories();
        $allCats = '';
        $i=1;
        foreach($cats as $cat)
        {
            $_category = Mage::getModel('catalog/category')->load($cat->getId());

            if($_category->getParentId() != 2){
                $allCats.= $i.'. '.$_category->getName().'<br>';

                $i++;
            }



        }
        $allCats = substr($allCats, 0, -4);
        return $allCats;



    }
}
