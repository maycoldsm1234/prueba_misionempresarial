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
            <table id="table-empleados" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="10%">Documento</th>
                        <th width="30%">Nombre Completo</th>
                        <th width="20%">Direccion</th>
                        <th>Email</th>
                        <th width="20%">Cliente</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoEmp" class="btn btn-primary btn-lg btn--icon" href="#formulario-empleado" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('empleados.formulario') }}
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
            $('#table-empleados').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('empleados.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'emp_documento', name: 'emp_documento'},
                    {data: 'emp_nombre', name: 'emp_nombre'},
                    {data: 'emp_direccion', name: 'emp_direccion'},
                    {data: 'emp_email', name: 'emp_email'},
                    {data: 'cliente.cli_nombre', name: 'cliente.cli_nombre'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [{
                    targets: [1,4,5],
                    className:'text-center'
                }]
            });            
        });

        $('#Btn-NuevoEmp').on('click', function(){
            $("#form-empleados")[0].reset();
            $('#formulario-empleado select').val('').trigger('change');
            $('#formulario-empleado #accion-empleado').val('nuevo');
            $('#formulario-empleado .modal-title').html('Nuevo empleado');
            $('#formulario-empleado #id').val('0');
        });

        $('#table-empleados').on('click', '.btn-editar', function(){
            $("#form-empleados")[0].reset();
            var url = '{{ route("empleados.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-empleado #accion-empleado').val('editar');
            $('#formulario-empleado .modal-title').html('Editar empleado');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        $('#formulario-empleado #'+index).val(value);
                        $('#formulario-empleado #'+index).trigger('blur');
                        if($("#formulario-empleado select#"+index).length){
                            if($("#formulario-empleado select#"+index).attr("data-url")){
                                var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                    urlsel = urlsel.replace(':tipo', $('#formulario-empleado #'+index).data('url'));
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
                                        $("#formulario-empleado select#"+index).append(option).trigger('change');
                                    }
                                });
                            }
                        }
                    });
                    $('#formulario-empleado select').trigger('change');
                    $("#formulario-empleado").modal("show");
                }
            });
        });

        $('#table-empleados').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("empleados.eliminar") }}',
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
                            $('#table-empleados').DataTable().ajax.reload();
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

        function empleadoCreado(emp_documento){
            $('#table-empleados').DataTable().ajax.reload();
        }
    </script>
@endsection