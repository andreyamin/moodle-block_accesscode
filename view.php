<?php
 
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
$PAGE->set_url('/blocks/accesscode/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('createaccesscode', 'block_accesscode'));

$settingsnode = $PAGE->settingsnav->add(get_string('accesscode', 'block_accesscode'));
$editurl = new moodle_url('/blocks/accesscode/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('manageaccesscode', 'block_accesscode'), $editurl);
$editnode->make_active();
 
$accesscode = new accesscode_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$accesscode->set_data($toform);

if($accesscode->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $accesscode->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
    // but for now we will just redirect back to the course main page.
	print_object($fromform);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    $accesscode->display();
    echo $OUTPUT->footer();
}
 
$accesscode->display();

?>