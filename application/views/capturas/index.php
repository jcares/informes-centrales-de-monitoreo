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
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-info" href="<?php echo base_url();?>index.php/CapturasController/add">Imágen x clasificar individual</a>
    </div>
</div>
<br>
<div class="table-responsive">
    <?php echo $output; ?>
</div>
<?php foreach($js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php $this->load->view('layout/main_bot_gc');?>  
<script>
     if($('#field-ruta_imagen').length > 0){
        let imagen = '<?php echo base_url();?>assets/imagenes_capturadas/'+$('#field-ruta_imagen').html();
        $('#field-ruta_imagen').html('<img class="img-responsive" width="400" height="400" src="'+imagen+'" />');
    }

    if($('#ruta_imagen_display_as_box').length > 0){
        $('#ruta_imagen_display_as_box').html('Imagen');
    }
</script>