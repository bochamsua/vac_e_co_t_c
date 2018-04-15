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
class BS_Material_Block_Adminhtml_Helper_Column_Renderer_Download extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $doc = Mage::getModel('bs_material/instructordoc')->load($row->getId());

        $file = $doc->getIdocFile();

        $url = Mage::helper('bs_material/instructordoc')->getFileBaseUrl();
        $dir = Mage::helper('bs_material/instructordoc')->getFileBaseDir();

        $sub = Mage::getModel('bs_material/instructordoc_attribute_source_idoctype')->getOptionFormatted($doc['idoc_type']);

        $url .= '/'.$sub.'/'.$file;

        $fullPath = $dir. '/'.$sub.'/'.$file;

        $filesize = Mage::helper('bs_coursedoc')->getFileSize($fullPath);
        $filesize = ' ['.$filesize.']';

        $showPage = $this->getRequest()->getParam('showpage', false);

        $pages = '';
        if($showPage){
            $page = Mage::helper('bs_coursedoc')->countPdfPages($fullPath);
            if($page){
                $pages = ' <span style="color: green; font-style: italic;">['.$page.' pages]</span>';
            }
        }

        $listRar = $this->getRequest()->getParam('listrar', false);

        $rars = '';
        if($listRar){
            $rar = Mage::helper('bs_coursedoc')->getRarFiles($fullPath);
            if($rar){
                $rars = ' <span style="color: green; font-style: italic;">'.$rar.'</span>';
            }
        }


        //$file = strstr($file,'.',true);
        $name = str_replace(array("-","_")," ",$file);
        $name = ucwords($name);


        return '<a style="color: #eb5e00;" href="'.$url.'" target="_blank">'.$name.'</a>'.$filesize.$pages.$rars;
    }
}
