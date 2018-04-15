<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy admin grid block
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('instructorcopyGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructorcopy/instructorcopy')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructorcopy')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'c_from',
            array(
                'header'    => Mage::helper('bs_instructorcopy')->__('Copy from Curriculum'),
                'align'     => 'left',
                'index'     => 'c_from',
            )
        );
        

        $this->addColumn(
            'c_to',
            array(
                'header' => Mage::helper('bs_instructorcopy')->__('Copy to Curriculum'),
                'index'  => 'c_to',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_instructorcopy')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructorcopy')->__('Enabled'),
                    '0' => Mage::helper('bs_instructorcopy')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_instructorcopy')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_instructorcopy')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_instructorcopy')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_instructorcopy')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_instructorcopy')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('instructorcopy');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorcopy/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorcopy/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_instructorcopy')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_instructorcopy')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_instructorcopy')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructorcopy')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_instructorcopy')->__('Enabled'),
                                '0' => Mage::helper('bs_instructorcopy')->__('Disabled'),
                            )
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
     * @param BS_InstructorCopy_Model_Instructorcopy
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
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
