<div class="modal fade" id="formulario-cliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Nuevo cliente</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion-cliente" value="nuevo" />
                <form id="form-clientes">
                    <input type="hidden" name="id" id="id" value="0" />
                    @csrf
                    <div class="row">
                    
                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="cli_documento" name="cli_documento">
                                <label>Documento</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div> 

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="cli_nombre" name="cli_nombre">
                                <label>Nombre completo</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    
                    </div>

                    <div class="row">

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="cli_direccion" name="cli_direccion">
                                <label>Direccion</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>   

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="cli_telefono" name="cli_telefono">
                                <label>Telefono</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group--float">
                                <input required type="email" class="form-control" id="cli_email" name="cli_email">
                                <label>Email</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group select-form-group-float">
                                <label>Tipo</label>
                                <select required id="cli_tipo" name="cli_tipo" class="cli_select2" data-url="tipos">
                                    <option value="" selected>--Seleccione--</option>
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

            $('.cli_select2').select2({
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $('#formulario-cliente'),
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
                            _token : "{{ csrf_token() }}"
                        };
                    },
                    dataType: 'json',
                }
            }).on('select2:select', function(){
                $(this).trigger('blur');
            });

            $( "#form-clientes" ).validate({
                rules:{
                    email:{ email:true }
                },
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
                if($( "#form-clientes" ).valid()){
                    if($('#formulario-cliente #accion-cliente').val() == 'nuevo'){
                        var url_pac ='{{ route("clientes.agregar") }}';
                    }else{
                        var url_pac ='{{ route("clientes.editar") }}';
                    }
                    $.ajax({
                        type:'POST',
                        url: url_pac,
                        data:$('#form-clientes').serialize(),
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
                                    $("#form-clientes")[0].reset();
                                    $('#formulario-cliente select').val('').trigger('change');
                                    $("#formulario-cliente").modal("hide");
                                    clienteCreado(response.cli_documento);
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