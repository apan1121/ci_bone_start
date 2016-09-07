<?php
$this->load->bodyParameter(
    array(
        "id"=> "index",
        "data"=>array("page-id"=>"index")
        )
    );

$this->load->jsLoader(array(
        array("url"=>"js/lib/vendor/require/require.js","htmlOptions"=>array('data-main'=>$this->load->resPath.'js/app/demo/demo-page'))
    ),"after");


?>
this is a demo page



