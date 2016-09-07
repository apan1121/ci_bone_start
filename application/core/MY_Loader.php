<?php
class MY_Loader extends CI_Loader {
    public $resPath = "";
    public $version = "";
    public function __construct(){
        parent::__construct();
        $this->helper('url');

        switch( ENVIRONMENT ){
            case "development":
                $this->version = time();
                $this->resPath = base_url()."protected/";
                break;
            default:
            case "production":
                $this->version = defined("WEB_VER")?WEB_VER:"";
                $this->resPath = base_url()."public/".$this->version."/";
                break;
        }
    }

    private $title = WEB_TITLE;
    public function titleLoader($string){
        $this->title = $string;
    }
    public function setTitle(){
        return !empty($this->title)?$this->title:"";
    }

    /* set body */
    private $bodyStorage = array(
            "class"=> "",
        );

    public function bodyParameter( $param =array()){
        foreach ($param AS $key=>$value) {
            switch($key){
                case "data":
                    if (!isset($this->bodyStorage["data"])) {
                        $this->bodyStorage["data"] = array();
                    }
                    $this->bodyStorage["data"] =array_merge($this->bodyStorage["data"],$value);
                    break;
                case "class":
                    if (isset($this->bodyStorage["class"])) {
                        $this->bodyStorage["class"] .= " ".$value;
                    } else {
                        $this->bodyStorage["class"] = $value;
                    }
                    break;
                default:
                    $this->bodyStorage[$key] = $value;
                    break;
            }
        }
    }

    public function setBodyParameter(){
        $html = array();
        foreach($this->bodyStorage AS $key=>$val) {
            switch ($key) {
                case "data":
                    if (is_array($val)) {
                        foreach($val AS $dataKey=>$dataValue) {
                            $html[] = "data-".$dataKey."='".htmlspecialchars($dataValue)."'";
                        }
                    }
                    break;
                default:
                    $html[] = $key."='".htmlspecialchars($val)."'";
                    break;
            }

        }
        return implode(" ",$html);
    }

    /* meta Loader*/
    private $metaStorge = array("before"=>array(),"after"=>array());
    public function metaLoader($inputMeta, $position="after"){
        $position = ($position==="before")?$position:"after";

        $this->metaStorge[$position] = array_merge($this->metaStorge[$position],$inputMeta);
    }

    public function setMeta(){
        $positionArray = ["before","after"];
        $html = array();
        foreach ($positionArray AS $position) {
            if (!empty($this->metaStorge[$position])){
                foreach ($this->metaStorge[$position] AS $metas) {
                    $tmpHTML = array();
                    foreach ($metas AS $key=>$val) {
                        $tmpHTML[] = $key."='".htmlspecialchars($val)."'";
                    }
                    $html[] = "<meta ".implode(" ",$tmpHTML)." />";
                }
            }
        }
        return implode("\n", $html);
    }

    /* link Loader */
    private $linkStorge = array();
    public function headLinkLoader($inputLink){
        $this->linkStorge = array_merge($this->linkStorge,$inputLink);
    }

    public function setHeadLink(){
        $html = array();

        if (!empty($this->linkStorge)){
            foreach ($this->linkStorge AS $link) {
                $tmpHTML = array();
                foreach ($link AS $key=>$val) {
                    $tmpHTML[] = $key."='".htmlspecialchars($val)."'";
                }
                $html[] = "<link ".implode(" ",$tmpHTML)." />";
            }
        }

        return implode("\n", $html);
    }

    /* image Loader */
    public function imgLoader( $src ){
        if (!empty($src)){
            if (preg_match_all("/([http|https]\:\/\/)/i",$src,$match)){
                return $src;
            } else {
                return $this->resPath.$src;
            }
        } else {
            return "";
        }

    }


    /* Javascript Loader */
    private $jsStorage = array("before"=>array(),"after"=>array());
    public function jsLoader($inputFiles,$position="after"){
        $position = ($position==="before")?$position:"after";

        $files = array();
        if (is_string($inputFiles)) {
            $files[] = $inputFiles;
        } else if(is_array($inputFiles)){
            $files = $inputFiles;
        }

        if ($position === "before") {
            $this->jsStorage["before"] = array_merge($this->jsStorage["before"],$files);
        } else {
            $this->jsStorage["after"] = array_merge($this->jsStorage["after"],$files);
        }
    }
    public function setJs($position="after"){
        $position = ($position==="before")?$position:"after";
        $html = array();

        foreach ($this->jsStorage[$position] AS $file) {
            if (is_string($file)) {
                $html[] = '<script type="text/JavaScript" src="'.$this->resPath.$file.'?'.$this->version.'"></script>';
            } else if(isset($file['url'])){
                $htmlOptions = array();
                if (isset($file['htmlOptions'])){
                    foreach ($file['htmlOptions'] AS $key=>$val) {
                        $htmlOptions[] = $key."='".$val."'";
                    }
                }
                $htmlOptions = implode(" ",$htmlOptions);
                $html[] = '<script type="text/JavaScript" src="'.$this->resPath.$file['url'].'?'.$this->version.'" '.$htmlOptions.'></script>';
            }
        }
        return implode("\n",$html);
    }


    /* CSS Loader*/
    private $cssStorage = array("priority"=>array(),"basic"=>array());

    public function cssLoader( $inputFiles, $priority = false){
        $files = array();

        if (is_string($inputFiles)) {
            $files[] = $inputFiles;
        } else if(is_array($inputFiles)){
            $files = $inputFiles;
        }

        if ($priority === true) {
            $this->cssStorage["priority"] = array_merge($this->cssStorage["priority"],$files);
        } else {
            $this->cssStorage["basic"] = array_merge($this->cssStorage["basic"],$files);
        }
    }

    public function setCss(){
        $html = array();
        foreach ($this->cssStorage["priority"] AS $file) {
            $res[] = $file;
            $html[] = $this->setCssHtml($file);
        }
        foreach ($this->cssStorage["basic"] AS $file) {
            $res[] = $file;
            $html[] = $this->setCssHtml($file);
        }
        if (ENVIRONMENT == 'production' && COMBINE_CSS && $cssFile = $this->_combine($res)) {
            $tmp = $this->resPath;
            $this->resPath = '';

            // 很重要
            $html = [];
            foreach ($cssFile as $val) {
                $html[]= $this->setCssHtml($val);
            }

            $this->resPath = $tmp;
        }
        return implode("\n",$html);
    }

    /**
     * Combine all using css files to a single css for enhancement of loading.
     *
     * @param  Array $res file lists
     * @return String $cssFile
     */
    private function _combine($res)
    {
        $outputCssFolder = 'resources';
        $key = md5(join("|",$res));

        $refUrl = $outputCssFolder.'/'.$key.'_'.WEB_VER.'.css';
        $genFile = FCPATH.$refUrl;
        $content = '';
        $returnFiles = array();
        $combineFiles = array();

        foreach($res as $file) {
            if (preg_match('/^[http|https]/',$file)){
                $returnFiles[] =$file;
            } else {
                $combineFiles[]= $file;
            }
        }

        if (!file_exists($genFile)) {
            $glmBaseUrl = '../';
            foreach ($combineFiles as $file) {
                // $realPath = FCPATH.'public/'.$file;
                $file = str_replace(".less",".css",$file); /* 將 less 強制轉成 css */

                $realPath = FCPATH.$this->resPath."/".$file;
                $fileContents = file_get_contents($realPath);
                unset($arr);
                $arr =  explode("/", $file);
                array_pop($arr);
                $path = join("/", $arr);

                $fileContents = preg_replace('/url\([\"|\']*((\.\.\/){0,})([^\(][^http].*?)[\"|\']*\)/','url(../public/'.$this->version."/".$path.'/\1\3)',
                    $fileContents);
                $content .= "\n\n$fileContents";
            }
            file_put_contents($genFile, $content);
        }
        $returnFiles[] = base_url().$refUrl;
        return $returnFiles;
    }

    private $setLessJS = false;
    public function setCssHtml($filename){
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $html = "";

        switch ($ext){
            case "css":
                if (preg_match('/^[http|https]/', $filename)) {
                    $html = '<link rel="stylesheet" type="text/css" href="'.$filename.'"/>';
                } else {
                    $html = '<link rel="stylesheet" type="text/css" href="'.$this->resPath.$filename.'?'.$this->version.'"/>';
                }
                break;
            case "less":
                switch( ENVIRONMENT ){
                    case "development":
                        if (!$this->setLessJS) {
                            $this->setLessJS = true;
                            $this->jsLoader("js/lib/vendor/less.min.js","before");
                        }
                        $html = '<link rel="stylesheet/less" type="text/css" href="'.$this->resPath.$filename.'?'.$this->version.'"/>';
                        break;
                    default:
                    case "production":
                        $fileArray = pathinfo($filename);
                        $filename = str_replace($fileArray["basename"],$fileArray["filename"].".css",$filename);
                        $html = '<link rel="stylesheet" type="text/css" href="'.$this->resPath.$filename.'?'.$this->version.'"/>';
                        break;
                }

                break;
        }
        return $html;
    }


    public function setView($page, $data = array(), $layout = "layout/main"){
        if (empty($layout)) {
            $layout = "layout/main";
        }

        $loaderViewContent = $this->view($page, $data, true);

        if (is_array($data)) {
            $data = array_merge($data, array("loaderViewContent"=>$loaderViewContent));
        } else {
            $data = array("loaderViewContent"=>$loaderViewContent);
        }

        $this->view($layout, $data);
    }

}
