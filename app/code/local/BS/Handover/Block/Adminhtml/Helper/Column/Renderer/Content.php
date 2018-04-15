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
class BS_Handover_Block_Adminhtml_Helper_Column_Renderer_Content extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $content = $row->getContent();
        $content = explode("\r\n", $content);
        $result = '';
        if(count($content)){
            $i=1;
            foreach ($content as $item) {
                $item = explode("---", $item);
                $item = trim($item[0]);
                $result .= $i.'. '.$item.'<br>';

                $i++;
            }
            $result = substr($result, 0, -4);


        }


        return $result;
    }
}
