<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin grid block
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskinstructor_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('taskinstructorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_tasktraining/taskinstructor')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_tasktraining')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_tasktraining')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tasktraining')->__('Enabled'),
                    '0' => Mage::helper('bs_tasktraining')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_tasktraining')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_tasktraining')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_tasktraining')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_tasktraining')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_tasktraining')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('taskinstructor');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskinstructor/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskinstructor/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_tasktraining')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_tasktraining')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_tasktraining')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tasktraining')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_tasktraining')->__('Enabled'),
                                '0' => Mage::helper('bs_tasktraining')->__('Disabled'),
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
     * @param BS_Tasktraining_Model_Taskinstructor
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
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
