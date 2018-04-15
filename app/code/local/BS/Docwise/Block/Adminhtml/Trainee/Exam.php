<?php

class BS_Docwise_Block_Adminhtml_Trainee_Exam extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('exam_grid');
        //$this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }



    /**
     * prepare the subject collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_docwise/examtrainee')->getCollection()->addFieldToFilter('trainee_id', $this->getTrainee()->getId())->setOrder('position', 'ASC');
        $collection->getSelect()->joinLeft(array('tn' => $collection->getTable('bs_docwise/exam')), 'main_table.exam_id = tn.entity_id', array('exam_code', 'exam_date','exam_type','cert_type') );

        //print_r($collection->getSelect()->__toString());
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'exam_code',
            array(
                'header' => Mage::helper('bs_subject')->__('Exam'),
                'index'  => 'exam_code',


            )
        );

        $this->addColumn(
            'exam_date',
            array(
                'header' => Mage::helper('bs_subject')->__('Date'),
                'index'  => 'exam_date',
                'type'    => 'date',
            )
        );

        $this->addColumn(
            'exam_type',
            array(
                'header' => Mage::helper('bs_docwise')->__('Exam Type'),
                'index'  => 'exam_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_docwise')->convertOptions(
                    Mage::getModel('bs_docwise/exam_attribute_source_examtype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
        'cert_type',
        array(
            'header' => Mage::helper('bs_docwise')->__('Certificate Type'),
            'index'  => 'cert_type',
            'type'  => 'options',
            'options' => Mage::helper('bs_docwise')->convertOptions(
                Mage::getModel('bs_docwise/exam_attribute_source_certtype')->getAllOptions(false)
            )

        )
    );





        return parent::_prepareColumns();
    }


    /**
     * get row url
     *
     * @access public
     * @param BS_Subject_Model_Subject
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/docwise_exam/edit', array('id' => $item->getExamId()));
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/examsGrid',
            array(
                'id'=>$this->getTrainee()->getId()
            )
        );
    }

    public function getTrainee()
    {
        return Mage::registry('current_trainee');
    }


}
