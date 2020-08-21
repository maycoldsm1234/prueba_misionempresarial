<div class="modal fade" id="formulario-proveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Nuevo proveedor</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion-proveedor" value="nuevo" />
                <form id="form-proveedores">
                    <input type="hidden" name="id" id="id" value="0" />
                    @csrf
                    <div class="row">
                    
                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prov_nit" name="prov_nit">
                                <label>NIT</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div> 

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prov_nombre" name="prov_nombre">
                                <label>Nombre completo</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    
                    </div>

                    <div class="row">

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prov_direccion" name="prov_direccion">
                                <label>Direccion</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>   

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prov_telefono" name="prov_telefono">
                                <label>Telefono</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group--float">
                                <input required type="email" class="form-control" id="prov_email" name="prov_email">
                                <label>Email</label>
                                <i class="form-group__bar"></i>
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

            $( "#form-proveedores" ).validate({
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
                if($( "#form-proveedores" ).valid()){
                    if($('#formulario-proveedor #accion-proveedor').val() == 'nuevo'){
                        var url_pac ='{{ route("proveedores.agregar") }}';
                    }else{
                        var url_pac ='{{ route("proveedores.editar") }}';
                    }
                    $.ajax({
                        type:'POST',
                        url: url_pac,
                        data:$('#form-proveedores').serialize(),
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
                                    $("#form-proveedores")[0].reset();
                                    $("#formulario-proveedor").modal("hide");
                                    proveedorCreado(response.prov_documento);
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