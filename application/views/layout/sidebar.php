<?php
$opciones = [];
if($this->session->userdata('opciones'))
    //print_r($this->session->userdata('opciones'));
    $opciones = $this->session->userdata('opciones');
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url();?>index.php/ConfiguracionesController/index">
    <div class="sidebar-brand-icon">
    <!-- <div class="sidebar-brand-icon rotate-n-15"> -->
        <!--<i class="fas fa-cogs"></i>-->
        <img src="<?php echo base_url();?>assets/img/logo.png" class="img-responsive" width="75px;"/>
    </div>
    <div class="sidebar-brand-text mx-3">Panel Control<sup></sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="#">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Administración</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Imágenes</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <?php 
                foreach($opciones as $o){
                    if($o['padre'] == 21)
                        echo '<a class="collapse-item" href="'.base_url().'index.php/'.$o['controlador'].'/'.$o['accion'].'">'.$o['nombre'].'</a>';
                }
            ?>
        </div>
    </div>
</li>
<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Informes</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <?php 
                foreach($opciones as $o){
                    if($o['padre'] == 13)
                        echo '<a class="collapse-item" href="'.base_url().'index.php/'.$o['controlador'].'/'.$o['accion'].'">'.$o['nombre'].'</a>';
                }
            ?>
            <!--
            <a class="collapse-item" href="<?php //echo base_url();?>index.php/CapturasController/schema">Esquema de Horarios</a>
            <a class="collapse-item" href="<?php //echo base_url();?>index.php/CapturasController/index">Capturas</a>
            <a class="collapse-item" href="<?php //echo base_url();?>index.php/CapturasController/consolidate">Consolidar</a>
            -->
        </div>
    </div>
</li>
<!-- Divider 
<hr class="sidebar-divider">
-->
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Clientes</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <?php 
                foreach($opciones as $o){
                    if($o['padre'] == 17)
                        echo '<a class="collapse-item" href="'.base_url().'index.php/'.$o['controlador'].'/'.$o['accion'].'">'.$o['nombre'].'</a>';
                }
            ?>
            <!--<a class="collapse-item"  href="<?php //echo base_url();?>index.php/CapturasController/report">Registros</a>-->
            <!--<div class="collapse-divider"></div>
            <h6 class="collapse-header">Información Log</h6>-->
            <!--<a class="collapse-item" href= "<?php //echo base_url();?>index.php/LogsController/index">Logs</a>-->
        </div>
    </div>
</li>
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePageMantenedor"
        aria-expanded="true" aria-controls="collapsePageMantenedor">
        <i class="fas fa-fw fa-folder"></i>
        <span>Mantenedores</span>
    </a>
    <div id="collapsePageMantenedor" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <?php 
                foreach($opciones as $o){
                    if($o['padre'] == 3)
                        echo '<a class="collapse-item" href="'.base_url().'index.php/'.$o['controlador'].'/'.$o['accion'].'">'.$o['nombre'].'</a>';
                }
            ?>
            <!--<a class="collapse-item"  href="<?php //echo base_url();?>index.php/CapturasController/report">Registros</a>-->
            <!--<div class="collapse-divider"></div>
            <h6 class="collapse-header">Información Log</h6>-->
            <!--<a class="collapse-item" href= "<?php //echo base_url();?>index.php/LogsController/index">Logs</a>-->
        </div>
    </div>
</li>
</ul>
<!-- End of Sidebar -->