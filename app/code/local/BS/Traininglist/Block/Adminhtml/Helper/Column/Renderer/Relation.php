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
class BS_Traininglist_Block_Adminhtml_Helper_Column_Renderer_Relation extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $paramsData = $this->getColumn()->getData('params');
        $params = array();
        if (is_array($paramsData)) {
            foreach ($paramsData as $name=>$getter) {
                if (is_callable(array($row, $getter))) {
                    $params[$name] = call_user_func(array($row, $getter));
                }
            }
        }
        $staticParamsData = $this->getColumn()->getData('static');
        if (is_array($staticParamsData)) {
            foreach ($staticParamsData as $key=>$value) {
                $params[$key] = $value;
            }
        }

        //$today = Mage::getModel('core/date')->date("d-m-Y", time());
        //$finishDate = Mage::getModel('core/date')->date("d-m-Y", $row->getCourseFinishDate());

        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $todayDate = $now->format('Y-m-d');
        $todayDate .= ' 00:00:00.000000';

        $today = new DateTime($todayDate, $myTimezone);


        $finishDate = new DateTime($row->getCourseFinishDate(), $myTimezone);

        $interval = new DateInterval('P3D');

        $check = $today->diff($finishDate);
        $days = $check->days;

        $class = '';
        if($days == 0){
            $class = 'finished';
        }



        if(!$row->getDocuments() && $days > 3){
            $class .= ' document';
        }

        if(!$row->getHasReport() && $days > 10){
            $class .= ' report';
        }

        if(trim($row->getPlanDispatchNo()) == ''){
            $class .= ' no-dispatch';
        }







        return '<a class="'.$class.'" href="'.$this->getUrl($base, $params).'" target="_blank">'.$this->_getValue($row).'</a>';
    }
}
