<?php
/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
  require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
  die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
  
global $smcFunc, $db_prefix, $modSettings, $sourcedir, $boarddir, $settings, $db_package_log, $package_cache;

// Define the hooks
$hook_functions = array(
  'integrate_pre_include' => '$sourcedir/Subs-rpgAge.php',
  'integrate_actions' => 'rpg_age',
  'integrate_load_theme' => 'rpgAgeHeader',
  'integrate_admin_areas' => 'rpgAgeAdminMenu',
  'integrate_menu_buttons' => 'rpgAgeMenuButton',
  'integrate_load_permissions' => 'rpgAgePermissions',
);
// Adding or removing them?
if (!empty($context['uninstalling']))
  $call = 'remove_integration_function';
else
  $call = 'add_integration_function';
// Do the deed
foreach ($hook_functions as $hook => $function)
  $call($hook, $function);
  
?>