<?php

/**
 * Access Code Management Block
 *
 * @package    block_accesscode
 * @copyright  2014 edubit.com.br
 * @author     Andre Yamin <andre@edubit.com.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once('../../config.php');
require_once('accesscode_form.php');

 
global $DB, $OUTPUT, $PAGE, $USER;
 
// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

// Load some variables
$userid = $USER->id;
$timeadded = time();
 
 
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_accesscode', $courseid);
}
 
require_login($course);
$PAGE->set_url('/blocks/accesscode/edit.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('createaccesscode', 'block_accesscode'));

$settingsnode = $PAGE->settingsnav->add(get_string('accesscode', 'block_accesscode'));
$editurl = new moodle_url('/blocks/accesscode/edit.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('manageaccesscode', 'block_accesscode'), $editurl);
$editnode->make_active();
 
$accesscode = new accesscode_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['timeadded'] = $timeadded;
$toform['userid'] = $userid;

$accesscode->set_data($toform);

if($accesscode->is_cancelled()) {
    // Cancelled forms redirect to lot management main page.
    $indexurl = new moodle_url('/blocks/accesscode/index.php', array('courseid' => $courseid, 'blockid' => $blockid));
    redirect($indexurl);
} else if ($fromform = $accesscode->get_data()) {
    // Here goes the code executed when the form is submited
    if (!$lotid = $DB->insert_record('block_accesscode_lots', $fromform)) {
    	print_error('inserterror', 'block_accesscode');
	}

	for ($i = 1; $i <= $fromform->numcodes; $i++) {
		$coderecord = new stdClass;
		$coderecord->lotid = $lotid;
		$coderecord->accesscode = $fromform->cohortid . str_pad($i, 3, '0', STR_PAD_LEFT);
    	if (!$DB->insert_record('block_accesscode_codes', $coderecord)) {
    		print_error('inserterror', 'block_accesscode');
		}

	}
	$indexurl = new moodle_url('/blocks/accesscode/index.php', array('courseid' => $courseid, 'blockid' => $blockid));
	redirect($indexurl);

} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    $accesscode->display();
    echo $OUTPUT->footer();
}
 

?>