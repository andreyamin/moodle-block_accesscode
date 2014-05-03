<?php

require_once("{$CFG->libdir}/formslib.php");
 
class accesscode_form extends moodleform {

 
    function definition() {
    	global $USER;
 
        $mform =& $this->_form;
        $mform->addElement('header','displayinfo', get_string('createaccesscode', 'block_accesscode'));

        //Cohort id field
        $mform->addElement('text', 'cohortid', get_string('cohorid', 'block_accesscode'));
        $mform->addRule('cohortid', null, 'required', null, 'client');

        //Lot size
        $mform->addElement('text', 'numcodes', get_string('lotsize', 'block_accesscode'));
        $mform->addRule('numcodes', null, 'required', 'numeric', 'client');

        //Lot description
        $mform->addElement('textarea', 'desc', get_string('desc', 'block_accesscode'), 'wrap="virtual" rows="20" cols="50"');

        // hidden elements
		$mform->addElement('hidden', 'blockid');
		$mform->addElement('hidden', 'courseid');
		$mform->addElement('hidden', time());
		$mform->addElement('hidden', $USER->id);


        $this->add_action_buttons();
    }
}