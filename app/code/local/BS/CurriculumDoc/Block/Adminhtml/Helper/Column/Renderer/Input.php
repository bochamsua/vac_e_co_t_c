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
class BS_CurriculumDoc_Block_Adminhtml_Helper_Column_Renderer_Input extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{

    public function render(Varien_Object $row)
    {

        $id = $row->getId();
        $doc = Mage::getModel('bs_curriculumdoc/curriculumdoc')->load($id);
        $name = $doc->getCdocName();

        $html = '<input type="text" style="width: 95% !important;"';
        $html .= 'name="docname[' . $id . ']" ';
        $html .= 'value="' . $name . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '" />';


        return $html;
    }
}
