<!-- Layout -->
@extends('layouts.contentLayout')
<!-- End Layout -->
@section('title','CREATE NEW SUPPLIER')

<!-- Custom Action  -->
@section('action_menu')
    <a class="btn btn-success panel-refresh" id="savfm" target="_self"><span class="fa fa-floppy-o">&nbsp;</span>Save</a>
    <a class="btn btn-info rdrct" href="{{ url('supplier_new') }}" target="_self"><span class="fa fa-clipboard">&nbsp;</span>Clear</a>
    <a class="btn btn-danger rdrct" href="{{ url('supplier_home') }}" target="_self"><span class="fa fa-times">&nbsp;</span>Close</a>
@endsection

<!-- START PAGE CONTENT -->
@section('content')
<div class="row">
    <form action="{{ url('supplier_save') }}" method="POST" id="frmdt" class="pt-2">
        @csrf
        <div class="col-md-12 pb-2">
            <div class="col-md-6" >
                <div class="form-group col-md-12" >
                    <label class="col-md-3 control-label text-left">Supplier Name </label>
                    <div class="col-md-2 col-sm-2">
                            <select class="form-control select" name="nasi" id="nasi">
                                    <option value="">Title</option>
                                    <option value="1" >Rev</option>
                                    <option value="2" selected >Mr</option>
                                    <option value="3" >Mrs</option>
                                    <option value="4" >Miss</option>
                                    <option value="5" >Ms</option>
                            </select>
                        </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="sname" name="sname" autocomplete="off" value="{{ old('sname')}}">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-3 control-label text-left">Supplier Address</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="saddr" name="saddr" autocomplete="off" value="">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-3 control-label text-left">Contact No</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="contacts" name="contacts" autocomplete="off" value="" >
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-3 control-label text-left">Email Address</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="email" name="email" autocomplete="off" value="">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group col-md-12">
                    <label class="col-md-3 control-label text-left">Remark</label>
                    <div class="col-md-6">
                        <textarea class="form-control" style="resize:none" autocomplete="off" id="remark" name="remark" ></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

<!-- END PAGE CONTENT -->

<!-- START SCRIPT -->
@section('myscript')
<script type="text/javascript" src="{{ asset('/theme/js/plugins/bootstrap/bootstrap-select.js') }}"></script>
<script type="text/javascript">
    //Form Validation
    $(document).ready(function(){
        /* $("#frmdt").validate({
            onsubmit : false, //Disables form submit validation
            onkeyup :false,  //Disables onkeyup validation
            onclick : false, //Disables onclick validation of checkboxes and radio buttons
            ignore:[],
            errorPlacement: function(error, element) {
                if (element.hasClass('select')){
                        error.insertAfter(element);
                }else{
                error.insertAfter(element);
                }
            },
            rules:{
                cnam:{required:true,chk_cnam:true}, //customer name
                cadd:{required:true}, // customer address
                alno:{alrtno:true,chk_alno:function(element) { return $("#alno").val()!=""; } }, // alert no
                eadd:{email:true, chk_eadd:function(element) { return $('#eadd').val()!=""; } }, //email address
                crlm:{number:true}, //credit limit
                vtno:{checkvat:function(element) { return ($('#ptst').val()=="3" && $("#vtno").val() != '');}}
            }
        }); */

        //Check Existing Customer name
        jQuery.validator.addMethod("chk_cnam",function(value,element){
            var cnam= $('#cnam').val();
            if(cnam!=''){
                function valdt(){
                    var cid=0;
                    var temp=0;
                    $.ajax({
                        type        : "POST",
                        url         : "{{ url('/customer_name_val') }}",
                        async       : false,
                        data        : {"cnam":cnam,"cid":cid},
                        headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend  : function(){$("body").css("cursor","wait"); $('#cnam').addClass('data_loading');},
                        success     : function(msg){ temp=msg; $("body").css("cursor","default"); $('#cnam').removeClass('data_loading');},
                        error       : function(){ $("body").css("cursor","default"); $('#cnam').removeClass('data_loading'); console.log("Error");  }
                    });
                    return temp;
                }
                var vlrs = valdt();
                    vlrs = parseInt(vlrs);
                if(vlrs > '0'){
                    return false;
                }else {
                    return true;
                }
                }
        },"Already Existing Customer Name");

        //Check Existing Email Address
        jQuery.validator.addMethod("chk_eadd",function(value,element){
            var eadd= $('#eadd').val();
                function valdt(){
                    if(eadd!=''){
                        var cid=0;
                        var temp=0;
                        $.ajax({
                            type        : "POST",
                            url         : "{{ url('/customer_email_val') }}",
                            async       : false,
                            data        : {"eadd":eadd,"cid":cid},
                            headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend  : function(){$("body").css("cursor","wait"); $('#eadd').addClass('data_loading');},
                            success     : function(msg){ temp=msg; $("body").css("cursor","default"); $('#eadd').removeClass('data_loading'); },
                            error       : function(){ $("body").css("cursor","default"); $('#eadd').removeClass('data_loading'); console.log("Error");  }
                        });
                    return temp;
                    }
                }
                var vlrs = valdt();
                if(vlrs==undefined){
                    return true;
                }else{
                    vlrs = parseInt(vlrs);
                    if(vlrs > '0'){
                        return false;
                    }else {
                        return true;
                    }
                }
        },"Already Existing Email Address");

        //Check Existing alert number
        jQuery.validator.addMethod("chk_alno",function(value,element){
            var alno= $('#alno').val();
                function valdt(){
                    if(alno!=''){
                        var cid=0;
                        var temp=0;
                        $.ajax({
                            type        : "POST",
                            url         : "{{ url('/customer_alertno_val') }}",
                            async       : false,
                            data        : {"alno":alno,"cid":cid},
                            headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend  : function(){$("body").css("cursor","wait"); $('#alno').addClass('data_loading');},
                            success     : function(msg){ temp=msg; $("body").css("cursor","default"); $('#alno').removeClass('data_loading');},
                            error       : function(){ $("body").css("cursor","default"); $('#alno').removeClass('data_loading'); console.log("Error");  }
                        });
                        return temp;
                    }
                }
                var vlrs = valdt();
                if(vlrs==undefined){
                    return true;
                }else{
                    vlrs = parseInt(vlrs);
                    if(vlrs > '0'){
                        return false;
                    }else {
                        return true;
                    }
                }
        },"Already Existing Alert Number");

        //check VAT Number
        jQuery.validator.addMethod("checkvat",function(value,element){
            function valdt(){
                var temp=0;
                $.ajax({
                    type:"POST",
                    url:"{{ url('/customer_checkvat_number') }}",
                    async:false,
                    data:{"vtno":value},
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){$("body").css("cursor","wait");$("#"+element.id).addClass('data_loading');},
                    success: function(msg){temp=msg; $("body").css("cursor","default"); $("#"+element.id).removeClass('data_loading');},
                    error:function(){ $("body").css("cursor","default"); console.log("Error"); $("#"+element.id).removeClass('data_loading'); }
                });
                return temp;
            }
            var vlrs=valdt();
            if(vlrs == 0){return false;}else{return true;}
        },"VAT Already Exist");

    });

    //Form Save
    $(document).ready(function(){
        $('#savfm').click(function(e){
            var frmdt= true; //$('#frmdt').valid();
            if(frmdt==true){
                $("body").css("cursor","wait");
                $(".loader").fadeIn('slow');
                document.forms["frmdt"].submit();
            }else{
                console.error('Validation Error');
            }
        });
    });
</script>
@endsection
<!-- END SCRIPT -->
