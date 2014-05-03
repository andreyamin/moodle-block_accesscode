<?php

require_once("{$CFG->libdir}/formslib.php");
 
class accesscode_form extends moodleform {

 
    function definition() {
    	 
        $mform =& $this->_form;
        $mform->addElement('header','displayinfo', get_string('createaccesscode', 'block_accesscode'));

        //Cohort id field
        $mform->addElement('text', 'cohortid', get_string('cohorid', 'block_accesscode'));
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