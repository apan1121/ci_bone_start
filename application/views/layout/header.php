<?php
// 預設把左列全部縮小 by profileData setting
$this->load->bodyParameter(
    array(
        "class"=> "",
    )
);


$this->load->cssLoader(array(

));


$this->load->metaLoader(array(
    array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1, user-scalable=no, minimal-ui"), // Meta For iPad, iPhone device
    array("name"=>"apple-mobile-web-app-capable", "content"=>"yes"), // Meta For iPad, iPhone device
    array("name"=>"apple-touch-fullscreen", "content"=>"yes"), // Meta For iPad, iPhone device
    array("name"=>"apple-mobile-web-app-status-bar-style", "content"=>"black"), // Meta For iPad, iPhone device
    array("name"=>"mobile-web-app-capable", "content"=>"yes"), // Meta For Android
),"before");

$this->load->jsLoader(array(
    "js/lib/cdnServiceList.js",
),"before");

?>
<!doctype html><!--html5的開頭宣告-->
<html lang="zh_tw"><!--除錯與文本元素為繁體中文-->
<head>
<?php echo $this->load->setMeta();?>

<title><?php echo !empty($this->load->setTitle())?$this->load->setTitle():""?></title>

<?php echo $this->load->setCss();?>
<?php echo $this->load->setHeadLink();?>
<?php echo $this->load->setJs("before");?>

<script>
    var jsVars = <?php echo $jsVars?>;
</script>

</head>
<body <?php echo $this->load->setBodyParameter()?>>
