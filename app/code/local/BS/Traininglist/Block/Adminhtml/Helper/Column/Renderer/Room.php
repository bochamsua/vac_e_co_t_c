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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Room extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $room = array();
        $roomString = '';
        if($schedule->count()){
            foreach ($schedule as $sche) {
                $room[$sche->getRoomId()] = 1;
            }

            $room = array_keys($room);

            $i=1;
            foreach ($room as $in) {
                $ins = Mage::getModel('bs_logistics/classroom')->load($in);
                $roomString .= $ins->getClassroomCode().', ';
                $i++;
            }
            $roomString = substr($roomString, 0, -2);

        }

        return $roomString;
    }
}
