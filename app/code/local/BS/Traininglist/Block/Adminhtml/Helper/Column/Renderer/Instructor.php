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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        $schedule = Mage::getModel('bs_register/schedule')->getCollection()
            ->addFieldToFilter('course_id', $row->getEntityId())
            ->addFieldToFilter('schedule_start_date', array('to'=>$currentDate))
            ->addFieldToFilter('schedule_finish_date', array('from'=>$currentDate))
        ;
        $instructor = array();
        $instring = '';
        if($schedule->count()){
            //$i=1;
            foreach ($schedule as $sche) {
                $instructor[$sche->getInstructorId()] = 1;
                //$ins = Mage::getModel('bs_instructor/instructor')->load($sche->getInstructorId());
                //$instructor .= $i.'. '.$ins->getIname().'<br>';
                //$i++;
            }
            //$instructor = substr($instructor, -0, -4);

            $instructor = array_keys($instructor);

            $i=1;
            foreach ($instructor as $in) {
                $ins = Mage::getModel('bs_instructor/instructor')->load($in);
                $instring .= $ins->getIname().', ';
                $i++;
            }
            $instring = substr($instring, 0, -2);

        }

        return $instring;
    }
}
