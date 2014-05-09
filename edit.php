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
require_once('edit_form.php');

 
global $DB, $OUTPUT, $PAGE, $USER;
 
// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);
$lotid = optional_param('lotid', 0, PARAM_INT);

// Load some variables
$userid = $USER->id;
$timeadded = time();
 
 
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_accesscode', $courseid);
}
$indexurl = new moodle_url('/blocks/accesscode/index.php', array('courseid' => $courseid, 'blockid' => $blockid));

require_login($course);
$PAGE->set_url('/blocks/accesscode/edit.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('createaccesscode', 'block_accesscode'));

$settingsnode = $PAGE->settingsnav->add(get_string('accesscode', 'block_accesscode'));
$editurl = new moodle_url('/blocks/accesscode/edit.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('manageaccesscode', 'block_accesscode'), $editurl);
$editnode->make_active();

if ($delete and $lotid) {
    $PAGE->url->param('delete', 1);
    if ($confirm) {
        $DB->delete_records('block_accesscode_lots', array('id'=>$lotid));
        $DB->delete_records('block_accesscode_codes', array('lotid'=>$lotid));
        redirect($indexurl);
    }
    $strheading = get_string('dellot', 'block_accesscode');
    $PAGE->navbar->add($strheading);
    $PAGE->set_title($strheading);
    $PAGE->set_heading($COURSE->fullname);
    echo $OUTPUT->header();
    echo $OUTPUT->heading($strheading);
    $yesurl = new moodle_url('/blocks/accesscode/edit.php', array('blockid'=>$blockid,'courseid'=>$courseid, 'lotid'=>$lotid, 'delete'=>1, 'confirm'=>1,'sesskey'=>sesskey()));
    $message = get_string('confirmdellot','block_accesscode', $lotid);
    $indexurl = new moodle_url('/blocks/accesscode/index.php', array('courseid' => $courseid, 'blockid' => $blockid));
    echo $OUTPUT->confirm($message, $yesurl, $indexurl);
    echo $OUTPUT->footer();
    die;
}
 
$loteditform = new lotedit_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['timeadded'] = $timeadded;
$toform['userid'] = $userid;

$loteditform->set_data($toform);

if($loteditform->is_cancelled()) {
    // Cancelled forms redirect to lot management main page.
    
    redirect($indexurl);
} else if ($fromform = $loteditform->get_data()) {
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
	redirect($indexurl);

} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    $loteditform->display();
    echo $OUTPUT->footer();
}
 

?>