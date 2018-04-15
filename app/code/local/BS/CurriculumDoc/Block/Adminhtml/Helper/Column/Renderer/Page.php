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
class BS_CurriculumDoc_Block_Adminhtml_Helper_Column_Renderer_Page extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{

    public function render(Varien_Object $row)
    {

        $id = $row->getId();
        $doc = Mage::getModel('bs_curriculumdoc/curriculumdoc')->load($id);
        $name = $doc->getCdocPage();

        $html = '<input type="text"';
        $html .= 'name="docpage[' . $id . ']" ';
        $html .= 'value="' . $name . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '" />';


        return $html;
    }
}
