<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin grid block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Question_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('questionGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_bank/question')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_bank')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'subject_id',
            array(
                'header'    => Mage::helper('bs_bank')->__('Subject'),
                'index'     => 'subject_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_bank/subject_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_bank/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getSubjectId'
                ),
                'base_link' => 'adminhtml/bank_subject/edit'
            )
        );
        $this->addColumn(
            'curriculum_id',
            array(
                'header'    => Mage::helper('bs_bank')->__('Curriculum'),
                'align'     => 'left',
                'index'     => 'curriculum_id',
            )
        );
        

        $this->addColumn(
            'question_usage',
            array(
                'header' => Mage::helper('bs_bank')->__('Question Usage'),
                'index'  => 'question_usage',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_bank')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_bank')->__('Enabled'),
                    '0' => Mage::helper('bs_bank')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_bank')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_bank')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_bank')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_bank')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_bank')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('question');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('bs_bank')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('bs_bank')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('bs_bank')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_bank')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('bs_bank')->__('Enabled'),
                            '0' => Mage::helper('bs_bank')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $values = Mage::getResourceModel('bs_bank/subject_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'subject_id',
            array(
                'label'      => Mage::helper('bs_bank')->__('Change Subject'),
                'url'        => $this->getUrl('*/*/massSubjectId', array('_current'=>true)),
                'additional' => array(
                    'flag_subject_id' => array(
                        'name'   => 'flag_subject_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_bank')->__('Subject'),
                        'values' => $values
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Bank_Model_Question
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
     * @return BS_Bank_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
