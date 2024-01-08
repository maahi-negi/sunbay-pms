@extends('layouts.inner')
@section('content')
    <div class="content-header d-flex flex-wrap bg-white" style="padding: 0.8rem 2rem 0.4rem;">
        <div class="content-header-left p-0">
            <h3 class="content-header-title m-0 mr-1">Bulk Data Upload</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper pl-1">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                    </ol>
                </div>
            </div>
            <div class="sync-process text-center mb-1">
                <ul class="text-center">
                    <li> <a class="active text-center" id="ss_step">1</a> </li>
                    <li> <a class="incomplete text-center" id="drm_step">2</a> </li>
                    <li> <a class="incomplete text-center" id="sr_step">3</a> </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="content-overlay"></div>

    <div class="content-wrapper row">
        <div class="content-body col-lg-12 col-md-12 mb-1">
            <div class="card-content">
                <div class="card-body" style="background: #fff;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);">
                    <div id="syncresponse">
                        <div class="fix-sync">
                            <div class="sync-container">
                                <div id="ss_step_div" class="text-center containers">
                                    <h3 class="text-center">Import Files</h3>
                                    <div class="pt-0 pb-2">
                                        <h6 style="font-weight: 500;">Have existing records in your own file? Import your own excel file. If not you can download sample file.</h6>
                                    </div>
                                    <div class="row pl-1 pr-1 justify-content-between align-items-center choose-file-wrap">
                                        <div class="file-upload pr-md-1 pr-0">
                                            <div class="file-select">
                                                <div class="file-select-button" id="fileName">Choose Excel File</div>
                                                <div class="file-select-name" id="noFile">No file chosen...</div>
                                                <input type="file" name="excel_file" id="excelFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="uploadExcelFile(this)">
                                            </div>
                                        </div>
                                        <a href="{{ url('media/bulk-upload-sample.xlsx') }}">
                                            <button class="btn-orange" type="button"> Sample File </button>
                                        </a>
                                    </div>
                                    <h6 class="my-1">OR</h6>
                                    <button class="btn-orange" id="google_import" onclick="googleImport(this)" type="button" style="float:unset;"> Import From Google </button>
                                    <input type="text" class="form-control mx-auto mt-2" placeholder="Enter Google Sheet URL" id="googleSheetUrl" style="max-width:300px; display: none;">

                                    <div class="mt-2 mx-auto border border-light py-1 px-2" style="max-width:450px;">
                                        <label class="text-left d-block text-dark" style="font-weight:500 !important; margin-bottom: 5px;">Import Options</label>
                                        <div class="d-flex flex-sm-row flex-column justify-content-between align-items-center">
                                            <select id="importOptions" class="form-control mr-0 mr-sm-1 mb-1 mb-sm-0" disabled>
                                                <option value="update">Update</option>
                                                <option value="skip" selected>Skip</option>
                                            </select>
                                            <div class="w-100 d-flex align-items-center">
                                                <input type="checkbox" id="importCheck" onclick="changeimportCheck(this)" style="margin-right: 5px;">
                                                <span class="nowrap">Update existing data while importing.</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="drm_step_div" class="table-responsive containers">
                                    <ul class="nav nav-pills mb-0 justify-content-center pt-1" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="pills-homePlan-tab" data-toggle="pill" href="#pills-homePlan" role="tab" aria-controls="pills-homePlan" aria-selected="true">Home Plans</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="pills-designType-tab" data-toggle="pill" href="#pills-designType" role="tab" aria-controls="pills-designType" aria-selected="false">Design Type</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="pills-design-options-tab" data-toggle="pill" href="#pills-design-options" role="tab" aria-controls="pills-design-options" aria-selected="false">Design Options</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-homePlan" role="tabpanel" aria-labelledby="pills-homePlan-tab">
                                            <div class="d-flex justify-content-between border-bottom bg-light" style="padding:1.21rem;">
                                                <h6 class="mb-0 w-100">Column To Import</h6>
                                                <h6 class="mb-0 w-100" style="max-width:300px;">Map Into Field</h6>
                                            </div>
                                            <div class="mapping-fields-wrapper" id="homePlan-tab">
                                                <p class="syncloader text-center my-2" style="display:none;"><img src="{{asset('images/spinner.gif')}}"></p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-designType" role="tabpanel" aria-labelledby="pills-designType-tab">
                                            <div class="d-flex justify-content-between border-bottom bg-light" style="padding:1.21rem;">
                                                <h6 class="mb-0 w-100">Column To Import</h6>
                                                <h6 class="mb-0 w-100" style="max-width:300px;">Map Into Field</h6>
                                            </div>
                                            <div class="mapping-fields-wrapper" id="designType-tab">
                                                <p class="syncloader text-center my-2" style="display:none;"><img src="{{asset('images/spinner.gif')}}"></p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-design-options" role="tabpanel" aria-labelledby="pills-design-options-tab">
                                            <div class="d-flex justify-content-between border-bottom bg-light" style="padding:1.21rem;">
                                                <h6 class="mb-0 w-100">Column To Import</h6>
                                                <h6 class="mb-0 w-100" style="max-width:300px;">Map Into Field</h6>
                                            </div>
                                            <div class="mapping-fields-wrapper" id="design-options-tab">
                                                <p class="syncloader text-center my-2" style="display:none;"><img src="{{asset('images/spinner.gif')}}"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="sr_step_div" class="containers">
                                    <h3 class="text-center">Report</h3>
                                    <p class="uploadloader text-center my-2"><img src="{{asset('images/spinner.gif')}}"></p>
                                    <div class="report-wrap">
                                        <div class="text-center sr-ans"> <i class="material-icons">done</i> <span>All data has been imported successfully.</span>
                                            <ul class="same-btns">
                                                <li> <a href="{{route('bulk-data')}}">Import More Data </a> </li>
                                            </ul>
                                        </div>
                                        <div class="sr-synop">
                                            <h6>Activity Log </h6>
                                            <p> <span class="border-bottom"> <b class="badge badge-success">0</b> New entries has been imported successfully.  </span> </p>
                                            <p> <span class="border-bottom"> <b class="badge badge-light">0</b> entries has been skipped.  </span> </p>
                                            <p> <span class="border-bottom"> <b class="badge badge-danger">0</b> Entries failed to import. </span> </p>
                                            <p> <span class="border-bottom"> <b class="badge badge-info">0%</b> Import Process Completed. </span> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-buttons">
                            <div class="btn-group">
                                <a style="position:relative; margin-right:5px;" id="backButton" href="javascript:;" onclick="changeStep(false)" class="add_button"><i style="top:0;" class="fa fa-arrow-left"></i> Back </a>
                                <a style="position:relative;" href="javascript:;" id="importButton" onclick="changeStep(true)" class="add_button"> <span>Next</span> <i style="top:0;" class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <script>
        // buttonClicked = true for next and false for back
        let step = 1;
        const changeStep = (buttonClicked) => {
            if(buttonClicked == true) {
                if(step < 4)
                    step++;
            }
            else
            {
                if(step > 0)
                    step--;
            }
            switch(step)
            {
                case 1:
                    $('#ss_step').addClass('active').removeClass('incomplete');
                    $('#drm_step').addClass('incomplete').removeClass('active');
                    $("#importButton span").text('Next');
                    $(".containers").hide();
                    $('#ss_step_div').fadeIn();
                    $('#backButton').fadeOut();
                    break;
                case 2:
                    let validationFailed;
                    let googleSheetUrl = $('#googleSheetUrl').val();
                    if(!$('#importCheck').is(':checked')){
                        toastr.error("Please select Import Options.");
                        step = 1;
                        break;
                    }
                    if(googleSheetUrl=='') {
                        validationFailed = dataToShowInMapSection();
                    }else{
                        validationFailed = googleSheetImport();
                    }
                    if(validationFailed)
                    {
                        step = 1;
                        return;
                    }
                    $('#ss_step').addClass('complete').removeClass('active');
                    $('#drm_step').addClass('active').removeClass('incomplete');
                    $("#importButton span").text('Import');
                    $(".containers").hide();
                    $('#backButton').fadeIn();
                    $('#drm_step_div').fadeIn();
                    break;
                case 3:
                    let finalStep =  uploadData();
                    if(!finalStep){
                        step = 2;
                        return;
                    }
                    $('#drm_step').addClass('complete').removeClass('active');
                    $('#sr_step').addClass('active').removeClass('incomplete');
                    $('.footer-buttons').hide();
                    $(".containers").hide();
                    $('#sr_step_div').fadeIn();
                    break;
            }
        }
        var file = null;
        var filename;
        function uploadExcelFile(e){
            $("#googleSheetUrl").val('').fadeOut();
            file = e.files[0];
            filename = $("#excelFile").val();
            if (/^\s*$/.test(filename)) {
                $(".file-upload").removeClass('active');
                $("#noFile").text("No file chosen...");
            }
            else {
                $(".file-upload").addClass('active');
                $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
            }
        }
        //Bulk Upload
        dataToShowInMapSection = () => {
            var formData = new FormData();
            formData.append('excelFile', file);
            if(file != null){
                var fileExtension = filename.split('.').pop();
                if(fileExtension == 'xlsx'){
                    $.ajax({
                        type        : "post",
                        url         : "{{url("/api/map/sheet/columns")}}",
                        headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data        : formData,
                        cache       : false,
                        contentType : false,
                        processData : false,
                        beforeSend  : function(){
                            $('.syncloader').fadeIn();
                        },
                        success : function(response) {
                            console.log(response.headings);
                            if(response.headings.hasOwnProperty('Home Plans')) {
                                let homePlanOptions = `<option value=''>No Option Selected</option>`;
                                $.each(response.home_plans,(key,val)=>{
                                    homePlanOptions+=`<option value='${key}'>${val}</option>`;
                                });

                                let homePlanData = ``;
                                $.each(response.headings['Home Plans'][0],(key,val)=>{
                                    homePlanData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="home_plan_dropdown_${key}" onchange="userMappedData('home_plan',${key},'${val}')">
										    ${homePlanOptions}
										</select>
									</div>`;
                                });
                                $('#homePlan-tab').html(homePlanData);
                            } else {
                                $('#homePlan-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is HomPlans.</div>`)
                            }

                            //Design Type tab data formation
                            if(response.headings.hasOwnProperty('Design Types')) {
                                let designTypeOptions = `<option value=''>No Option Selected</option>`;
                                $.each(response.design_type,(key,val)=>{
                                    designTypeOptions+=`<option value='${key}'>${val}</option>`;
                                });
                                var designTypeData = ``;
                                $.each(response.headings['Design Types'][0],(key,val)=>{
                                    designTypeData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label data-index='${key}' class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="design_type_dropdown_${key}" onchange="userMappedData('design_type',${key},'${val}')">
										    ${designTypeOptions}
										</select>
									</div>`;
                                });
                                $('#designType-tab').html(designTypeData)
                            } else {
                                $('#designType-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is Design Type.</div>`)
                            }

                            //design-options type data adding here
                            if(response.headings.hasOwnProperty('Design Options'))
                            {
                                let designOptions = `<option value=''>No Option Selected</option>`;
                                $.each(response.design_options,(key,val)=>{
                                    designOptions+=`<option value='${key}'>${val}</option>`;
                                });
                                var designData = ``;
                                $.each(response.headings['Design Options'][0],(key,val)=>{
                                    designData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label data-index='${key}' class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="design_options_dropdown_${key}" onchange="userMappedData('design_options',${key},'${val}')">
								    		${designOptions}
										</select>
									</div>`;
                                });
                                $('#design-options-tab').html(designData)
                            } else {
                                $('#design-options-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is Design Options.</div>`)
                            }

                            return false;
                        },
                        error : function(error){
                            toastr.error(error.responseJSON.message);
                        },
                        complete : function(){
                            $('.syncloader').hide();
                        }
                    });
                } else {
                    toastr.error("Please choose an excel file.");
                    return true;
                }
                return false;
            } else {
                toastr.error("Please choose an excel file.");
                return true;
            }
        }

        let mappedArray = {};
        let count = 0;
        mappedArray['home_plans'] = {};
        mappedArray['design_types'] = {};
        mappedArray['design_options'] = {};
        let [homePlans,design_type,design_options] = [{},{},{}];

        function userMappedData(type,index,label) {
            switch(type){
                case 'home_plan':
                    if($('#home_plan_dropdown_'+index).val()==''){
                        delete homePlans[label];
                        count--;
                    } else {
                        homePlans[label] = $('#home_plan_dropdown_'+index).val();
                        count++;
                    }
                    mappedArray['home_plans'] = homePlans;
                    break;

                case 'design_type':
                    if($('#design_type_dropdown_'+index).val()=='')
                    {
                        delete design_type[label];
                        count--;
                    }
                    else
                    {
                        design_type[label] = $('#design_type_dropdown_'+index).val();
                        count++;
                    }
                    mappedArray['design_types'] = design_type;
                    break;

                case 'design_options':
                    if($('#design_options_dropdown_'+index).val()=='')
                    {
                        delete design_options[label];
                        count--;
                    }
                    else
                    {
                        design_options[label] = $('#design_options_dropdown_'+index).val();
                        count++;
                    }
                    mappedArray['design_options'] = design_options;
                    break;
                default:
                    break;
            }
        }

        function uploadData()
        {
            if(Object.keys(mappedArray.home_plans).length!=0)
            {
                let swappedObj = swap(mappedArray.home_plans);
                console.log(mappedArray.home_plans);
                if(!swappedObj.hasOwnProperty('title'))
                {
                    toastr.error("HomePlan Title should be mapped in HomePlan section.");
                    return false;
                }
            }
            if(Object.keys(mappedArray.design_types).length!=0)
            {
                let swappedObj = swap(mappedArray.design_types);
                if(!swappedObj.hasOwnProperty('elevation_id') || !swappedObj.hasOwnProperty('title'))
                {
                    toastr.error("HomePlan Title and Design Type Title should be mapped in Design Type section.");
                    return false;
                }
            }
            if(Object.keys(mappedArray.design_options).length!=0)
            {
                let swappedObj = swap(mappedArray.design_options);
                console.log(swappedObj);
                if(!swappedObj.hasOwnProperty('elevation_id') || !swappedObj.hasOwnProperty('home_design_type_id') || !swappedObj.hasOwnProperty('title'))
                {
                    toastr.error("HomePlan Title,Design Type and Option Title should be mapped in design options section.");
                    return false;
                }
            }

            if(count!=0)
            {
                mappedArray['import_as'] = $('#importOptions').val();
                let dat = JSON.stringify(mappedArray);
                $.ajax({
                    type 		:'post',
                    url  		: '{{url("/api/mega-import")}}',
                    headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data        : {'mapped':dat},
                    success		: function(response){
                        $('.badge-success').html(response.success);
                        $('.badge-danger').html(response.fail);
                        $('.badge-light').html(response.skip);
                        $('.badge-info').html(`${response.percentage}%`);
                        $(".uploadloader").hide();
                        $('.report-wrap').fadeIn();
                    },
                    error : function(error){

                    },
                })
                return true;
            }
            else
            {
                toastr.error("Please select atleast one field to map.");
                return false;
            }
        }

        function swap(json){
            var ret = {};
            for(var key in json){
                ret[json[key]] = key;
            }
            return ret;
        }

        function changeimportCheck(e){
            if(e.checked) {
                $("#importOptions").removeAttr('disabled');
            } else {
                $("#importOptions").attr('disabled', true);
            }
        }

        function googleImport(e){
            file = null;
            filename = null;
            $("#excelFile").val('');
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen...");
            $(e).next().fadeIn();
        }

        googleSheetImport = ()=>
        {
            $.ajax({
                type        : "post",
                url         : "{{url("/api/map/sheet/columns")}}",
                headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data		: {'url':$('#googleSheetUrl').val()},
                beforeSend  : function(){
                    $('.syncloader').fadeIn();
                },
                success     : function(response)
                {
                    //Home Plan tab data formation
                    if(response.headings.hasOwnProperty('Home Plans')) {
                        let homePlanOptions = `<option value=''>No Option Selected</option>`;
                        $.each(response.home_plans,(key,val)=>{
                            homePlanOptions+=`<option value='${key}'>${val}</option>`;
                        });
                        let homePlanData = ``;
                        $.each(response.headings['Home Plans'][0],(key,val)=>{
                            homePlanData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="home_plan_dropdown_${key}" onchange="userMappedData('home_plan',${key},'${val}')">
										    ${homePlanOptions}
										</select>
									</div>`;
                        });
                        $('#homePlan-tab').html(homePlanData);
                    } else {
                        $('#homePlan-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is HomPlans.</div>`)
                    }

                    //Design Type tab data formation
                    if(response.headings.hasOwnProperty('Design Types')) {
                        let designTypeOptions = `<option value=''>No Option Selected</option>`;
                        $.each(response.design_type,(key,val)=>{
                            designTypeOptions+=`<option value='${key}'>${val}</option>`;
                        });
                        var designTypeData = ``;
                        $.each(response.headings['Design Types'][0],(key,val)=>{
                            designTypeData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label data-index='${key}' class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="design_type_dropdown_${key}" onchange="userMappedData('design_type',${key},'${val}')">
										    ${designTypeOptions}
										</select>
									</div>`;
                        });
                        $('#designType-tab').html(designTypeData)
                    } else {
                        $('#designType-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is Design Type.</div>`)
                    }

                    //design-options type data adding here
                    if(response.headings.hasOwnProperty('Design Options'))
                    {
                        let designOptions = `<option value=''>No Option Selected</option>`;
                        $.each(response.design_options,(key,val)=>{
                            designOptions+=`<option value='${key}'>${val}</option>`;
                        });
                        var designData = ``;
                        $.each(response.headings['Design Options'][0],(key,val)=>{
                            designData+=`<div class="d-flex justify-content-between align-items-center px-1 mt-1 mb-1 border-bottom">
										<label data-index='${key}' class="w-100 m-0 text-dark">${val}</label>
										<select class="form-control" style="max-width: 300px;" id="design_options_dropdown_${key}" onchange="userMappedData('design_options',${key},'${val}')">
								    		${designOptions}
										</select>
									</div>`;
                        });
                        $('#design-options-tab').html(designData)
                    } else {
                        $('#design-options-tab').html(`<div class="alert alert-danger mt-1" role="alert">There is no corresponding record found in the sheet make sure sheet name is Design Options.</div>`)
                    }
                    //Elevation tab data formation
                    return false;
                },
                error       : function(error){
                    toastr.error('Either you have entered wrong url or Make sure your google sheet has public access.');
                },
                complete	: function(){
                    $('.syncloader').hide();
                }
            });
        }
    </script>
@endpush
