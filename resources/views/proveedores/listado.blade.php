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
            <table id="table-proveedores" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="10%">Nit</th>
                        <th width="40%">Nombre proveedor</th>
                        <th width="20%">Direccion</th>
                        <th>Email</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoEmp" class="btn btn-primary btn-lg btn--icon" href="#formulario-proveedor" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('proveedores.formulario') }}
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
            $('#table-proveedores').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('proveedores.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'prov_nit', name: 'prov_nit'},
                    {data: 'prov_nombre', name: 'prov_nombre'},
                    {data: 'prov_direccion', name: 'prov_direccion'},
                    {data: 'prov_email', name: 'prov_email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [{
                    targets: [0,3,4],
                    className:'text-center'
                }]
            });            
        });

        $('#Btn-NuevoEmp').on('click', function(){
            $("#form-proveedores")[0].reset();
            $('#formulario-proveedor select').val('').trigger('change');
            $('#formulario-proveedor #accion-proveedor').val('nuevo');
            $('#formulario-proveedor .modal-title').html('Nuevo proveedor');
            $('#formulario-proveedor #id').val('0');
        });

        $('#table-proveedores').on('click', '.btn-editar', function(){
            $("#form-proveedores")[0].reset();
            var url = '{{ route("proveedores.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-proveedor #accion-proveedor').val('editar');
            $('#formulario-proveedor .modal-title').html('Editar proveedor');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        $('#formulario-proveedor #'+index).val(value);
                        $('#formulario-proveedor #'+index).trigger('blur');
                    });
                    $('#formulario-proveedor select').trigger('change');
                    $("#formulario-proveedor").modal("show");
                }
            });
        });

        $('#table-proveedores').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("proveedores.eliminar") }}',
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
                            $('#table-proveedores').DataTable().ajax.reload();
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

        function proveedorCreado(proveedores){
            $('#table-proveedores').DataTable().ajax.reload();
        }
    </script>
@endsection