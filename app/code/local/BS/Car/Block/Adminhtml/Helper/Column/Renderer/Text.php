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
class BS_Car_Block_Adminhtml_Helper_Column_Renderer_Text extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $colName = $this->getColumn()->getColName();
        $limit = $this->getColumn()->getCharLimit();

        $value = $row->getData($colName);

        $value = explode("\r\n", $value);
        $value = implode("<br>", $value);

        if($limit > 0){
            $value = substr($value, 0, $limit);
            $value .= '...';
        }

        return $value;



    }
}
