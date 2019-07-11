<?php
class ACL {
    
    
    private static $perms;
    private static $role_field;
    public function __construct() {
        $this->role_field = 'role_id';
        
        $this->perms[0]['home']['index']        = true;
        $this->perms[0]['home']['about']        = true;
        $this->perms[1]['user']['dashboard']    = true;
        $this->perms[1]['user']['edit']         = true;
        $this->perms[1]['user']['show']         = true;
        $this->perms[2]['admin']['dashboard']   = true;
        $this->perms[3]['admin']['settings']    = true;
    }
    public function auth(){
        $CI =& get_instance();
        if (!isset($CI->session)){ 
            $CI->load->library('session');
        }
        if (!isset($CI->router)){ 
            $CI->load->library('router');
        }
        $class = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();
        $is_ruled = false;

        foreach ($this->perms as $role){ 
        if (isset($role[$class][$method])){ 
            $is_ruled = true;
        }
    }
    if (!$is_ruled){ 
        return;
    }
    if ($CI->session->userdata($this->role_field)){ 
        if ($this->perms[$CI->session->userdata($this->role_field)][$class][$method]){ 
            return true;
        }
        else{ 
            $CI->error->show(403);
        }
    }
        else{ 
            if ($this->perms[0][$class][$method]){ 
                return true;
            }
            else{ 
                $CI->error->show(403);
            }
        }

        
        
    }
}