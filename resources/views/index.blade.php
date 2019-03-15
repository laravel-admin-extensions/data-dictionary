<div class="box">
    <div class="box-header">

        <input type="checkbox" class="dd-select-all"/>

        &nbsp;&nbsp;&nbsp;

        <a class="btn btn-sm btn-default dd-export" title="Export"><i class="fa fa-download"></i><span class="hidden-xs"> Export</span></a>
        <a class="btn btn-sm btn-primary dd-refresh" title="Refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> Refresh</span></a>

        <div class="box-tools">
            <form class="form-inline">
                <div class="form-group">
                    <label>Connections &nbsp;&nbsp;</label>
                    <select class="form-control select-connection">
                        @foreach($connections as $conn)
                            <option {{ ($conn == $connection) ? 'selected' : '' }} value="{{ $conn }}">{{ $conn }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Engine</th>
                <th>Rows</th>
                <th>Data length</th>
                <th>Collation</th>
                <th>Comment</th>
                <th>Create time</th>
                <th>Actions</th>

            </tr>
            @foreach($tables as $table)
            <tr class="dd-table" style="cursor:pointer;" data-loaded="0" data-table="{{ $table['Name'] }}" data-toggle="collapse" data-target="#grid-collapse-{{ $table['Name'] }}">
                <td>
                    <input type="checkbox" class="dd-select" data-table="{{ $table['Name'] }}"/>
                </td>
                <td>{{ $table['Name'] }}</td>
                <td>{{ $table['Engine'] }}</td>
                <td>{{ $table['Rows'] }}</td>
                <td>{{ $table['Data_length'] }}</td>
                <td>{{ $table['Collation'] }}</td>
                <td>{{ $table['Comment'] }}</td>
                <td>{{ $table['Create_time'] }}</td>
                <td>
                    <a class="btn btn-primary btn-xs" href="{{ route('dd-export-table', ['table' => $table['Name'], 'connection' => $connection]) }}" target="_blank">
                        <i class="fa fa-download"></i>&nbsp;&nbsp;Export
                    </a>
                </td>
            </tr>

            <tr style='background-color: #ecf0f5;'>
                <td colspan=9 style='padding:0 !important; border:0;'>
                    <div id="grid-collapse-{{ $table['Name'] }}" class="collapse">
                        <div  style="padding: 10px 10px 0 10px;">

                            <div class="box box-primary">
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover" id="dd-desc-{{ $table['Name'] }}">
                                        <tbody>
                                        <tr>
                                            <th>Field</th>
                                            <th>Type</th>
                                            <th>Collation</th>
                                            <th>Null</th>
                                            <th>Key</th>
                                            <th>Default</th>
                                            <th>Extra</th>
                                            <th>Comment</th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            @endforeach

            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<script lang="javascript">

    $(function () {

        $('.dd-table').on('click', function () {

            var table_row = $(this);

            if (table_row.data('loaded') == 1) {
                return;
            }

            var table_name = table_row.data('table');

            var table = $('#dd-desc-'+table_name+' tbody');

            $.ajax({
                method: 'GET',
                url: "{{route('dd-desc-table')}}",
                data: {table: table_name},
                type: 'json',
                success: function (data) {
                    $.each(data, function(idx, elem){
                        table.append("<tr><td>"+elem.Field+"</td><td>"+elem.Type+"</td><td>"+elem.Collation+"</td><td>"+elem.Null+"</td><td>"+elem.Key+"</td><td>"+elem.Default+"</td><td>"+elem.Extra+"</td><td>"+elem.Comment+"</td></tr>");
                    });

                    table_row.data('loaded', 1)
                }
            });
        });

        $('.dd-select-all')
            .iCheck({checkboxClass:'icheckbox_minimal-blue'})
            .on('ifChanged', function(event) {
            if (this.checked) {
                $('.dd-select').iCheck('check');
            } else {
                $('.dd-select').iCheck('uncheck');
            }
        });

        $('.dd-select')
            .iCheck({checkboxClass:'icheckbox_minimal-blue'})
            .on('ifChanged', function () {
            if (this.checked) {
                $(this).closest('tr').css('background-color', '#ffffd5');
            } else {
                $(this).closest('tr').css('background-color', '');
            }
        });

        $('.dd-refresh').on('click', function() {
            $.pjax.reload('#pjax-container');
            toastr.success('{{ trans('admin.refresh_succeeded') }}');
        });

        $('.dd-export').click(function () {

            var selected = [];
            $('.dd-select:checked').each(function(){
                selected.push($(this).data('table'));
            });

            var url = "{{ route('dd-export-table', ['connection' => $connection]) }}&table="+selected.join();

            window.open(url, '_blank')
        });

        $('.select-connection').on('change', function () {
            var url = "{{route('dd-index')}}" + '?connection=' + this.value;
            $.pjax({url: url, container: '#pjax-container'})
        });

    });

</script>