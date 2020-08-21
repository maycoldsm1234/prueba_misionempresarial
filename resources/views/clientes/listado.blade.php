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
            <table id="table-clientes" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="10%">Documento</th>
                        <th width="40%">Nombre Completo</th>
                        <th width="20%">Direccion</th>
                        <th>Email</th>
                        <th width="10%">Tipo</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoCli" class="btn btn-primary btn-lg btn--icon" href="#formulario-cliente" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('clientes.formulario') }}
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
            $('#table-clientes').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('clientes.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'cli_documento', name: 'cli_documento'},
                    {data: 'cli_nombre', name: 'cli_nombre'},
                    {data: 'cli_direccion', name: 'cli_direccion'},
                    {data: 'cli_email', name: 'cli_email'},
                    {data: 'tipo.descripcion', name: 'tipo.descripcion'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [{
                    targets: [0,3,4,5],
                    className:'text-center'
                }]
            });            
        });

        $('#Btn-NuevoCli').on('click', function(){
            $("#form-clientes")[0].reset();
            $('#formulario-cliente select').val('').trigger('change');
            $('#formulario-cliente #accion-cliente').val('nuevo');
            $('#formulario-cliente .modal-title').html('Nuevo cliente');
            $('#formulario-cliente #id').val('0');
        });

        $('#table-clientes').on('click', '.btn-editar', function(){
            $("#form-clientes")[0].reset();
            var url = '{{ route("clientes.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-cliente #accion-cliente').val('editar');
            $('#formulario-cliente .modal-title').html('Editar cliente');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        $('#formulario-cliente #'+index).val(value);
                        $('#formulario-cliente #'+index).trigger('blur');
                        if($("#formulario-cliente select#"+index).length){
                            if($("#formulario-cliente select#"+index).attr("data-url")){
                                var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                    urlsel = urlsel.replace(':tipo', $('#formulario-cliente #'+index).data('url'));
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
                                        $("#formulario-cliente select#"+index).append(option).trigger('change');
                                    }
                                });
                            }
                        }
                    });
                    $('#formulario-cliente select').trigger('change');
                    $("#formulario-cliente").modal("show");
                }
            });
        });

        $('#table-clientes').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("clientes.eliminar") }}',
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
                            $('#table-clientes').DataTable().ajax.reload();
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

        function clienteCreado(pac_documento){
            $('#table-clientes').DataTable().ajax.reload();
        }
    </script>
@endsection