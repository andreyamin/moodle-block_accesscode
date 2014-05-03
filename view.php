<?php
 
require_once('../../config.php');
require_once('accesscode_form.php');

 
global $DB, $OUTPUT, $PAGE;
 
// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
 
 
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

echo $OUTPUT->header();
$accesscode->display();
echo $OUTPUT->footer();

 
$accesscode->display();
?>