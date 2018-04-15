<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Instructor Feedback admin grid block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Ifeedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('ifeedbackGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_kst/ifeedback')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_kst')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_kst')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        

        $this->addColumn(
            'task_id',
            array(
                'header' => Mage::helper('bs_kst')->__('Task'),
                'index'  => 'task_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'trainee_id',
            array(
                'header' => Mage::helper('bs_kst')->__('Trainee'),
                'index'  => 'trainee_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'criteria_one',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria one'),
                'index'  => 'criteria_one',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriaone')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'criteria_two',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria two'),
                'index'  => 'criteria_two',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriatwo')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'criteria_three',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria Three'),
                'index'  => 'criteria_three',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriathree')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'criteria_four',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria Four'),
                'index'  => 'criteria_four',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafour')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'criteria_five',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria Five'),
                'index'  => 'criteria_five',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafive')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'criteria_six',
            array(
                'header' => Mage::helper('bs_kst')->__('Criteria Six'),
                'index'  => 'criteria_six',
                'type'  => 'number',
                'options' => Mage::helper('bs_kst')->convertOptions(
                    Mage::getModel('bs_kst/ifeedback_attribute_source_criteriasix')->getAllOptions(false)
                )

            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_kst')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_kst')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_kst')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_kst')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_kst')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('ifeedback');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/ifeedback/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/ifeedback/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_kst')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_kst')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_kst')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_kst')->__('Enabled'),
                                '0' => Mage::helper('bs_kst')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'criteria_one',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria one'),
                'url'        => $this->getUrl('*/*/massCriteriaOne', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_one' => array(
                        'name'   => 'flag_criteria_one',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria one'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriaone')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'criteria_two',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria two'),
                'url'        => $this->getUrl('*/*/massCriteriaTwo', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_two' => array(
                        'name'   => 'flag_criteria_two',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria two'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriatwo')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'criteria_three',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria Three'),
                'url'        => $this->getUrl('*/*/massCriteriaThree', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_three' => array(
                        'name'   => 'flag_criteria_three',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria Three'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriathree')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'criteria_four',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria Four'),
                'url'        => $this->getUrl('*/*/massCriteriaFour', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_four' => array(
                        'name'   => 'flag_criteria_four',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria Four'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafour')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'criteria_five',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria Five'),
                'url'        => $this->getUrl('*/*/massCriteriaFive', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_five' => array(
                        'name'   => 'flag_criteria_five',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria Five'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafive')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'criteria_six',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Criteria Six'),
                'url'        => $this->getUrl('*/*/massCriteriaSix', array('_current'=>true)),
                'additional' => array(
                    'flag_criteria_six' => array(
                        'name'   => 'flag_criteria_six',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Criteria Six'),
                        'values' => Mage::getModel('bs_kst/ifeedback_attribute_source_criteriasix')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_KST_Model_Ifeedback
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
