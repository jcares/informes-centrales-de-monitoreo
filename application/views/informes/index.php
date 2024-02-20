<?php $this->load->view('layout/main_top');?>
<style>
    #report-error{
        font-size: 14px !important;
        width: 100% !important;
    }
</style>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<div class="table-responsive">
    <?php echo $output; ?>
</div>
<?php foreach($js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php $this->load->view('layout/main_bot_gc');?>  
<script>
     if($('#field-ruta').length > 0){
        $('#field-ruta').html('<iframe src="'+ $('#field-ruta').html()+'" style="width: 800px; height: 600px;"></iframe>');
    }
</script>