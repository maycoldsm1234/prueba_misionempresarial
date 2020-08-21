@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('theme/vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/vendors/flatpickr/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/vendors/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
        <div class="table-responsive">
            <table id="table-productos" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="20%">Producto</th>
                        <th width="20%">Descripcion</th>
                        <th>Valor</th>
                        <th width="20%">Cliente</th>
                        <th width="20%">Proveedor</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoProd" class="btn btn-primary btn-lg btn--icon" href="#formulario-producto" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('productos.formulario') }}
</div>
@endsection

@section('script')
    <script src="{{ asset('theme/vendors/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('theme/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('theme/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('theme/vendors/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('theme/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function(){
            $('#table-productos').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('productos.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'prod_nombre', name: 'prod_nombre'},
                    {data: 'prod_descripcion', name: 'prod_descripcion'},
                    {data: 'prod_valor', name: 'prod_valor'},
                    {data: 'cliente.cli_nombre', name: 'cliente.cli_nombre'},
                    {data: 'proveedor.prov_nombre', name: 'proveedor.prov_nombre'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [{
                    targets: [1,4,5],
                    className:'text-center'
                }]
            });            
        });

        $('#Btn-NuevoProd').on('click', function(){
            $("#form-productos")[0].reset();
            $('#formulario-producto select').val('').trigger('change');
            $('#formulario-producto #accion-producto').val('nuevo');
            $('#formulario-producto .modal-title').html('Nuevo producto');
            $('#formulario-producto #id').val('0');
        });

        $('#table-productos').on('click', '.btn-editar', function(){
            $("#form-productos")[0].reset();
            var url = '{{ route("productos.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-producto #accion-producto').val('editar');
            $('#formulario-producto .modal-title').html('Editar producto');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        $('#formulario-producto #'+index).val(value);
                        $('#formulario-producto #'+index).trigger('blur');
                        if($("#formulario-producto select#"+index).length){
                            if($("#formulario-producto select#"+index).attr("data-url")){
                                var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                    urlsel = urlsel.replace(':tipo', $('#formulario-producto #'+index).data('url'));
                                $.ajax({
                                    url: urlsel, 
                                    type:'post',
                                    data: { 
                                        buscarid: value,
                                        "_token" : "{{ csrf_token() }}"
                                    },
                                    dataType: "json",
                                    success:function(dataSel){
                                        var Selec = dataSel.results;
                                        var option = new Option(Selec.text, Selec.id, true, true);
                                        $("#formulario-producto select#"+index).append(option).trigger('change');
                                    }
                                });
                            }
                        }
                    });
                    $('#formulario-producto select').trigger('change');
                    $("#formulario-producto").modal("show");
                }
            });
        });

        $('#table-productos').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("productos.eliminar") }}',
                data: { 
                    id: $id,
                    "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    if(response.success){
                        swal({
                            title: response.msg,
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function(){
                            $('#table-productos').DataTable().ajax.reload();
                        });
                    }else{
                        swal({
                            title: 'Error',
                            html: msg,
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary'
                        });
                    }
                }
            });
        });

        function productoCreado(prod_documento){
            $('#table-productos').DataTable().ajax.reload();
        }
    </script>
@endsection