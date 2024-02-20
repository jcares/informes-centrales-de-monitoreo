<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layout/head');?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">  
        <!-- sidebar-->
        <?php $this->load->view('layout/sidebar');?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php $this->load->view('layout/nav');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid m-2" style="background-color: #fff; min-height: 500px;">
                    <!-- main content -->
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Esquema de Horarios';?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Organización</label>
                            <select class="form-control" id="select-organizacion">
                                <option value="">Seleccione</option>
                                <?php 
                                    if(!empty($organizaciones)){
                                        foreach($organizaciones as $o){
                                            echo '<option value="'.$o['id'].'">'.$o['nombre'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Dispositivo</label>
                            <select class="form-control" id="select-dispositivo">
                                <option value="">Seleccione</select>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table id="table-schema" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Canal</th>
                                        <th>Lunes</th>
                                        <th>Martes</th>
                                        <th>Miércoles</th>
                                        <th>Jueves</th>
                                        <th>Viernes</th>
                                        <th>Sábado</th>
                                        <th>Domingo</th>
                                        <th width="15%">Hora</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-schema">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="button" onclick="guardarEsquema();" class="btn btn-success">Enviar</button>
                            <button class= "btn btn-xs btn-danger" onclick="actualizar();">Cancelar</button>
                        </div>
                    </div>
                    <br>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php $this->load->view('layout/footer');?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <?php $this->load->view('layout/scripts');?>
    <script>
        $(document).ready(function() {
            
            $('#select-organizacion').change(function(){
                let options_dispositivos = '<option value="">Seleccione</select>';
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CapturasController/obtenerDispositivosOrganizacion',
                    data: {organizacion: $('#select-organizacion').val()},
                    type: 'post',
                    dataType: 'json',
                    success: function(response){
                        if(response.length > 0){
                            for(let i=0; i<response.length; i++){
                                options_dispositivos += '<option value="'+response[i]['id']+'">'+response[i]['nombre']+'</option>';
                            }
                        }
                        $('#select-dispositivo').html(options_dispositivos);
                    }
                });
            });

            $('#select-dispositivo').change(function(){
                let tbody = '';
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CapturasController/obtenerCantidadCanalesDispotivo2',
                    type: 'post',
                    data: {dispositivo: $(this).val()},
                    dataType: 'json',
                    success: function(response){

                        if(response.length > 0){
                            for(let i=0; i< response.length; i++){
                                tbody  += '<tr canal="'+response[i]['canal']+'" id="tr-'+response[i]['canal']+'" class="tr-canales">';
                                
                                tbody  += '<td>'+response[i]['canal']+' '+response[i]['nombre']+'</td>';
                                
                                tbody  += '<td class="text-center" ><input name="input-check-lun_'+response[i]['canal']+'" id="input-check-lun_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-mar_'+response[i]['canal']+'" id="input-check-mar_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-mie_'+response[i]['canal']+'" id="input-check-mie_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-jue_'+response[i]['canal']+'" id="input-check-jue_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-vie_'+response[i]['canal']+'" id="input-check-vie_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-sab_'+response[i]['canal']+'" id="input-check-sab_'+response[i]['canal']+'" type="checkbox"></td>';
                                tbody  += '<td class="text-center" ><input name="input-check-dom_'+response[i]['canal']+'" id="input-check-dom_'+response[i]['canal']+'" type="checkbox"></td>';
                                
                                tbody  += '<td class="td-horas" id="td-horas-'+response[i]['canal']+'">';
                                
                                tbody  += '<div class="row">';

                                tbody  += '<div class="col-md-8">';
                                tbody  += '<input canal="'+response[i]['canal']+'" hora="1" class="horas" ocupado="0" id="div-hora-'+response[i]['canal']+'_1" class="form-contron" type="time" value="06:00"><br>';
                                tbody  += '</div>';

                                tbody  += '<div class="col-md-4">';
                                tbody  += '<button onclick="agregarHora('+response[i]['canal']+');" class="btn btn-primary btn-sm round">+</button>';
                                tbody  += '</div>';

                                tbody  += '</div>';
                                
                                tbody  += '</td>';
                            }
                        }

                        $('#tbody-schema').html(tbody);
                        
                        $.ajax({
                            url: '<?php echo base_url();?>index.php/CapturasController/obtenerSchema',
                            data:{dispositivo: $('#select-dispositivo').val()},
                            type: 'post',
                            dataType:'json',
                            success: function(response2){
                                if(response2.length > 0){
                                    $('.td-horas').html('');
                                    for(let i=0; i<response2.length;i++){
                                        
                                        //$('#td-horas-'+response2[i]['canal']).html('');

                                        if(response2[i]['Lun'] == '1'){
                                            $('#input-check-lun_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Mar'] == '1'){
                                            $('#input-check-mar_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Mie'] == '1'){
                                            $('#input-check-mie_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Jue'] == '1'){
                                            $('#input-check-jue_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Vie'] == '1'){
                                            $('#input-check-vie_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Sab'] == '1'){
                                            $('#input-check-sab_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        if(response2[i]['Dom'] == '1'){
                                            $('#input-check-dom_'+response2[i]['canal']).prop('checked', true);
                                        }
                                        
                                        let nueva_hora_id = agregarHora2(response2[i]['canal']);
                                        $('#'+nueva_hora_id).val(response2[i]['hora']);
                                    }
                                }
                            }
                        });
                    }
                });
            });
        });

        function removerHora(canal, hora){
            $('#row_'+canal+'_'+hora).remove();
        }

        function agregarHora(canal){
            let elementos_horas = $('.horas');
            let hora = 1;
            for(let i=0; i< elementos_horas.length; i++){
                if($(elementos_horas[i]).attr('canal') == canal){
                    hora = $(elementos_horas[i]).attr('hora');
                }
            }

            hora++;
            let row = '';
            row  += '<div class="row" id="row_'+canal+'_'+hora+'">';
            row  += '<div class="col-md-8">';
            row  += '<input canal="'+canal+'" hora="'+hora+'" class="horas" id="hora-'+canal+'_'+hora+'" class="form-contron" type="time" value="06:00">';
            row  += '</div>';
            row  += '<div class="col-md-4"><button class="btn btn-danger btn-sm round" onclick="removerHora('+canal+','+hora+');">-</button></div>';
            row  += '</div>';

            $('#td-horas-'+canal).append(row);
        }

        function agregarHora2(canal){
            let elementos_horas = $('.horas');
            let hora = 0;
            for(let i=0; i< elementos_horas.length; i++){
                if($(elementos_horas[i]).attr('canal') == canal){
                    hora++;
                }
            }

            hora++;
            let row = '';
            row  += '<div class="row" id="row_'+canal+'_'+hora+'">';
            row  += '<div class="col-md-8">';
            row  += '<input canal="'+canal+'" hora="'+hora+'" class="horas" id="hora-'+canal+'_'+hora+'" class="form-contron" type="time" value="06:00">';
            row  += '</div>';
            if(hora > 1)
                row  += '<div class="col-md-4"><button class="btn btn-danger btn-sm round" onclick="removerHora('+canal+','+hora+');">-</button></div>';
            else
                row  += '<div class="col-md-4"><button onclick="agregarHora('+canal+');" class="btn btn-primary btn-sm round">+</button></div>';
            row  += '</div>';

            $('#td-horas-'+canal).append(row);
            return 'hora-'+canal+'_'+hora;
        }

        function actualizar(){
            location.reload();
        }

        function guardarEsquema(){
            if(confirm('Confirme esta operación')){
                let arreglo = [];
                let horas = [];
                let filas = $('.tr-canales');
                if(filas.length > 0){
                    for(let i=0; i<filas.length; i++){
                        let canal = $(filas[i]).attr('canal');
                        let lun = $('#input-check-lun_'+canal).is(':checked');
                        let mar = $('#input-check-mar_'+canal).is(':checked');
                        let mie = $('#input-check-mie_'+canal).is(':checked');
                        let jue = $('#input-check-jue_'+canal).is(':checked');
                        let vie = $('#input-check-vie_'+canal).is(':checked');
                        let sab = $('#input-check-sab_'+canal).is(':checked');
                        let dom = $('#input-check-dom_'+canal).is(':checked');
                        
                        let horas = $('input[canal="'+canal+'"]');
                        let temp_horas = [];
                        if(horas.length > 0){
                            for(let j=0; j<horas.length; j++){
                                temp_horas.push($(horas[j]).val());
                            }    
                        }
                        arreglo.push({'canal': canal, 'lun': lun, 'mar': mar, 'mie': mie, 'jue': jue, 'vie': vie, 'sab': sab, 'dom': dom, 'horas': temp_horas});
                    }

                    $.ajax({
                        url: '<?php echo base_url();?>index.php/CapturasController/guardarSchema',
                        type: 'post',
                        data: {'schema': arreglo, 'organizacion': $('#select-organizacion').val(), 'dispositivo': $('#select-dispositivo').val()},
                        dataType: 'text',
                        success: function(response){
                            if(response == 1){
                                alert('Se ha generado el equema de horarios');
                                location.reload();
                            }     
                        }
                    });
                }
                else
                    alert('Debe existir al menos un Canal con su esquema de horarios');
            }
        }
    </script>
</body>
</html>