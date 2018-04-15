<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * parent entities column renderer
 * @category   BS
 * @package    BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Helper_Column_Renderer_Parent extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
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
        $options = $this->getColumn()->getOptions();
        $showMissingOptionValues = (bool)$this->getColumn()->getShowMissingOptionValues();
        if (!empty($options) && is_array($options)) {
            $value = $row->getData($this->getColumn()->getIndex());
            if (is_array($value)) {
                $res = array();
                foreach ($value as $item) {
                    if (isset($options[$item])) {
                        $res[] = '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.
                            $this->escapeHtml($options[$item]).
                            '</a>';
                    } elseif ($showMissingOptionValues) {
                        $res[] = '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.
                            $this->escapeHtml($item).
                            '</a>';
                    }
                }
                return implode('<br />', $res);
            } elseif (isset($options[$value])) {
                return '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.
                    $this->escapeHtml($options[$value]).
                    '</a>';
            } elseif (in_array($value, $options)) {
                return '<a href="'.$this->getUrl($base, $params).'" target="_blank">'.
                    $this->escapeHtml($value).
                    '</a>';
            }
        }
    }
}
