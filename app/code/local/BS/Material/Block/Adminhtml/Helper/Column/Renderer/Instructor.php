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
class BS_Material_Block_Adminhtml_Helper_Column_Renderer_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $instructor = Mage::getResourceModel('bs_material/instructordoc_instructor_collection')
            ->addInstructordocFilter($row->getId());

        $str = array();
        if($instructor->count()){
            foreach ($instructor as $item) {

                $ins = Mage::getSingleton('bs_instructor/instructor')->load($item->getId());
                $str[] = $ins->getIname();
            }
        }

        return implode(", ", $str);

    }
}
