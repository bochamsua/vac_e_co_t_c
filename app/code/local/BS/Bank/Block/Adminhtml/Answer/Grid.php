<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin grid block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Answer_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('answerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_bank/answer')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Answer_Grid
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
            'question_id',
            array(
                'header'    => Mage::helper('bs_bank')->__('Question'),
                'align'     => 'left',
                'index'     => 'question_id',
            )
        );
        

        $this->addColumn(
            'answer_correct',
            array(
                'header' => Mage::helper('bs_bank')->__('Correct'),
                'index'  => 'answer_correct',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_bank')->__('Yes'),
                    '0' => Mage::helper('bs_bank')->__('No'),
                )

            )
        );
        $this->addColumn(
            'answer_position',
            array(
                'header' => Mage::helper('bs_bank')->__('Position'),
                'index'  => 'answer_position',
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
     * @return BS_Bank_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('answer');
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
        $this->getMassactionBlock()->addItem(
            'answer_correct',
            array(
                'label'      => Mage::helper('bs_bank')->__('Change Correct'),
                'url'        => $this->getUrl('*/*/massAnswerCorrect', array('_current'=>true)),
                'additional' => array(
                    'flag_answer_correct' => array(
                        'name'   => 'flag_answer_correct',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_bank')->__('Correct'),
                        'values' => array(
                                '1' => Mage::helper('bs_bank')->__('Yes'),
                                '0' => Mage::helper('bs_bank')->__('No'),
                            )

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
     * @param BS_Bank_Model_Answer
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
     * @return BS_Bank_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
