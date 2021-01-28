
@if($voyage && $voyage->id >0)

    <div class="voyage mt-5 mb-5">
        <h4>Info Voyage</h4>
        <div class="row">
            <div class="col-4">
                <strong>Reference: </strong> {{ $voyage->reference }}
            </div>
            <div class="col-4">
                <strong>Departure -> Arrival: </strong> {{ $voyage->city_from_to }}
            </div>
            <div class="col-4">
                <strong>Date: </strong> {{ $voyage->date_from_to }}
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <strong>Steps: </strong> {{ count($voyage->steps) }}
            </div>
            <div class="col-4">
                <strong>Created at: </strong> {{ $voyage->created_at }}
            </div>
            <div class="col-4">
                <strong>Updated at: </strong> {{ $voyage->updated_at }}
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <h4>Create/Edit Step</h4>
    <div class="row">
        <div class="col-3">
            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', $tripTypes, null, ['class' => 'form-control', 'id' => 'step_type', 'required' ]) }}
            </div>
        </div>
        <div class="col-3">
            <div class="form-group {{ $errors->has('transport_number') ? 'has-error' : '' }}">
                {{ Form::label('transport_number', 'Transport Number') }}
                {{ Form::text('transport_number', '', ['class' => 'form-control', 'autofocus', 'id'=>'transport_number', 'required']) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group {{ $errors->has('departure_date') ? 'has-error' : '' }}">
                {{ Form::label('departure_date', 'Departure Date') }}
                {{ Form::text('departure_date', '', ['class' => 'form-control date', 'autofocus', 'id' => 'departure_date', 'autocomplete'=>'off', 'required' ]) }}
            </div>
        </div>

        <div class="col-3">
            <div class="form-group {{ $errors->has('arrival_date') ? 'has-error' : '' }}">
                {{ Form::label('arrival_date', 'Arrival Date') }}
                {{ Form::text('arrival_date', '', ['class' => 'form-control date', 'autofocus', 'id' => 'arrival_date','autocomplete'=>'off', 'required']) }}
            </div>
        </div>
        <div class="col-3">
            <div class="form-group {{ $errors->has('departure') ? 'has-error' : '' }}">
                {{ Form::label('departure', 'Departure City') }}
                {{ Form::text('departure', '', ['class' => 'form-control', 'autofocus', 'id' => 'departure', 'required']) }}
            </div>
        </div>

        <div class="col-3">
            <div class="form-group {{ $errors->has('arrival') ? 'has-error' : '' }}">
                {{ Form::label('arrival', 'Arrival City') }}
                {{ Form::text('arrival', '', ['class' => 'form-control', 'autofocus', 'id' => 'arrival', 'required']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group {{ $errors->has('seat') ? 'has-error' : '' }}">
                {{ Form::label('seat', 'Seat') }}
                {{ Form::text('seat', '', ['class' => 'form-control', 'autofocus', 'id' => 'seat']) }}
            </div>
        </div>

        <div class="col-3">
            <div class="form-group {{ $errors->has('gate') ? 'has-error' : '' }}">
                {{ Form::label('gate', 'Gate') }}
                {{ Form::text('gate', '', ['class' => 'form-control', 'autofocus', 'id' => 'gate']) }}
            </div>
        </div>

        <div class="col-3">
            <div class="form-group {{ $errors->has('baggage_drop') ? 'has-error' : '' }}">
                {{ Form::label('baggage_drop', 'Baggage Drop') }}
                {{ Form::text('baggage_drop', '', ['class' => 'form-control', 'autofocus', 'id' => 'baggage_drop']) }}
            </div>
        </div>
    </div>

    <div class="btn-group" role="group" aria-label="">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Save</button>

        <button type="button" id="btnCancelStep" class="btn btn-secondary"><i class="fa fa-refresh mr-2"></i> Cancel</button>

        @if($voyage && $voyage->id > 0)
        <a href='{{ route("voyages.destroy" , ['voyage' => $voyage]) }}' id="btnDeleteVoyage" class='btn btn-danger' title='Delete'><i class="fa fa-window-close mr-2"></i>Delete</a>
        @endif

        <a href='{{ route("voyages.index") }}' class='btn btn-light' title='Cancel'><i class="fa fa-arrow-left mr-2"></i>Back</a>
    </div>

    {{ Form::hidden('step_id', 0, ['id' => 'step_id']) }}
    {{ Form::hidden('voyage_id', $voyage->id, ['id' => 'voyage_id']) }}
</div>


<div class="mt-5">
    <table class="table table-striped table-bordered" style="width:100%" id="steps_table">
        <thead>
        <tr>
            <th>Type</th>
            <th>Transport #</th>
            <th>Departure Date</th>
            <th>Arrival Date</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Seat</th>
            <th>Gate</th>
            <th>Baggage Drop</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@push('css')
<link rel="stylesheet" href="{!! asset('/assets/css/jquery-ui.min.css') !!}">
<link rel="stylesheet" href="{!! asset('/assets/css/jquery.datetimepicker.css') !!}">
@endpush

@push('js')
    <script src="{!! asset('assets/js/jquery-ui.min.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/js/jquery.datetimepicker.full.min.js') !!}" type="text/javascript"></script>


    <script type="text/javascript">
        $(function () {
            // Initialize datepicker
            $('.date').datetimepicker({
                'minView': 2,
                'format': 'Y-m-d H:i',
                'timepicker': true,
                'step': 5
            }) ;

            $('#step_type').on('change', function () {
                if ($('#step_type').val() == 'car') {
                    $('#seat').parent('.form-group').hide() ;
                    $('#gate').parent('.form-group').hide() ;
                    $('#baggage_drop').parent('.form-group').hide() ;
                } else if ($('#step_type').val() == 'plane') {
                    $('#seat').parent('.form-group').show() ;
                    $('#gate').parent('.form-group').show() ;
                    $('#baggage_drop').parent('.form-group').show() ;
                } else {
                    $('#seat').parent('.form-group').show() ;
                    $('#gate').parent('.form-group').hide() ;
                    $('#baggage_drop').parent('.form-group').hide() ;
                }
            });

            $('#step_type').trigger('change') ;

            const tblName = '#steps_table';

            const oTable = $(tblName).DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('voyages.steps.list', ['voyage' => $voyage->id?? 0 ]) }}",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                columns: [
                    {data: 'type', name: 'type', orderable: false},
                    {data: 'transport_number', name: 'transport_number', orderable: false},
                    {data: 'departure_date', name: 'departure_date', orderable: false},
                    {data: 'arrival_date', name: 'arrival_date', orderable: false},
                    {data: 'departure', name: 'departure', orderable: false},
                    {data: 'arrival', name: 'arrival', orderable: false},
                    {data: 'seat', name: 'seat', orderable: false},
                    {data: 'gate', name: 'gate', orderable: false},
                    {data: 'baggage_drop', name: 'baggage_drop', orderable: false},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                orderable: false,
                order: [] //[ 2, "asc" ]
            });

            $(tblName).on('click', '.edit.btn', function (e) {
                e.preventDefault();

                var data = oTable.row( $(this).parents('tr') ).data();

                $('#step_id').val(data.id) ;
                $('#step_type').val(data.type).trigger('change') ;
                $('#transport_number').val(data.transport_number) ;
                $('#departure').val(data.departure) ;
                $('#arrival').val(data.arrival) ;

                $('#gate').val(data.gate) ;
                $('#seat').val(data.seat) ;
                $('#baggage_drop').val(data.baggage_drop) ;

                $('#departure_date').val(data.departure_date) ;
                $('#arrival_date').val(data.arrival_date) ;
                $("html, body").animate({ scrollTop: 0 }, "slow");
            });

            $(tblName).on('click', '.delete.btn', function (e) {
                e.preventDefault();

                var actionRoute = $(this).attr('href');

                var isLastRecord = (oTable.data().count() == 1) ;
                const msg = (isLastRecord) ? 'This is the last entry in this trip, are you sure you want to delete it?' : 'Are you sure you want to delete this entry' ;

                if (confirm(msg)) {
                    $.ajax({
                        url: actionRoute,
                        type: 'delete',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        cache: false,
                        success: function(result) {
                            if (!isLastRecord) {
                                oTable.ajax.reload();
                            } else {
                                window.location.href = "{{ route('voyages.index') }}";
                            }
                        }
                    });
                }
            });

            $('#btnCancelStep').on('click', function () {
                $('#step_id').val(0) ; //very imp
                $('#step_type').val('').trigger('change') ;
                $('#transport_number').val('') ;
                $('#departure').val('') ;
                $('#arrival').val('') ;

                $('#departure_date').val() ;
                $('#arrival_date').val() ;

                $('#gate').val('') ;
                $('#seat').val('') ;
                $('#baggage_drop').val('') ;
            });

            $('#btnDeleteVoyage').on('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete the entire voyage and all its related steps?')) {
                    const form = $("#frmEditRecord") ;
                    form.attr('action', $(this).attr('href')) ;
                    form.attr('method', 'POST') ;
                    $('#frmEditRecord [name="_method"]').val('DELETE') ;
                    form.submit() ;
                }
            });
        });
    </script>
@endpush

