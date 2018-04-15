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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Approval extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $docs = Mage::getModel('bs_curriculumdoc/curriculumdoc')->getCollection()
            ->addCurriculumFilter($row->getId())
            ->addFieldToFilter('cdoc_type',64)
            ->addFieldToFilter('cdoc_file', array('notnull'=>true))
            ->setOrder('cdoc_date', 'DESC')
            ->setOrder('entity_id', 'DESC')
        ;

        $doc = $docs->getFirstItem();
        if($doc->getId()){
            $sub = Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')->getOptionFormatted($doc->getCdocType());

            $rev = Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdocrev')->getOptionText($doc->getCdocRev());

            $file = Mage::helper('bs_curriculumdoc/curriculumdoc')->getFileBaseDir().'/'.$sub.'/'. $doc->getCdocFile();
            if(file_exists($file)){
                $url = Mage::helper('bs_curriculumdoc/curriculumdoc')->getFileBaseUrl() .'/'.$sub.'/'. $doc->getCdocFile();
                $date = '';
                if($doc->getCdocDate() != ''){
                    $date = Mage::getModel('core/date')->date("d/m/Y", $doc->getCdocDate());
                }

                $name = array();
                if($rev != ''){
                    $name[] = 'Rev '.$rev;
                }

                if($date != ''){
                    $name[] = '['.$date.']';
                }

                if(count($name)){
                    $name = implode(" ", $name);
                }else {
                    $name = 'Download';
                }

                return '<a href="'.$url.'" target="_blank">'.$name.'</a>';
            }

        }

        return '';
    }
}
