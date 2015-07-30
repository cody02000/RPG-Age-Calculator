<?php
/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

// First of all, we make sure we are accessing the source file via SMF so that people can not directly access the file. 
if (!defined('SMF'))
  die('Hack Attempt...');

function rpg_age(&$actionArray)
{
	$actionArray['rpg_age'] = array('rpgAge.php', 'rpgAgeMain');
}

function rpgAgeHeader()
{
  global $context, $settings, $modSettings;
  if($context['current_action']=='rpg_age') {
    
    if (!isset($context['jquery'])) {
      $context['html_headers'] .='<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>';
      $context['jquery']='rpgAge';
    }
    
    $cutOff=explode('-',$modSettings['rpg_age_school_age_cut_off']);
    $yearNames=explode(',',$modSettings['rpg_age_school_year_names']);
    $firstDay=explode('-',$modSettings['rpg_age_school_year_start']);
    $lastDay=explode('-',$modSettings['rpg_age_school_year_end']);
    $enddate = explode("-", $modSettings['rpg_end_date']);
    
    $context['html_headers'] .='<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/rpgAge.js?10"></script><script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
  var yearNames=' . json_encode($yearNames ) . ';
  var cutOff='. json_encode($cutOff ) . ';
  var firstDay='. json_encode($firstDay ) . ';
  var lastDay='. json_encode($lastDay ) . ';
  var numberYears='.$modSettings['rpg_age_number_school_years'].';
  var nowyear = "' . $enddate[0] . '";
  var nowmonth = "' . $enddate[1] . '";
  var nowday = "' . $enddate[2] . '";
// ]]></script>';
  }
}

function rpgAgeAdminMenu(&$admin_areas)
{
  global $txt,$scripturl;
  loadLanguage('rpgAge');
$admin_areas['config']['areas']['rpg_age']=array(
      'label' => $txt['rpg_age_admin'],
      'file' => 'rpgAgeAdmin.php',
	  'custom_url' => $scripturl . '?action=admin;area=rpg_age;sa=general',
      'function' => 'rpgAgeAdminMain',
      'icon' => 'boards.gif',
      'subsections' => array(
        'general' => array($txt['rpg_age_general'], 'admin_forum',),
        'menu' => array($txt['rpg_age_menu'], 'admin_forum',),
      ),
      );
}

function rpgAgePermissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
  global $context;
  
  // Add to the non-guest permissions
  $new = array('rpg_age_edit_settings');
  $context['non_guest_permissions'] = array_merge($context['non_guest_permissions'], $new);
  
  // And the permission list.
  $list = array(
    'rpg_age_edit_settings' => array(false, 'maintenance', 'administrate'),
  );
  $permissionList['membergroup'] = array_merge($permissionList['membergroup'], $list);
}

function rpgAgeProfile()
{
  global $context, $modSettings, $txt;
  if ($modSettings['rpg_age_profile_age']==True && !empty($context['member']['birth_date'])){
    list ($birth_year, $birth_month, $birth_day) = explode('-',$context['member']['birth_date']);
    $birth_array=getdate(mktime(0,0,0,$birth_month,$birth_day,$birth_year));

    list ($end_year, $end_month, $end_day)=explode('-', $modSettings['rpg_end_date']);
    $end_array=getdate(mktime(0,0,0,$end_month,$end_day,$end_year));
	
    list ($start_year, $start_month, $start_day)=explode('-', $modSettings['rpg_start_date']);
    $start_array=getdate(mktime(0,0,0,$start_month,$start_day,$start_year));

    $age=$end_array['year'] - $birth_array['year'];

	if ($birth_year > 4) {
		if ($start_array['year'] == $end_array['year']) {
			$context['member']['age']= $birth_array['yday']<=$start_array['yday'] ? $age : ($birth_array['yday'] <= $end_array['yday'] ?  $age-1 . ' ('.$age.' on '.date(rpgAgeDateFormat(),mktime(0,0,0,$birth_month,$birth_day,$end_year)).')' : $age-1);
			$context['member']['today_is_birthday'] = $birth_year <= 4 ? false : ($birth_array['yday'] >= $start_array['yday'] && $birth_array['yday'] <= $end_array['yday']);
			
		}
			
		else {
			if ($birth_array['yday'] >= $start_array['yday']) {
				$age-=2;
				$context['member']['age']= $age++ . ' (' . $age . ' on '.date(rpgAgeDateFormat(),mktime(0,0,0,$birth_month,$birth_day,$start_year)).')';
				$context['member']['today_is_birthday'] = True;
			}
			elseif ($birth_array['yday'] <= $end_array['yday']) {
				$context['member']['age']=$age-1 . ' ('.$age.' on '.date(rpgAgeDateFormat(),mktime(0,0,0,$birth_month,$birth_day,$end_year)).')';
				$context['member']['today_is_birthday'] = True;
			}
			else {
				$context['member']['age']=$age-1;
				$context['member']['today_is_birthday'] = FALSE;
			}
		}
	}
	else {
		$context['member']['age']=$txt['not_applicable'];
	}
	
  }
}

function rpgAgeDateFormat()
{
  global $user_info;
  switch ($user_info['time_format']):
  case '%B %d, %Y, %I:%M:%S %p':
  case '%B %d, %Y, %H:%M:%S':
    $dateFormat='F d, Y';
    break;
  case '%Y-%m-%d, %H:%M:%S':
    $dateFormat='Y-m-d';
    break;
  case '%d %B %Y, %H:%M:%S':
    $dateFormat='d-F-Y';
    break;
  case '%d-%m-%Y, %H:%M:%S':
    $dateFormat='d-m-Y';
    break;
endswitch;
return $dateFormat;
}

function rpgAgeMenuButton(&$buttons) {
  global $modSettings, $scripturl;
  
  list($button_enabled,$button_title,$sub_button)=explode(',',$modSettings['rpg_age_menu_settings']);
  
  $button=array(
    'rpg_age' => array(
      'title' => isset($button_title) ? $button_title : 'Age &amp; Year Calculator',
      'href' => $scripturl . '?action=rpg_age',
      'show' => !empty($button_enabled),),
      );
  if ($sub_button=='standalone') {
    $buttons+=$button;
  }
  else {
    $buttons[$sub_button]['sub_buttons']+=$button;
  }
}


?>