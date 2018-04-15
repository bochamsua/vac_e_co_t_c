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
class BS_TraineeDoc_Block_Adminhtml_Helper_Column_Renderer_Traineeid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $trainee = Mage::getResourceModel('bs_traineedoc/traineedoc_trainee_collection')
            ->addTraineedocFilter($row->getId());

        $str = array();
        if($trainee->count()){
            foreach ($trainee as $item) {

                $tn = Mage::getSingleton('bs_trainee/trainee')->load($item->getId());
                if($tn->getVaecoId() != ''){
                    $str[] = $tn->getVaecoId();
                }else {
                    $str[] = $tn->getTraineeCode();
                }

            }
        }

        return implode(", ", $str);

    }
}
