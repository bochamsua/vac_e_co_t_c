<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Helper_Column_Renderer_Relation extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        return '<a style="color: #eb5e00;" href="'.$this->getUrl($base, $params).'" target="_blank">'.$this->_getValue($row).'</a>';
    }
}
