<?php
class FzController {
	
	public $render_class		= null;
	public $view			    = "index.html";
	public $user_id				= null; // User_id when user the authentication class
    public $request				= null;
    public $data				= null;
    public $addons				= null;
    //public $layout_type			= "json";
    
    public $result				= array();
    public $error				= 0;
    public $title				= "";
    //public $notorm              = null;

    function __destruct () {

    }

    function __construct () {

    }

    public function start($method, $requirements, $authentication) {
        if ($method != $this->request->method) {
            $this->error = 1;
            return false;
        }

        if ($requirements != null) {
            foreach ($requirements as $var) {
                if (!isset($this->request->datas[$var])) {
                    $this->error = 2;
                    return false;
                }
            }
        }
        if ($authentication == true) {
            $auth = false;
            if (isset($this->datas['username'])
                && isset($this->datas['token'])) {
                $auth =  $this->addons['Authentication']->is_auth($this->datas['username'], $this->datas['token']);
            }
            if (!$auth) {
                $this->error = 3;
                return false;
            }
        }
    	return true;
    }

    public function put_result() {
    	if ($this->$layout_type == 'json') {
    		$this->$layout_type['error'] = $this->error;
    		header('Content-Type: application/json');
			echo json_encode($this->$layout_type);
		} else if ($this->$layout_type == 'html') {
            Designer::put_result_html($this->title, $this->$layout_type);
		}
    }
    
    public function get_result() {
    	//var_dump($this->result);
		$this->result['error'] = $this->error;
    	return $this->result;	
    }
    
    public function copy_attrs($other_inst) {
    	$this->request 	= $other_inst->request;
        $this->data 	= $other_inst->data;
        $this->addons 	= $other_inst->addons;
        $this->lang 	= $other_inst->lang;
    }
    
    function parseClass($class_doc) {
	    $class_doc = str_replace('/**', '', $class_doc);
	    $class_doc = str_replace('*/', '', $class_doc);
	    $class_doc = str_replace(' *', '', $class_doc);
	    $split = explode('<br />', nl2br($class_doc));
	    $result = '';
	    foreach($split as $line) {
            $result .= $line;
	    }
	    $result .= "\n";
	    return $result;
	}
	
	function parseDoc($class_doc) {
	    $class_doc = str_replace('/**', '', $class_doc);
	    $class_doc = str_replace('*/', '', $class_doc);
	    $class_doc = str_replace(' *', '', $class_doc);
	    $split = explode('<br />', nl2br($class_doc));
	    $result = array(
	        'method' => '',
	        'description' => '',
	        'params' => array(),
	        'returns' => array()
        );

	    foreach($split as $line) {
	        if (strpos($line, '@method')) {
	            $result['method'] = trim(str_replace('@method ', '', $line));
	        } else if (strpos($line, '@name')) {
	            $result['name'] = str_replace('@name ', '', $line);
	        } else if (strpos($line, '@description')) {
	            $result['description'] = str_replace('@description ', '', $line);
	        } else if (strpos($line, '@param')) {
	            $result['params'][] = str_replace('@param ', '', $line);
	        } else if (strpos($line, '@return')) {
	            $result['returns'][] = str_replace('@return ', '', $line);
	        }
	    }
	    return $result;
	}
}