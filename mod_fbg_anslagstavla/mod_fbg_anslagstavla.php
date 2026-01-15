<?php
/**
 * @package    FBG Anslagstavla
 *
 * @author     Tomas Bolling Nilsson <tomas.bollingnilsson@falkenberg.se>
 * @copyright  2022
 * @license    NA
 * @link       falkenberg.se
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$document = Factory::getDocument();
$document->addScript('/fbg_apps/js/framework/jquery/jquery.min.js');
$document->addScript('/fbg_apps/js/framework/datatables/datatables.min.js');
$document->addScript('/fbg_apps/js/framework/datatables/dataTables.uikit.min.js');
$document->addScript('/modules/mod_fbg_anslagstavla/anslagstavla.js');
$document->addStyleSheet('/fbg_apps/js/framework/datatables/datatables.min.css');
// $document->addStyleSheet('/fbg_apps/js/framework/datatables/dataTables.uikit.min.css');

require ModuleHelper::getLayoutPath('mod_fbg_anslagstavla', $params->get('layout', 'default'));
