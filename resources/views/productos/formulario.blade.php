<div class="modal fade" id="formulario-producto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Nuevo producto</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion-producto" value="nuevo" />
                <form id="form-productos">
                    <input type="hidden" name="id" id="id" value="0" />
                    @csrf
                    <div class="row">
                    
                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prod_nombre" name="prod_nombre">
                                <label>Nombre</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div> 

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prod_descripcion" name="prod_descripcion">
                                <label>Descripcion</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    
                    </div>

                    <div class="row">

                        <div class="col-md-6" >
                            <div class="form-group form-group--float ">
                                <input required type="text" class="form-control" id="prod_valor" name="prod_valor">
                                <label>Valor</label>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group select-form-group-float">
                                <label>Cliente</label>
                                <select required id="prod_cliente" name="prod_cliente" class="prod_select2" data-url="cliente">
                                    <option value="" selected>--Seleccione--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group select-form-group-float">
                                <label>Proveedor</label>
                                <select required id="prod_proveedor" name="prod_proveedor" class="prod_select2" data-url="proveedor">
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

            $('.prod_select2').select2({
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $('#formulario-producto'),
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

            $( "#form-productos" ).validate({
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
                if($( "#form-productos" ).valid()){
                    if($('#formulario-producto #accion-producto').val() == 'nuevo'){
                        var url_pac ='{{ route("productos.agregar") }}';
                    }else{
                        var url_pac ='{{ route("productos.editar") }}';
                    }
                    $.ajax({
                        type:'POST',
                        url: url_pac,
                        data:$('#form-productos').serialize(),
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
                                    $("#form-productos")[0].reset();
                                    $('#formulario-producto select').val('').trigger('change');
                                    $("#formulario-producto").modal("hide");
                                    productoCreado(response.prod_documento);
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