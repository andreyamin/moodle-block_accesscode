<?php

/**
 * Access Code Management Block
 *
 * @package    block_accesscode
 * @copyright  2014 edubit.com.br
 * @author     Andre Yamin <andre@edubit.com.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class block_accesscode extends block_base {
    
    public function init() {
        $this->title = get_string('accesscode', 'block_accesscode');
    }

    public function instance_allow_multiple() {
  		return false;
	}

    public function get_content() {

    	global $COURSE;
    
    	if ($this->content !== null) {
      		return $this->content;
    	}
 
	    $this->content         =  new stdClass;
	    $this->content->text   = '';
		$url = new moodle_url('/blocks/accesscode/index.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
		$this->content->footer = html_writer::link($url, get_string('createaccesscode', 'block_accesscode'));

	 
	    return $this->content;
    }

}