<?php
defined('_JEXEC') or die;

JLoader::register('JShopTeplohodHelper', JPATH_ADMINISTRATOR . '/components/com_jshopteplohod/helpers/jshopteplohod.php');

/**
 * Content HTML helper
 *
 * @since  3.0
 */
abstract class JHtmlContentAdministrator
{
	/**
	 * Show the feature/unfeature links
	 *
	 * @param   int      $value      The state value
	 * @param   int      $i          Row number
	 * @param   boolean  $canChange  Is user allowed to change?
	 *
	 * @return  string       HTML code
	 */
	public static function create_your($value = 0, $i, $canChange = true)
	{
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states = array(
			0 => array('unfeatured', 'menutypes.create_yourpublish', 'Поставить создать своё', 'Поставить создать своё'),
			1 => array('featured', 'menutypes.create_yourunpublish', 'Убрать создать своё', 'Убрать создать своё'),
		);
		$state = JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon  = $state[0];
        
		$html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
			. ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><span class="icon-' . $icon . '"></span></a>';
		

		return $html;
	}
    
	/**
	 * Show the feature/unfeature links
	 *
	 * @param   int      $value      The state value
	 * @param   int      $i          Row number
	 * @param   boolean  $canChange  Is user allowed to change?
	 *
	 * @return  string       HTML code
	 */
	public static function napitki($value = 0, $i, $canChange = true)
	{
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states = array(
			0 => array('unfeatured', 'producttypes.napitkipublish', 'Отметить как напитки', 'Отметить как напитки'),
			1 => array('featured', 'producttypes.napitkiunpublish', 'Убрать отметку', 'Убрать отметку'),
		);
		$state = JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon  = $state[0];
        
		$html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
			. ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><span class="icon-' . $icon . '"></span></a>';
		

		return $html;
	}
	
}
