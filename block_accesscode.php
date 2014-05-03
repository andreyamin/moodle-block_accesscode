<?php
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
	    $this->content->text   = 'Gerar códigos de acesso';
		$url = new moodle_url('/blocks/accesscode/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
		$this->content->footer = html_writer::link($url, get_string('createaccesscode', 'block_accesscode'));

	 
	    return $this->content;
	}

}