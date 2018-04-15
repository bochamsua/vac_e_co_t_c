<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Training admin grid block
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Training_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    protected $_defaultLimit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->setId('trainingGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Training_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_staffinfo/training')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('cus'=>'customer_entity_varchar'),'staff_id = cus.entity_id AND cus.attribute_id = 133','cus.value');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Training_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_staffinfo')->__('Name'),
                'renderer'  => 'bs_staffinfo/adminhtml_helper_column_renderer_customername',
                'index'     => 'name',
                'filter'    => false,
            )
        );
        $this->addColumn(
            'staff_id',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Staff ID'),
                'type'      => 'text',
                'renderer'  => 'bs_staffinfo/adminhtml_helper_column_renderer_customer',
                'filter_condition_callback' => array($this, '_customerFilter'),

            )
        );
        $this->addColumn(
            'course',
            array(
                'header'    => Mage::helper('bs_staffinfo')->__('Course'),
                'align'     => 'left',
                'index'     => 'course',
            )
        );
        


        $this->addColumn(
            'organization',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Country'),
                'index'  => 'organization',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'start_date',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Start Date'),
                'index'  => 'start_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'certificate',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Cert#/Evidence'),
                'index'  => 'certificate',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'keyword',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Keyword'),
                'index'  => 'keyword',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_staffinfo')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_staffinfo')->__('Enabled'),
                    '0' => Mage::helper('bs_staffinfo')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_staffinfo')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_staffinfo')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_staffinfo')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_staffinfo')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_staffinfo')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Training_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('training');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("customer/training/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("customer/training/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_staffinfo')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_staffinfo')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'use',
                array(
                    'label'=> Mage::helper('bs_instructorinfo')->__('Use for Approval'),
                    'url'  => $this->getUrl('*/*/massUse'),
                )
            );
            $this->getMassactionBlock()->addItem(
                'replace_country',
                array(
                    'label'      => Mage::helper('bs_instructorinfo')->__('Replace Country'),
                    'url'        => $this->getUrl('*/*/massReplaceCountry', array('_current'=>true)),
                    'additional' => array(
                        'replace_country' => array(
                            'name'   => 'replace_country',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructorinfo')->__('Text | Replace'),
                        )
                    ),
                    'confirm'  => Mage::helper('bs_staffinfo')->__('This action should be taken very carefully.Are you sure?')
                )
            );
            $this->getMassactionBlock()->addItem(
                'replace_course',
                array(
                    'label'      => Mage::helper('bs_instructorinfo')->__('Replace Course'),
                    'url'        => $this->getUrl('*/*/massReplaceCourse', array('_current'=>true)),
                    'additional' => array(
                        'replace_course' => array(
                            'name'   => 'replace_course',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructorinfo')->__('Text | Replace'),
                        )
                    ),
                    'confirm'  => Mage::helper('bs_staffinfo')->__('This action should be taken very carefully.Are you sure?')
                )
            );

            $this->getMassactionBlock()->addItem(
                'replace_cert',
                array(
                    'label'      => Mage::helper('bs_instructorinfo')->__('Replace Cert'),
                    'url'        => $this->getUrl('*/*/massReplaceCert', array('_current'=>true)),
                    'additional' => array(
                        'replace_cert' => array(
                            'name'   => 'replace_cert',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructorinfo')->__('Text | Replace'),
                        )
                    ),
                    'confirm'  => Mage::helper('bs_staffinfo')->__('This action should be taken very carefully.Are you sure?')
                )
            );
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_staffinfo')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_staffinfo')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_staffinfo')->__('Enabled'),
                                '0' => Mage::helper('bs_staffinfo')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        }
        return $this;
    }
    protected function _customerFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cus.value LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_StaffInfo_Model_Training
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return '#';//$this->getUrl('*/*/edit', array('id' => $row->getId()));
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
     * @return BS_StaffInfo_Block_Adminhtml_Training_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
