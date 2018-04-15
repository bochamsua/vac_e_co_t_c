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
class BS_InstructorApproval_Block_Adminhtml_Helper_Column_Renderer_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $content = $row->getVaecoIds();
        $content = explode("\r\n", $content);
        $result = array();
        if(count($content)){
            foreach ($content as $item) {

                $item = explode("--", $item);
                $id = trim($item[0]);

                if (strlen($id) == 5) {
                    $id = "VAE" . $id;
                } elseif (strlen($id) == 4) {
                    $id = "VAE0" . $id;
                }
                $id = strtoupper($id);

                $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                if($cus->getId()){
                    $customer = Mage::getModel('customer/customer')->load($cus->getId());
                    $id = '<strong>'.$id.'</strong>'.'-'.$customer->getName();
                }

                $result[] = $id;
            }



        }
        $result = array_unique($result);
        $result = implode(", ", $result);


        return $result;
    }
}
