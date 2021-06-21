<!-- Layout -->
@extends('layouts.homeContent_layout')
<!-- End Layout -->
@section('title','SUPPLIER')

@section('action_menu')
<li><a class="rdrct" href="{{ url('/supplier_new') }}" target="_self" title="Create New"><span class="fa fa-file"></span></a></li>
@endsection
<!-- START PAGE CONTENT -->
@section('content')
<div class="col-md-12">
    <div>
        <table class="table" id="dttbl">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Other Phones</th>
                    <th>Remark</th>
                    <th width="150px">Option</th>
                </tr>
            </thead>
            <tbody id="body"></tbody>
        </table>
        </div>
    </div>
@endsection
@section('myscript')
<script type="text/javascript" src="{{ URL::asset('theme/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript">
//function for load datatable data
$(document).ready(function(){
  //data_load();
})
function data_load(){
    var table_1=$('#dttbl').DataTable({
        'columns'       : [
                            { data  : "name" },
                            { data  : "address" },
                            { data  : "email" },
                            { data  : "other_phone" },
                            { data  : "remark" },
                            { data  : "sts" }
                        ],
        'ordering'      : false,
        'ajax'          : {
                            "url"     : "{{ url('/suplmas_home_data') }}",
                            "dataSrc" : "data",
                            "data"    : function(data) {},
                            "type"    : "GET",
                            "deferRender": true,
                            "error"   : function(e){
                                            console.log("error");
                                        }
                        },
        'columnDefs'    : [

                            {
                                "targets": 0,
                                "className": "col text-left"
                            },{
                                "targets": 1,
                                "className": "col text-left"
                            },{
                                "targets": 2,
                                "className":"col text-left"
                            },{
                                "targets": 3,
                                "className":"col text-left"
                            },{
                                "targets": 4,
                                "className":"col text-right",
                            },{
                                "targets": 5,
                                "className": "col text-right",
                                render : function(data, type, row, meta) {
                                    var actvbtn = "";
                                    var btnlist = "";
                                    if(row.sts == 1){
                                        actvbtn='<button type="button" style="width:80px;" data-key="'+row.ekp+'" class="btn btn-success prvw" id="'+row.sid+'" >Preview</button>';
                                        btnlist='<li><a data-id="'+row.sts+'" data-key="'+row.eke+'" class="edit" id="'+row.sid+'"><i class="fa fa-edit "></i>Edit</a></li><li>';
                                    }else if(row.sts == 2){
                                        actvbtn='<button type="button" style="width:80px;" data-key="'+row.ekp+'" class="btn btn-suspend prvw" id="'+row.sid+'" >Preview</button>';
                                        btnlist='<li><a href="#" data-id="'+row.sts+'" data-key="'+row.eke+'" class="edit" id="'+row.sid+'"><i class="fa fa-edit "></i>Edit</a></li><li>';
                                    }else{
                                        actvbtn='<button type="button" style="width:80px;" data-key="'+row.ekp+'" class="btn btn-danger prvw" id="'+row.sid+'" >Preview</button>';
                                        btnlist='<li><a href="#" data-id="'+row.sts+'" data-key="'+row.eke+'" class="edit" id="'+row.sid+'"><i class="fa fa-edit "></i>Edit</a></li><li>';
                                    }
                                        return '<div class="btn-group" >'+actvbtn+'<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" role="menu">'+btnlist+'</ul></div>';

                                        }
                }
                ]
            });
}
  //send prvw id to edit page
  $('#dttbl').on('click', '.edit', function(e) {
	e.preventDefault();
    var token=$(this).attr('data-key');
    if(token=='#'){
        document.getElementById('info_msgcontent').innerHTML="Access Forbidden! Sorry, You don't have permission to access this form or report requested by you. It is either read-protected or not readable by the system administrator. Please contact your system administrator for more details about this problem. Sorry for the inconvenience.";
        $('#message-box-info').toggleClass("open");
        return false;
    }else{
        $.redirect( '{{ url("/suplmas_edit") }}' , {"token": token }, "GET", "_self" );
    }
});


  //send prvw id to prvw page
  $('#dttbl').on('click', '.prvw', function(e) {
	e.preventDefault();
    var token=$(this).attr('data-key');
    if(token=='#'){
        document.getElementById('info_msgcontent').innerHTML="Access Forbidden! Sorry, You don't have permission to access this form or report requested by you. It is either read-protected or not readable by the system administrator. Please contact your system administrator for more details about this problem. Sorry for the inconvenience.";
        $('#message-box-info').toggleClass("open");
        return false;
    }else{
        $.redirect( '{{ url("/suplmas_prvw") }}' , {"token": token }, "GET", "_self" );
    }
});

</script>
@endsection
<!-- END PAGE CONTENT -->
