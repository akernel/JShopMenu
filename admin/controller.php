<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
/**
 * Общий контроллер компонента JShopMenu.
 */
class JShopMenuController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since   1.6
	 */
	protected $default_view = 'categories';
    
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
    /**
     * Задача по отображению.
     *
     * @param   boolean  $cachable   Если true, то представление будет закешировано.
     * @param   array    $urlparams  Массив безопасных url-параметров и их валидных типов переменных.
     *
     * @return  void
     */
    public function display($cachable = false, $urlparams = array()) 
    {
	   $view   = $this->input->get('view', 'categories');
		// Устанавливаем подменю.
		JShopMenuHelper::addSubmenu($view);
                
		return parent::display();
    }
}