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
            <table id="table-seccionesempleados" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="35%">Empleado</th>
                        <th width="35%">Secciones</th>
                        <th width="20%">Cliente</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoEmp" class="btn btn-primary btn-lg btn--icon" href="#formulario-seccionempleado" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('empleadossecciones.formulario') }}
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
            $('#table-seccionesempleados').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('empleadossecciones.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'emp_nombre', name: 'emp_nombre'},
                    {data: 'secciones.[].descripcion', name: 'secciones.[].descripcion'},
                    {data: 'cliente.cli_nombre', name: 'cliente.cli_nombre'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [{
                    targets: [0,1,2,3],
                    className:'text-center'
                }]
            });            
        });

        $('#Btn-NuevoEmp').on('click', function(){
            $("#form-seccionesempleados")[0].reset();
            $('#formulario-seccionempleado select').val('').trigger('change');
            $('#formulario-seccionempleado #accion-seccionempleado').val('nuevo');
            $('#formulario-seccionempleado .modal-title').html('Nuevo seccion a empleado');
            $('#formulario-seccionempleado #id').val('0');
        });

        $('#table-seccionesempleados').on('click', '.btn-editar', function(){
            $("#form-seccionesempleados")[0].reset();
            var url = '{{ route("empleadossecciones.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-seccionempleado #accion-seccionempleado').val('editar');
            $('#formulario-seccionempleado .modal-title').html('Editar seccion a empleado');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        if(index == 'id'){
                            index = 'empleado_id';
                            var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                urlsel = urlsel.replace(':tipo', $('#formulario-seccionempleado #'+index).data('url'));
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
                                    $("#formulario-seccionempleado select#"+index).append(option).trigger('change');
                                    $("#formulario-seccionempleado select#"+index).trigger('select2:select');
                                }
                            });
                        }

                        if(index == 'emp_cliente'){
                            index = 'cliente_id';
                            var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                urlsel = urlsel.replace(':tipo', $('#formulario-seccionempleado #'+index).data('url'));
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
                                    $("#formulario-seccionempleado select#"+index).append(option).trigger('change');
                                    $("#formulario-seccionempleado select#"+index).trigger('select2:select');
                                }
                            });
                        }
                    });
                    $("#formulario-seccionempleado").modal("show");
                }
            });
        });

        $('#table-seccionesempleados').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("empleadossecciones.eliminar") }}',
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
                            $('#table-seccionesempleados').DataTable().ajax.reload();
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

        function seccionCreado(){
            $('#table-seccionesempleados').DataTable().ajax.reload();
        }
    </script>
@endsection