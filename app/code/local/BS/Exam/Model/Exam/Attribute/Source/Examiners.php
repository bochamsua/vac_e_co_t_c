<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Examiner
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Model_Exam_Attribute_Source_Examiners extends Mage_Eav_Model_Entity_Attribute_Source_Table
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
        $customers = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToFilter('group_id', 23)
            ->addAttributeToFilter('retirement', 0)
            ->addAttributeToFilter('position', array('nlike'=>'%đốc%'))
            ->addAttributeToFilter('position', array('nlike'=>'%phòng%'))
        ;
        $options = array();
        if(count($customers)){
            foreach ($customers as $cus) {
                $customer = Mage::getModel('customer/customer')->load($cus->getId());
                $options[] = array(
                    'label' => $customer->getName(),
                    'value' => $customer->getId()
                );
            }


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
