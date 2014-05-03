<?php

require_once("{$CFG->libdir}/formslib.php");
 
class accesscode_form extends moodleform {
 
    function definition() {
 
        $mform =& $this->_form;
        $mform->addElement('header','displayinfo', get_string('createaccesscode', 'block_accesscode'));
    }
}