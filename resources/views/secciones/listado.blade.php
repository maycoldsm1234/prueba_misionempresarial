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
            <table id="table-secciones" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="15%">Id</th>
                        <th width="45%">Seccion</th>
                        <th width="30%">Cliente</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="fixed-action-btn">
        <button id="Btn-NuevoEmp" class="btn btn-primary btn-lg btn--icon" href="#formulario-seccion" data-toggle="modal" data-backdrop="static" data-keyboard="false" style="display: block;">
            <i class="zmdi zmdi-account-add"></i>
        </button>
    </div>
    {{ view('secciones.formulario') }}
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
            $('#table-secciones').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('secciones.listado') }}",
                    "data": { "_token" : "{{ csrf_token() }}" },
                    "type": "POST"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'descripcion', name: 'descripcion'},
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
            $("#form-secciones")[0].reset();
            $('#formulario-seccion select').val('').trigger('change');
            $('#formulario-seccion #accion-seccion').val('nuevo');
            $('#formulario-seccion .modal-title').html('Nuevo seccion');
            $('#formulario-seccion #id').val('0');
        });

        $('#table-secciones').on('click', '.btn-editar', function(){
            $("#form-secciones")[0].reset();
            var url = '{{ route("secciones.buscar", ["id" => ":id"]) }}';
            url = url.replace(':id', $(this).data('id'));
            $('#formulario-seccion #accion-seccion').val('editar');
            $('#formulario-seccion .modal-title').html('Editar seccion');
            $.ajax({
                type:'POST',
                url: url,
                data: { "_token" : "{{ csrf_token() }}" },
                dataType:'json',
                success:function(response){
                    $.each(response, function(index, value){
                        $('#formulario-seccion #'+index).val(value);
                        $('#formulario-seccion #'+index).trigger('blur');
                        if($("#formulario-seccion select#"+index).length){
                            if($("#formulario-seccion select#"+index).attr("data-url")){
                                var urlsel = '{{ route("select2", ["tipo" => ":tipo"]) }}';
                                    urlsel = urlsel.replace(':tipo', $('#formulario-seccion #'+index).data('url'));
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
                                        $("#formulario-seccion select#"+index).append(option).trigger('change');
                                    }
                                });
                            }
                        }
                    });
                    $('#formulario-seccion select').trigger('change');
                    $("#formulario-seccion").modal("show");
                }
            });
        });

        $('#table-secciones').on('click', '.btn-eliminar', function(){
            var $id = $(this).data('id');
            $.ajax({
                type:'POST',
                url: '{{ route("secciones.eliminar") }}',
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
                            $('#table-secciones').DataTable().ajax.reload();
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

        function seccionCreado(secc_id){
            $('#table-secciones').DataTable().ajax.reload();
        }
    </script>
@endsection