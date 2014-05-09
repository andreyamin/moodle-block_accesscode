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


require_login();

global $DB, $OUTPUT, $PAGE, $USER;
 
// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
 
 
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_accesscode', $courseid);
}
 
require_login($course);
$PAGE->set_url('/blocks/accesscode/index.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('managelot', 'block_accesscode'));

$settingsnode = $PAGE->settingsnav->add(get_string('accesscode', 'block_accesscode'));
$editurl = new moodle_url('/blocks/accesscode/index.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('manageaccesscode', 'block_accesscode'), $editurl);
$editnode->make_active();


$site = get_site();

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('lotcodemanagement','block_accesscode')); 
// Table
$lots = $DB->get_records('block_accesscode_lots');
$data = array();
foreach($lots as $lot) {
    $line = array();
    $line[] = $DB->get_field('cohort', 'name', array('idnumber'=>$lot->cohortid));
    $line[] = $lot->numcodes;
    $line[] = $DB->count_records('block_accesscode_codes', array('lotid'=>$lot->id, 'userid'=>0));
    $buttons = array();
    $buttons[] = html_writer::link(new moodle_url('/blocks/accesscode/edit.php', array('delete'=>'1','courseid'=>$courseid, 'blockid'=>$blockid, 'lotid'=>$lot->id)), 
        html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('t/delete'), 'alt'=>get_string('delete'), 'class'=>'iconsmall')));
    $line[] = implode(' ', $buttons);
    $data[] = $line;
}
$table = new html_table();
$table->head = array(get_string('cohortname', 'block_accesscode'),get_string('lotsize', 'block_accesscode'),get_string('availablecodes', 'block_accesscode') ,get_string('options'));
$table->data = $data;
echo html_writer::table($table);
echo $OUTPUT->single_button(new moodle_url('/blocks/accesscode/edit.php', array('blockid'=>$blockid, 'courseid'=>$courseid)), get_string('addnewlot','block_accesscode'));
$context = context_system::instance();
echo $OUTPUT->single_button(new moodle_url('/cohort/edit.php', array('contextid'=>$context->id)), get_string('addnewcohort','block_accesscode'));
echo $OUTPUT->footer();
?>