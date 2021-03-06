<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for App Cat
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Model_Assessment_Attribute_Source_Appcat extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * get possible values
     *
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     * @author Bui Phong
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $options =  array(
            array(
                'label' => Mage::helper('bs_assessment')->__('A'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('bs_assessment')->__('B1'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('bs_assessment')->__('B2'),
                'value' => 3
            ),
            array(
                'label' => Mage::helper('bs_assessment')->__('C'),
                'value' => 4
            ),
            array(
                'label' => Mage::helper('bs_assessment')->__('ARS'),
                'value' => 5
            ),
            array(
                'label' => Mage::helper('bs_assessment')->__('L1'),
                'value' => 6
            ),
        );
        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;

    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Bui Phong
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get option text
     *
     * @access public
     * @param mixed $value
     * @return string
     * @author Bui Phong
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
