<div class="modal fade" id="formulario-seccionempleado" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Nuevo seccion a empleado</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion-seccionempleado" value="nuevo" />
                <form id="form-seccionesempleados">
                    <input type="hidden" name="id" id="id" value="0" />
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group select-form-group-float">
                                <label>Cliente</label>
                                <select required id="cliente_id" name="cliente_id" class="se_select2" data-url="cliente">
                                    <option value="" selected>--Seleccione--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" >
                            <div class="form-group select-form-group-float">
                                <label>Empleados</label>
                                <select required id="empleado_id" name="empleado_id" class="se_select2" data-url="empleados">
                                    <option value="" selected>--Seleccione--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group select-form-group-float">
                                <label>Secciones</label>
                                <select required multiple id="seccion_id" name="seccion_id[]" class="se_select2" data-url="secciones">
                                </select>
                            </div>
                        </div>
                    
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="form-guardar" type="button" class="btn btn-primary btn--icon-text">
                    <i class="zmdi zmdi-save"></i> Guardar
                </button>
                <button type="button" class="btn btn-danger btn--icon-text" data-dismiss="modal">
                    <i class="zmdi zmdi-close-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@section('script')
    @parent

    <script>
        $(function(){

            $('.se_select2').select2({
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $('#formulario-seccionempleado'),
                ajax: {
                    url: function(){
                        var url = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                        url = url.replace(':tipo', $(this).data('url'));
                        return url;
                    },
                    type:'post',
                    data: function (params) {
                        return {
                            term: params.term, // search term
                            _token : "{{ csrf_token() }}",
                            cliente_id: $('#cliente_id').val()
                        };
                    },
                    dataType: 'json',
                }
            }).on('select2:select', function(){
                $(this).trigger('blur');                
            });

            $('#empleado_id').on('select2:select',function(){
                $.ajax({
                    url: '{{ route("select2", ["tipo" => "secciones"]) }}', 
                    type:'post',
                    data: { 
                        empleado_id: $(this).val(),
                        "_token" : "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success:function(dataSelect){
                        $.each(dataSelect.results[0], function(index, value){
                            if(index == 'secciones'){
                                $.each(value, function(index2, value2){
                                    var option = new Option(value2.descripcion, value2.id, true, true);
                                    $("select#seccion_id").append(option).trigger('change');
                                });
                            }
                        });
                    }
                });
            });

            $( "#form-seccionesempleados" ).validate({
                highlight: function (input) {
                    $(input).parents('.form-group').addClass('is-invalid');
                },
                unhighlight: function (input) {
                    $(input).parents('.form-group').removeClass('is-invalid');
                },
                errorPlacement: function (error, element) {
                    $(element).parents('.form-group').append('');
                }
            });

            $('#form-guardar').on('click', function(){
                if($( "#form-seccionesempleados" ).valid()){
                    if($('#formulario-seccionempleado #accion-seccion').val() == 'nuevo'){
                        var url_pac ='{{ route("empleadossecciones.agregar") }}';
                    }else{
                        var url_pac ='{{ route("empleadossecciones.editar") }}';
                    }
                    $.ajax({
                        type:'POST',
                        url: url_pac,
                        data:$('#form-seccionesempleados').serialize(),
                        dataType:'json',
                        beforeSend: function() {
                            swal({
                                title: 'Procesando informacion!',
                                html: '<div class="preloader pl-size-xs"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>',
                                showConfirmButton: false
                            });
                        },
                        success:function(response){
                            if(response.success){
                                swal({
                                    title: response.msg,
                                    type: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function(){
                                    $("#form-seccionesempleados")[0].reset();
                                    $("#formulario-seccionempleado").modal("hide");
                                    seccionCreado();
                                });
                            }else{
                                var msg = '';
                                $.each(response.error, function(index, value){
                                    var campo = $('#'+index).parents('.form-group').text();
                                    msg += campo+': '+value+'<br/>';
                                });
                                swal({
                                    title: 'Error de Validacion',
                                    html: msg,
                                    type: 'warning',
                                    buttonsStyling: false,
                                    confirmButtonClass: 'btn btn-primary'
                                });
                            }
                        }
                    });
                }
            });
       });
   </script>
@endsection