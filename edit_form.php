<?php

/**
 * Access Code Management Block
 *
 * @package    block_accesscode
 * @copyright  2014 edubit.com.br
 * @author     Andre Yamin <andre@edubit.com.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("{$CFG->libdir}/formslib.php");
 
class lotedit_form extends moodleform {

 
    function definition() {

        global $DB;
    	 
        $mform =& $this->_form;
        $mform->addElement('header','displayinfo', get_string('createaccesscode', 'block_accesscode'));

        //Cohort id field
        $sql = 'SELECT * 
                FROM {cohort}
                WHERE idnumber 
                NOT IN (
                    SELECT cohortid
                    FROM {block_accesscode_lots}
                )';
        $cohorts = $DB->get_records_sql($sql);
        $cohortlist = array();
        foreach ($cohorts as $cohort) {
            $cohortlist[$cohort->idnumber] = $cohort->idnumber . ' - ' . $cohort->name;
        }
        $mform->addElement('select', 'cohortid', get_string('cohorid', 'block_accesscode'),$cohortlist);
        $mform->addRule('cohortid', null, 'required', null, 'client');

        //Lot size
        $mform->addElement('text', 'numcodes', get_string('lotsize', 'block_accesscode'));
        $mform->addRule('numcodes', null, 'required', 'numeric', 'client');

        //Lot description
        $mform->addElement('textarea', 'description', get_string('description', 'block_accesscode'), 'wrap="virtual" rows="20" cols="50"');

        // hidden elements
		$mform->addElement('hidden', 'blockid');
		$mform->addElement('hidden', 'courseid');
		$mform->addElement('hidden', 'timeadded');
		$mform->addElement('hidden', 'userid');


        $this->add_action_buttons();
    }
}