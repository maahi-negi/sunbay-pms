<div class="admin-bar">
    <div class="row">
        <div class="col">
            <div class="dropdown">

                <a class="dropdown-toggle" data-toggle="dropdown">
                    <span class="text-white ad-opt">
                        <i data-feather="plus-circle" class="mr-10"></i>
                        Add Option
                    </span>
                </a>
                <div class="dropdown-menu">
                    @foreach($design_types as $design_type)
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)"> {{$design_type->title}} </a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="add_new_option('{{$design_type->slug}}','{{$design_type->id}}','{{$design_type->title}}')">Add Option</a></li>
                            </ul>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col text-right r-head">
            <span><u>Admin</u></span> &nbsp;
            <a href="javascript:void(0)" onclick="logout()" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 12a1 1 0 0 0 1 1h7.59l-2.3 2.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l4-4a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76a1 1 0 0 0-.21-.33l-4-4a1 1 0 1 0-1.42 1.42l2.3 2.29H5a1 1 0 0 0-1 1ZM17 2H7a3 3 0 0 0-3 3v3a1 1 0 0 0 2 0V5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-3a1 1 0 0 0-2 0v3a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3Z"/></svg>
                Logout
            </a>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="addDesignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:65rem;">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title"> <span class="designtype-title"></span> - <small>Add Design</small></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x-circle"></i>
                </button>
            </div>
            <form id="designForm">
                <input type="hidden" id="designTypeId">
                <input type="hidden" id="designTypelug">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="d-inline-block custom-control custom-radio mr-1">
                            <input type="radio" name="data_from" class="custom-control-input" id="df1" value="1">
                            <label class="custom-control-label" for="df1">Color form Library</label>
                        </div>
                        <div class="d-inline-block custom-control custom-radio mr-1">
                            <input type="radio" name="data_from" class="custom-control-input" id="df2" value="0">
                            <label class="custom-control-label" for="df2">Add New</label>
                        </div>
                        <div class="d-inline-block custom-control custom-radio">
                            <input type="radio" name="data_from" class="custom-control-input" id="df3" value="2">
                            <label class="custom-control-label" for="df3">Add New From Google</label>
                        </div>
                    </div>

                    <div class="box">
                        <div class="get-library" style="display: none;">
                            <select class="form-control selectpicker" id="select-color" data-live-search="true">
                                <option data-tokens="">Select Color</option>
                            </select>
                            <hr>
                        </div>

                        <div class="design-data form-row mb-2">
                            <div class="col">
                                <label class="text-uppercase m-0">Title</label>
                                <input name="title" id="title" class="form-control border" type="text" placeholder="Enter title" required>
                            </div>
                            <div class="col">
                                <label class="d-inline-block text-uppercase m-0">Color / Texture </label><br>
                                <div class="d-inline-block custom-control custom-radio mr-1">
                                    <input type="radio" name="design_type" class="custom-control-input" id="dt-1" value="1" checked>
                                    <label class="custom-control-label" for="dt-1">Color</label>
                                </div>
                                <div class="d-inline-block custom-control custom-radio" style="padding: 10px 25px;">
                                    <input type="radio" name="design_type" class="custom-control-input" id="dt-2" value="2">
                                    <label class="custom-control-label" for="dt-2">Texture</label>
                                </div>
                            </div>
                        </div>

                        <div class="row design-data">
                            <div class="col-sm-6 col-12">
                                <div class="form-group form-row">
                                    <div class="col">
                                        <div class="mr-2">
                                            <label for="image" class="text-uppercase ml-0 thumbnail">Thumbnail/Icon</label>
                                            <figure class="position-relative w-150 mb-0" style="max-width: 200px;">
                                                <img id="image" src="{{asset('media/placeholder.jpg')}}" class="img-thumbnail">
                                                <input type="file" accept="image/jpg,image/jpeg,image/png" id="thumbnailImage" class="d-none">
                                                <label class="btn btn-sm btn-secondary in-block m-0" for="thumbnailImage"> <i class="ft-image"></i> Choose Image</label>
                                            </figure>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-success mx-50 applycropbtn" onclick="applyCropping()" style="display: none">Done With Cropping</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6 col-12 design-option color-options" style="padding-left: 8px;">
                                <div class="form-group">
                                    <label class="text-uppercase">SW Code</label>
                                    <input name="product_id" id="productId" class="form-control border" type="text" placeholder="SW 7005" required>
                                </div>

                                <div class="form-group">
                                    <label class="text-uppercase" style="margin-left: 0">RGB Color </label>
                                    <div>
                                        <input type="text" id="color_red" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="R" required>
                                        <input type="text" id="color_green" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="G" required>
                                        <input type="text" id="color_blue" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="B" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-uppercase" style="margin-left: 0">HEX Color</label>
                                    <input name="hex" id="hex_color" class="form-control border hex-color" type="text" placeholder="#FFFFFF" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label style="border: 1px solid #CCC;padding: 0 7px;">
                                        <a href="#" data-toggle="modal" data-target="#colorPicker">
                                            <span class="ft-edit-2"></span> Color Picker
                                        </a>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12 design-option texture-options" style="width:200px;display: none;">
                                <label class="text-uppercase mb-0">Thumbnail Preview</label>
                                <div class="croppreview">
                                    <figure class="position-relative w-full mb-0">
                                        <img src="{{asset('media/placeholder.jpg')}}" class="img-thumbnail preview">
                                    </figure>
                                </div>

                            </div>
                        </div>
                    </div>

                    <section id="search_box_panel" class="search_box">
                        <h1>Image Search</h1>
                        <div class="flex flex-row" style="width: 80%;margin: 5px auto;text-align: center;">
                            <input type="text" id="searchQuery"  placeholder="Enter search query">
                            <a onclick="searchImages()" class="search_button">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M 9 2 C 5.1458514 2 2 5.1458514 2 9 C 2 12.854149 5.1458514 16 9 16 C 10.747998 16 12.345009 15.348024 13.574219 14.28125 L 14 14.707031 L 14 16 L 20 22 L 22 20 L 16 14 L 14.707031 14 L 14.28125 13.574219 C 15.348024 12.345009 16 10.747998 16 9 C 16 5.1458514 12.854149 2 9 2 z M 9 4 C 11.773268 4 14 6.2267316 14 9 C 14 11.773268 11.773268 14 9 14 C 6.2267316 14 4 11.773268 4 9 C 4 6.2267316 6.2267316 4 9 4 z"></path>
                                </svg>
                            </a>
                            <button onclick="clearSearch()">Clear</button>
                        </div>

                        <div id="imageResults"></div>
                        <div id="page_controls" style="display: none;">
                            <div id="pagination"></div>
                            <button id="previousPageBtn" onclick="previousPage()">Previous Page</button>
                            <button id="nextPageBtn" onclick="nextPage()">Next Page</button>
                        </div>
                        <!--<div id="img_grid"></div>-->
                    </section>

                </div>

                <div class="modal-footer">
                    <button type="button" id="saveButton" onclick="submitForm(1)" class="btn btn-dark text-white m-0 mr-2 save-button">
                        <span class="button-text"> Save </span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>

                    <button type="button" id="submitButton" onclick="submitForm(2)" class="btn btn-dark text-white m-0 save-button">
                        <span class="button-text"> Save & Activate </span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="colorPicker" class="modal fade" role="dialog" style="background: rgb(0 0 0 / 73%);">
    <div class="modal-dialog modal-lg" style="max-width: calc(45%);">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Color Picker</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mr-1">
                    <figure class="position-relative mb-0">
                        <img src="" class="img-thumbnail clrPkr" style="max-width: 300px">
                        <input type="file" id="cp1" class="d-none" accept="image/jpg,image/jpeg,image/png" onchange="readUrl(this, 'colorPicker')">
                        <label class="btn btn-sm btn-secondary in-block m-0" for="cp1"> <i class="ft-image"></i> Choose Image</label>
                    </figure>
                </div>
            </div>
            <div class="modal-footer">
                <p class="mb-0">
                    <span class="haxColor"></span>
                </p>
                <button class="btn save-colors" style="display:none;color: #fff;">
                    Apply Color
                </button>

                <button class="btn btn-primary open-picker" style="display: none;color: #fff;">
                    <i class="ft-edit-2"></i> Pick Color
                </button>
            </div>
        </div>

    </div>
</div>

<script> let SITEURL = "{{url("/")}}"; </script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<style type="text/css">
    #addDesignModal {z-index: 9999;}
    #colorPicker {z-index: 9999;}
    .admin-bar{
        background: #3a3b3d;color: #fff;font-size: 13px;
        padding: 10px 15px;width: 100%;
    }
    .ad-opt{ cursor: pointer; }
    @media screen and (min-width: 992px){
        .r-head{
            padding-right: 15%;
        }
    }
    .in-block{
        position: absolute;top: 3px;left: 5px;
    }
    .croppreviewcss {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid #323232;
        box-shadow: 0 0 4px 0 rgb(95 95 95 / 14%), 0 3px 4px 0 rgb(95 95 95 / 12%), 0 1px 5px 0 rgb(95 95 95 / 20%);
    }
    /* ============ desktop view ============ */
    .dropdown-menu {padding: 0;margin: 0;}
    @media all and (min-width: 992px) {
        .dropdown-menu {margin: 13px 0 !important;}
        .dropdown-item{overflow: unset;}
        .dropdown-menu li{position: relative;}
        .dropdown-menu .submenu{
            display: none;position: absolute;left:100%; top:-7px;margin: 7px 0 !important;
        }
        .dropdown-menu .submenu-left{right:100%; left:auto;}
        .dropdown-menu > li:hover{ background-color: #f1f1f1 }
        .dropdown-menu > li:hover > .submenu{
            display: block;
        }
    }
    /* ============ desktop view .end// ============ */
    /* ============ small devices ============ */
    @media (max-width: 991px) {
        .dropdown-menu .dropdown-menu{
            margin-left:0.7rem; margin-right:0.7rem; margin-bottom: .5rem;
        }
    }
    /* ============ small devices .end// ============ */
    .dropdown-toggle::after {content: none;}
    .nav-link {padding: 0;}
    .modal-header {padding: 6px 17px}

    /* ===== google search ======*/
    div#imageResults {
        min-height: 50vh;
    }
    a.close.close-icon {
        background: red;
        color: #fff;
        border-radius: 100%;
        box-shadow: 0px 0px 3px;
        padding: 3px;
        width: 28px;
        text-align: center;
        font-size: 18px;
        line-height: 24px;
    }
    div#img_grid {
        width: 100%;height: 400px;
    }
    section.search_box {
        padding: 15px;
        /*width: 50%;
        max-width: 450px;*/
        overflow-y: auto;
        height: calc(100% - 26px);
        display: none;
    }
    input#searchQuery { width: 60%; }
    section.search_box h1 {
        font-size: 24px;
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #ccc;
    }
    .search_box button, .search_box a.search_button {
        border: 0;background: #3a3b3d;
        padding: 2px 8px;color: #cecdcd;
        box-shadow: 0px 0px 1px;
        margin-left: 5px;fill: #fff;
    }
    .search_box button svg {
        fill: #cecdcd;
    }
    img.card-img-top {
        width: 100%;
        object-fit: contain;
        height: 160px;
    }
    img#blah6 {
        width: auto;
        height: 240px;
    }

    .img-card {
        width: 20%;padding: 10px;
    }
    .row2 {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        align-content: space-around;
        flex-wrap: wrap;
    }
    .img-card p.card-text { padding: 0; }
    .img-card .card .card-title {
        font-size: 12px;padding-right: initial;height: 25px;
    }
    .img-card .card a {
        font-size: 11px;
    }
</style>
