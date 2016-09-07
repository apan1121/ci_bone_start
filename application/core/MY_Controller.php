<?php
class MY_Controller extends CI_Controller
{
    private $jsVars = array();
    private $resourcePath = "";

    public $gooseProfile;
    public $guestSession;
    public $gooseSession;

    public function __construct(){
        parent::__construct();

        $version = "";
        switch( ENVIRONMENT ){
            case "development":
                $this->resourcePath = base_url()."protected/";
                $version = time();
                break;
            default:
            case "production":
                $version = WEB_VER;
                $this->resourcePath = base_url()."public/".$version."/";
                break;
        }

        $this->setJsVars(array(
            "baseUrl"       => base_url(),
            "baseFullUrl"   => "//".$_SERVER["HTTP_HOST"].base_url(),
            "baseResUrl"    => $this->resourcePath,
            "userIP"        => $this->get_client_ip(),
            "version"       => $version,
        ));
    }


    public function getResourcePath(){
        return $this->resourcePath;
    }

    public function setJsVars($var = array()){
        if (is_array($var)) {
            $this->jsVars = array_merge($this->jsVars,$var);
        }
        $this->load->vars(array("jsVars"=>json_encode($this->jsVars)));
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
