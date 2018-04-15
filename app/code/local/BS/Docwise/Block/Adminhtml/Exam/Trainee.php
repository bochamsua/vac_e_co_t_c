<?php

class BS_Docwise_Block_Adminhtml_Exam_Trainee extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('trainee_grid');
        //$this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('import_trainee_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Trainees'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/docwise_importtrainee/new', array('_current'=>false,'popup'=>true, 'exam'=>$this->getExam()->getId())).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );
        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('import_trainee_button');

            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

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
        $collection = Mage::getModel('bs_docwise/examtrainee')->getCollection()->addFieldToFilter('exam_id', $this->getExam()->getId())->setOrder('position', 'ASC');
        $collection->getSelect()->joinLeft(array('tn' => $collection->getTable('bs_docwise/trainee')), 'main_table.trainee_id = tn.entity_id', array('trainee_name', 'vaeco_id') );

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
            'trainee_name',
            array(
                'header' => Mage::helper('bs_docwise')->__('Trainee'),
                'index'  => 'trainee_name',
                'filter'    => false,
                'sortable'  => false,


            )
        );

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_docwise')->__('VAECO ID'),
                'align'  => 'left',
                'index'  => 'vaeco_id',
                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'score',
            array(
                'header'         => Mage::helper('bs_docwise')->__('Score'),
                'name'           => 'score',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_docwise/adminhtml_helper_column_renderer_input',
                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'questionno',
            array(
                'header'         => Mage::helper('bs_docwise')->__('Question No'),
                'name'           => 'questionno',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_docwise/adminhtml_helper_column_renderer_questionno',
                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '100px',
                'type'      => 'text',
                'link' => '*/docwise_exam/deleteTrainee',
                'parent'    => $this->getExam()->getId(),
                'renderer'  => 'bs_traininglist/adminhtml_helper_column_renderer_remove',
                'filter'    => false,
                'sortable'  => false
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
        return '#';
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
            '*/*/traineesGrid',
            array(
                'id'=>$this->getExam()->getId()
            )
        );
    }

    public function getExam()
    {
        return Mage::registry('current_exam');
    }


}
