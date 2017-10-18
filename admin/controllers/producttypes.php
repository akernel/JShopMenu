<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controlleradmin Joomla.
jimport('joomla.application.component.controlleradmin');
 
/**
 * Producttypes контроллер.
 */
class JShopMenuControllerProducttypes extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	ContentControllerArticles
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		// Articles default form can come from the articles or featured view.
		// Adjust the redirect view on the value of 'view' in the request.
		if (JRequest::getCmd('view') == 'napitkipublish') {
			$this->view_list = 'napitkipublish';
		}
		parent::__construct($config);

		$this->registerTask('napitkiunpublish',	'napitkipublish');
	}

	/**
	 * Method to toggle the featured setting of a list of articles.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function napitkipublish()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('napitkipublish' => 1, 'napitkiunpublish' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');
        
		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();
            
            $id = $ids[0];
			// Publish the items.
			if (!$model->napitki($id, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_jshopmenu&view=producttypes');
	}
    /**
     * Прокси метод для getModel.
     *
     * @param   string  $name    Имя класса модели.
     * @param   string  $prefix  Префикс класса модели.
     *
     * @return  object  Объект модели.
     */
    public function getModel($name = 'Producttype', $prefix = 'JShopMenuModel')
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
?>