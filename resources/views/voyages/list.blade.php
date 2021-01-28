@extends('master')

@section('title', 'List des Voyages')

@section('content')

    <div class="row mb-4">
        <div class="col-6">
            <h2>Voyages</h2>
        </div>
        <div class="col-6" style="text-align: right">
            <a href='{{ route("voyages.create") }}' class='btn btn-primary' title='New'><i class="fa fa-plus-square mr-2"></i>Create new Voyage</a>
        </div>
    </div>

    <table class="table table-striped table-bordered" style="width:100%" id="voyages_table">
        <thead>
        <tr>
            <th></th>
            <th>No</th>
            <th>Reference</th>
            <th>Departure -> Arrival</th>
            <th>Date</th>
            <th>Steps</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@stop

@push('css')
    <style>
        td.details-control {
            background: url({{ asset('./assets/icons/details_open.png') }}) no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url({{ asset('./assets/icons/details_close.png') }}) no-repeat center center;
        }
    </style>
@endpush

@push('js')
<script type="text/javascript">
    $(function () {
        const tblName = '#voyages_table';

        const oTable = $(tblName).DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('voyages.list') }}",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            columns: [
                {
                    className:      'details-control',
                    data:           null,
                    defaultContent: '',
                    orderable: false,
                    searchable: false
                },
                {data: 'id', name: 'id'}, // DT_RowIndex
                {data: 'reference', name: 'reference'},
                {data: 'city_from_to', name: 'city_from_to', orderable: false},
                {data: 'date_from_to', name: 'date_from_to', orderable: false},
                {data: 'nb_of_steps', name: 'nb_of_steps'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            "order": [[ 7, "desc" ]]
        });

        $(tblName).on('click', '.delete.btn', function (e) {
            e.preventDefault();

            var actionRoute = $(this).attr('href');

            if (confirm('Are you sure you want to delete this voyage and all its related steps?')) {
                $.ajax({
                    url: actionRoute,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    cache: false,
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        oTable.ajax.reload();
                    }
                });
            }
        });

        // Add event listener for opening and closing details (subgrid of steps)
        $(tblName + ' tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( showSteps(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        function formatDate(d) {
            return moment(d).format("D MMM YYYY HH:mm");
        }

        // Shows the subgrid of steps for this selected voyage
        function showSteps( row ) {
            var html = '<table class="table table-light table-bordered" style="width:100%; padding-left: 50px" >'+
                '<thead>'+
                '<tr>'+
                '<th>Type</th>'+
                '<th>Transport #</th>'+
                '<th>Departure Date</th>'+
                '<th>Arrival Date</th>'+
                '<th>Departure City</th>'+
                '<th>Arrival City</th>'+
                '<th>Seat</th>'+
                '<th>Gate</th>'+
                '<th>Baggage Drop</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';

            for(var i=0; i < row.steps.length; i++) {
                var step = row.steps[i] ;

                html += '<tr>' ;
                html += '<td>' + step.type + '</td>' ;
                html += '<td>' + step.transport_number + '</td>' ;
                html += '<td>' + formatDate(step.departure_date) + '</td>' ;
                html += '<td>' + formatDate(step.arrival_date) + '</td>' ;
                html += '<td>' + step.departure + '</td>' ;
                html += '<td>' + step.arrival + '</td>' ;
                html += '<td>' + ((step.seat != null) ? step.seat : '') + '</td>' ;
                html += '<td>' + ((step.gate != null) ? step.gate : '') + '</td>' ;
                html += '<td>' + ((step.baggage_drop != null) ? step.baggage_drop : '') + '</td>' ;
                html += '</tr>' ;
            }

            html += '</tbody>' ;
            html += '</table>' ;

            return html ;
        }

    });
</script>
@endpush