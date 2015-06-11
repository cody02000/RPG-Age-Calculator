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

function rpgAgeMain()
{

	// Second, give ourselves access to all the global variables we will need for this action
	global $context, $scripturl, $txt, $smcFunc;

	// Third, Load the specialty template for this action.
	loadTemplate('rpgAge');
	loadLanguage('rpgAge');

	//Fourth, Come up with a page title for the main page
	$context['page_title'] = $txt['rpg_age_page_title'];
	$context['page_title_html_safe'] = $smcFunc['htmlspecialchars'](un_htmlspecialchars($context['page_title']));


	//Fifth, define the navigational link tree to be shown at the top of the page.
	$context['linktree'][] = array(
  		'url' => $scripturl. '?action=rpg_age',
 		'name' => $txt['rpg_age'],
	);


$context['rpg_age_school_year']=rpgAgeSchoolYear();
$context['rpg_age_year_info']=rpgAgeYearInfo($context['rpg_age_school_year']['start']);
}

function rpgAgeSchoolYear()
{
  global $modSettings;
  $startDate=explode('-',$modSettings['rpg_start_date']);
  $endDate=explode('-',$modSettings['rpg_end_date']);
  $firstDay=explode('-',$modSettings['rpg_age_school_year_start']);
  $lastDay=explode('-',$modSettings['rpg_age_school_year_end']);
  
  if (($endDate[1] > $lastDay[0]) || ($endDate[1]==$lastDay[0] && $endDate[2]>$lastDay[1]))
    {
      $schoolYear['start']=$endDate[0];
      $schoolYear['end']=$endDate[0]+1;
    }
  else {
      $schoolYear['start']=$endDate[0]-1;
      $schoolYear['end']=$endDate[0];
  }

  if (($startDate[1]<$lastDay[0]) || ($startDate[1]==$lastDay[0] && $startDate[2]<=$lastDay[1]))
  {
      $schoolYear['start']=$startDate[0]-1;
      $schoolYear['end']=$startDate[0];
  }
  
  return $schoolYear;
};


function rpgAgeYearInfo($currentYear)
{
  global $txt,$modSettings;
  
  date_default_timezone_set('UTC');
  $dateformat=rpgAgeDateFormat();
  $schoolYear=0;
  $firstYear=$currentYear;
  $firstYearPlus=$currentYear+1;
  $birthYear=$currentYear-$modSettings['rpg_age_school_start_age']-1;
  $birthYearPlus=$birthYear+1;
  $gradYear=$currentYear+($modSettings['rpg_age_number_school_years']-1);
  $gradYearPlus=$gradYear+1;
  $age=$modSettings['rpg_age_school_start_age'];
  $cutOff=explode('-',$modSettings['rpg_age_school_age_cut_off']);
  
  $yearLabel=explode(',',$modSettings['rpg_age_school_year_names']);
  $yearInfo='';
  
  while($schoolYear<=($modSettings['rpg_age_number_school_years']-1)) {
    $yearInfo.='<div class="post_wrapper"><div class="poster"><h4>' . $yearLabel[$schoolYear] . '</h4></div>' .PHP_EOL . '
    <dl class="postarea settings">' . PHP_EOL . '
      <dt>' . $txt['rpg_age_birthday'] . '</dt>' . PHP_EOL . '
        <dd>'.date($dateformat, mktime(0, 0, 0, $cutOff[0], $cutOff[1], $birthYear--)). '&mdash; ' .date($dateformat, mktime(0, 0, 0, $cutOff[0], $cutOff[1]-1, $birthYearPlus--)). '</dd>' . PHP_EOL . '
      <dt>' . $yearLabel[0] . '</dt>'.PHP_EOL.'
        <dd>' . $firstYear-- . '&mdash;' . $firstYearPlus-- . $txt['rpg_age_school_year'] . '</dd>' . PHP_EOL . '
      <dt>' . $txt['rpg_age_grad_label'] . '</dt>' . PHP_EOL . '
        <dd>' . $gradYear-- . '&mdash;' . $gradYearPlus-- . $txt['rpg_age_school_year'] . '</dd>' . PHP_EOL . '
      <dt>'.$txt['rpg_age_age'].'</dt>'.PHP_EOL.'
        <dd>' . $age++ . ' or ' . $age . $txt['rpg_age_character_arrives'] . '</dd>' . PHP_EOL . '
      </dl></div>' . PHP_EOL;
  
    $schoolYear++;
  }
  $yearInfo.='';
  return $yearInfo;
}
?>