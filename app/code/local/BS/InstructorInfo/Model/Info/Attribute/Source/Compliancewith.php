<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Compliance With
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Model_Info_Attribute_Source_Compliancewith extends Mage_Eav_Model_Entity_Attribute_Source_Table
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
                'label' => Mage::helper('bs_instructorinfo')->__('ATO'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('AMO'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Others'),
                'value' => 3
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

    public function getOptionValue($text)
    {
        $text = strtolower($text);
        $options = $this->getOptionsArray();

        $id = null;
        foreach ($options as $key=>$value) {
            if(strtolower($value) == $text){
                $id = $key;
            }

        }
        return $id;
    }
}
