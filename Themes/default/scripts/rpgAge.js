/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

function rpgAgeCalculateAge() {
    
    var birthyear = $('#birthYear').val();
    var birthmonth = $('#birthMonth').val();
    var birthday = $('#birthDay').val();
    
    if (birthyear >= nowyear) { // check valid year
        
        rpgAgeShowMessage('#birthYear', 'Year must be before ' + nowyear + '.');
        return false;
    }
    if (birthmonth < 1 || birthmonth > 12) { // check valid month 1-12
        rpgAgeShowMessage('#birthMonth', 'Invalid Month, must be 1-12.');
        return false;
    }
    
    var dim = rpgAgeDaysInMonth(birthmonth, birthyear);
    if (birthday < 1 || birthday > dim) { // check valid date according to month
        rpgAgeShowMessage('#birthDay', 'That day is not possible in ' + rpgAgeGetMonthName(birthmonth) + ' ' + birthyear + '.');
        return false;
    }
    
    //calculate base age
    var age = nowyear - birthyear;
    if (birthmonth > nowmonth) { //if birth month is greater than current month, birthday hasnt been reached yet, subtract 1 from base age calculations
        age--;
    }
    if (birthmonth == nowmonth && birthday > nowday) { //if it is the birth month and birth day is greater then the birthday has not been reached yet, subtract 1 from base age calculation.
        age--;
    }
    $('#ageDisplay').html(age);
    rpgAgeCalculateYear(birthyear, birthmonth, birthday);
}

function rpgAgeCalculateYear(birthyear, birthmonth, birthday) {
    var firstyear = parseInt(birthyear,10) + 11;
    var yearlabel="";
    if ((birthmonth > cutOff[0]) || (birthmonth == cutOff[0] && birthday >= cutOff[1])) {
        firstyear++;
    }
    var year = nowyear - firstyear;
    if ((nowmonth > lastDay[0]) || (nowmonth==lastDay[0] && nowday > lastDay[1])) {
        year++;
    }
    if (year > numberYears) {
        var graduateyear = firstyear + numberYears;
        schoolyear = graduateyear;
        yearlabel = "Graduated ";
    } else {
      year--;
      var schoolyear=yearNames[year];
    }
    $('#yearDisplay').html(yearlabel + schoolyear);
    
}

function rpgAgeGetMonthName(monthNumber) {
    var months = ['January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'];
    return months[monthNumber - 1];
}

function rpgAgeDaysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

function rpgAgeShowMessage(box, msg) {
    if ($(box).val() !== "") {
        $('#rpg-error').html(msg);
        $(box).val("");
        $(box).focus();
		$('#rpg-error').delay(2000).fadeOut(2000, rpgComplete);
    }
}

function rpgComplete() {
    $('#rpg-error').html('');
	$('#rpg-error').show();
  }
//$('#birthYear').blur(rpgAgeCalculateAge);
//$('#birthMonth').blur(rpgAgeCalculateAge);
//$('#birthDay').blur(rpgAgeCalculateAge);