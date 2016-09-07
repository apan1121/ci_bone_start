<?php
/* Header */
$this->load->view("layout/header");
$this->load->view("layout/headerBar");
?>
<div class="content-wrapper" style="min-height: 183px;">
<?php
/* Content */
echo $loaderViewContent;
?>
</div>
<?php
/* Footer */
$this->load->view("layout/footerBar");
$this->load->view("layout/footer");
?>

