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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Icontomorrow extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $base = $this->getColumn()->getBaseLink();
        if (!$base) {
            return parent::render($row);
        }


        //$today = Mage::getModel('core/date')->date("d-m-Y", time());
        //$finishDate = Mage::getModel('core/date')->date("d-m-Y", $row->getCourseFinishDate());

        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $tomorrowDate = date("Y-m-d", strtotime("+1 day"));

        //$todayDate = $now->format('Y-m-d');
        $tomorrowDate .= ' 00:00:00.000000';

        $today = new DateTime($tomorrowDate, $myTimezone);

        $startDate = new DateTime($row->getCourseStartDate(), $myTimezone);

        $finishDate = new DateTime($row->getCourseFinishDate(), $myTimezone);

        $interval = new DateInterval('P3D');

        $check = $today->diff($finishDate);
        $days = $check->days;


        $checkStart = $today->diff($startDate);
        $dayStart = $checkStart->days;

        $class = '';
        $add = '';


        if($dayStart == 0){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/start.png" style="float: left; margin-right: 5px;">';
        }

        if($days == 0){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/finished.png" style="float: left; margin-right: 5px;">';
        }


        if(trim($row->getPlanDispatchNo()) == ''){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/caution.png" style="float: left; margin-right: 5px;">';
        }












        return $add;
    }
}
