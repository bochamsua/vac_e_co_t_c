<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * parent entities column renderer
 * @category   BS
 * @package    BS_Logistics
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Helper_Column_Renderer_Examiner extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
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
        $base = $row->getExaminers();
        if (!$base) {
            return parent::render($row);
        }

        $examinerIds = explode(",", $base);
        $examiners = '';

        foreach ($examinerIds as $eId) {
            $customer = Mage::getModel('customer/customer')->load($eId);
            $examiners .= $customer->getName().', ';

        }

        $examiners = substr($examiners, 0, -2);




        return $examiners;

    }
}
