const eyeDropper = new EyeDropper()
const pickerBtn = document.querySelector('.open-picker')
let thumbnail = null,view1 = null, isChange = null, tempThumb = null;
let brick1 = null,brick2 = null,brick3 = null,brick_pattern = null;
let simg = '';
var image = document.getElementById("image");
var cropper, b = 1, cropper_type = "";

// logout
function logout(){
    let form = document.createElement("form");
    $(document.body).append(form);
    form.method = "POST";
    form.action = SITEURL+"/logout";
    // csrf token
    var csrf = document.createElement("input");
    csrf.name="_token"
    csrf.value = $('meta[name="csrf-token"]').attr('content');
    form.appendChild(csrf);
    form.submit();
}

// Get FileType
const fileType = (file) => {
    return file.type.split('/').pop().toLowerCase();
}

/**
 * image validation
 * @returns {boolean}
 */
const imageValidation = () => {
    if(thumbnail == null){
        toastr.clear()
        toastr.error(`Thumbnail is required`);
        return false;
    }
}


/**
 * read File URl
 * @param input
 * @param element
 * @returns {boolean}
 */
function readUrl(input, element) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var img_file = input.files[0];
        if (fileType(img_file) != "jpeg" && fileType(img_file) != "jpg" && fileType(img_file) != "png") {
            toastr.clear()
            toastr.error('Only jpeg, jpg, png formats are allowed. Please select valid file.');
            return false;
        }else{
            reader.onload = function (e) {
                $(input).prev().attr('src', e.target.result);

                if(element=="brick1" || element=="brick2" || element=="brick3"){
                    var brick_images = [];
                    $(".brk-prev").each(function(){
                        brick_images.push(this.src)
                    });
                    loadImages(brick_images, function(e) {
                        getPatternImage(e,brick_images.length);
                    });
                }
                if(element=="colorPicker"){
                    $(".open-picker").show();
                }
            };
            reader.readAsDataURL(input.files[0]);
            switch(element){
                case 'thumbnail':
                    thumbnail = input.files[0];
                    isChange = 'thumbnail';
                    break;
                case 'brick1':
                    brick1 = input.files[0];
                    isChange = 'brick1';
                    break;
                case 'brick2':
                    brick2 = input.files[0];
                    isChange = 'brick2';
                    break;
                case 'brick3':
                    brick3 = input.files[0];
                    isChange = 'brick3';
                    break;
                case 'view1':
                    view1 = input.files[0];
                    isChange = 'view1';
                    break;
                default:
                    break;
            }
        }
    }
}

/**
 * Save form data
 * @param sType
 * @returns {boolean}
 */
function submitForm(sType){
    const title = $('#title').val();
    const productId = $('#productId').val();
    const design_type = $('input[name="design_type"]:checked').val();
    const category = $('select[name="category"]').val();
    const sub_category = $('select[name="sub_category"]').val();

    const designTypeId = $("#designTypeId").val();
    const designTypelug = $("#designTypelug").val();
    const bricks = [];
    // data form library or new add
    const data_from = $("input[name=data_from]:checked").val();
    let colorR = $('#color_red').val();
    let colorG = $('#color_green').val();
    let colorB = $('#color_blue').val();
    let colorHex = $('#hex_color').val();

    if(design_type=="1"){
        if((colorR != '' && colorG != '' && colorB !='') && colorHex==""){
            colorHex = RGBtoHex(parseInt(colorR), parseInt(colorG), parseInt(colorB));
            $("#hex_color").val(colorHex);
        }else if(colorHex!="" && (colorR == '' || colorG == '' || colorB =='')){
            var rgb_color = HexToRGB(colorHex);
            colorR = rgb_color[0];
            colorG = rgb_color[1];
            colorB = rgb_color[2];
            $('#color_red').val(colorR);
            $('#color_green').val(colorG);
            $('#color_blue').val(colorB);
        }
    }
    // Validations
    if(title == ''){
        toastr.clear()
        toastr.error('Title field is required');
        return false;
    }
    if(!(/^[A-Za-z0-9 ]+$/.test(title))){
        toastr.clear()
        toastr.error('Title field should only contain alphabets.');
        return false;
    }

    if(design_type=="1"){
        if((colorR == '' || colorG == '' || colorB =='') && colorHex ==''){
            toastr.clear()
            toastr.error('Please provide RGB or Hex Color');
            return false;
        }
        if(colorR > 255 || colorG > 255 || colorB > 255){
            toastr.clear()
            toastr.error('Invalid color');
            return false;
        }

        if(productId == ''){
            toastr.clear()
            toastr.error('SW Code field is required');
            return false;
        }
        if(!(/^[A-Za-z0-9 ]+$/.test(productId))){
            toastr.clear()
            toastr.error('SW Code field should only contain alphabets.');
            return false;
        }
    }else{
        if(imageValidation() == false){
            toastr.clear()
            toastr.error('Please select thumbnail/texture image');
            return false;
        }
    }
    const formData = new FormData();
    formData.append('title', title);
    formData.append('product_id', productId);
    formData.append('colorR', colorR);
    formData.append('colorG', colorG);
    formData.append('colorB', colorB);
    formData.append('hex_color', colorHex);
    formData.append('status', "1");
    formData.append('design_type', design_type);
    formData.append('thumbnail', (thumbnail)?thumbnail:"");
    formData.append('image_layer', (thumbnail)?view1:"");
    formData.append('category', category);
    formData.append('sub_category', sub_category);
    formData.append('isApplied', sType);

    if($(".brick-imgs").has('.col')){
        var m = $(".brick-imgs").find(".col")
        m.each(function(){
            if($(this).find('input[type="radio"]').is(':checked')) {
                var b1 = $(this).find('input[type="radio"]:checked').val();
                var bn = $(this).find('input[type="radio"]:checked').attr("name");
                bn = bn.replace("brick_image","");
                bricks[bn] = b1;
            }
        });
        formData.append('bricks_type', (bricks)?JSON.stringify(bricks):"");
        formData.append('brick1', (brick1)?brick1:"");
        formData.append('brick2', (brick2)?brick2:"");
        formData.append('brick3', (brick3)?brick3:"");
        formData.append('brick_pattern', (brick_pattern)?brick_pattern:"");
    }

    formData.append('design_type_id', designTypeId);
    $("#addDesignModal").find('.save-button').addClass('disable');
    if(sType=="1"){
        $("#addDesignModal").find('#saveButton .button-text').addClass('hide-button-text');
        $("#addDesignModal").find('#saveButton .spinner-border').addClass('show-spinner').show();
    }else{
        $("#addDesignModal").find('#submitButton .button-text').addClass('hide-button-text');
        $("#addDesignModal").find('#submitButton .spinner-border').addClass('show-spinner').show();
    }
    $.ajax({
        type: 'post',
        url: `${SITEURL}/api/add-design-option`,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            removeSelected(`${response.design_type.slug}`);
            var isCate = (response.design.category)?"cate"+response.design.category:"",
                isSubCate = (response.design.sub_category)?"subcate"+response.design.sub_category:"";
            let card = `<div class="col-sm-4 col-6">
                <div class="design-container image-icons-wrap mb-1 ${isCate} ${isSubCate} ${(sType == "2")?'color-active':''}">
                    <div data-design-title="${response.design_type.title}" data-value="${response.design.title}" class="w-100 design back-image ${(sType == "2")?'fade-image':''}" style="background: ${response.background}; background-repeat:repeat; background-size: contain; background-position: center;"></div>
                    <span class="text-white d-flex ${(sType == "2")?'show-buttons':''}">
                        <i data-feather="check-circle" class="mr-1 check-color-option ${(sType == "2")?'button-active':''}"
                           data-design-group-view1="${response.baseImg1}"
                           data-design-group-view2="${response.baseImg2}"
                           data-design-type="${response.design_type.slug}"
                           data-design-view1="${response.image_view1}"
                           data-design-view2="${response.image_view2}"
                           data-design-view3="${response.image_view3}"
                           data-rbg-color="${(response.design.rgb_color)?response.design.rgb_color:""}"
                           data-type="${(response.design.design_type)?response.design.design_type:"1"}"
                           data-texture="${response.dataTexture}"
                           data-additional-texture="${response.additional_pattern}"></i>
                    </span>
                </div>
                <p class="text-capitalize p-0 m-0" data-price="${response.design.price}">
                    ${response.dtitle}<br> ${response.newStr}
                </p>
            </div>`;
            $("#"+designTypelug+"Data").find(".add-new-option").before(card);

            feather.replace();
            if(sType == "2" || design_type=="2"){
                reload_js(SITEURL+'/js/main.js');
            }else{
                $(".image-icons-wrap").on("mouseenter", function() {
                    $(this).find("span").addClass("show-buttons"), $(this).find(".back-image").addClass("fade-image");
                }),
                    $(".image-icons-wrap")
                        .not(".image-icons-wrap span")
                        .on("mouseleave", function() {
                            $(this).hasClass("design-active") || $(this).hasClass("color-active") || ($(this).find("span").removeClass("show-buttons"), $(this).find(".back-image").removeClass("fade-image"));
                        });
            }
            refreshData();

            $('#addDesignModal').modal('hide');
            $("#addDesignModal").find('.save-button').removeClass('disable');
            $("#addDesignModal").find('.save-button .button-text').removeClass('hide-button-text');
            $("#addDesignModal").find('.save-button .spinner-border').removeClass('show-spinner');
        },
        error: function(error){
            status = false;
            toastr.error(error.responseJSON.msg);
        }
    });
}

/**
 * remove selected options
 */
function removeSelected(e){
    $("#"+e+"Data").find(".design-container").find(".check-color-option").removeClass("button-active"),
        $("#"+e+"Data").find(".design-container").find("span").removeClass("show-buttons"),
        $("#"+e+"Data").find(".design-container").find(".back-image").removeClass("fade-image"),
        $("#"+e+"Data").find(".design-container").removeClass("color-active");
}

/**
 * reload js files
 * @param src
 */
function reload_js(src) {
    $('script[src="' + src + '"]').remove();
    $('<script/>',{type:'text/javascript', src: src}).appendTo('head');
}

/**
 * refresh data
 */
function refreshData(){
    var e = $(".design-active").find(".button-active").attr("data-target");
    $(".content-container").hide(), $(e).fadeIn(), selectColorOption(e), filterCategory();
}

/**
 * add new options
 * @param slug
 * @param d_id
 * @param title
 */
function add_new_option(slug,d_id,title){
    var modal = $('#addDesignModal');
    $(".designtype-title").html(title);
    $("#designTypeId").val(d_id);
    $("#designTypelug").val(slug);
    get_colors(slug);
    $("#search_box_panel, #page_controls").hide();
    $(".get-library, .design-data, .texture-options").hide();

    var form = document.getElementById('designForm');
    form.reset();
    $("#cp1").val(null);
    modal.find('#thumbnailImage').prev().attr('src', `${SITEURL}/media/placeholder.jpg`);
    modal.find('.design-data').hide();
    modal.find('.save-button').removeClass('disable');
    modal.find('.save-button .button-text').removeClass('hide-button-text');
    modal.find('.save-button .spinner-border').removeClass('show-spinner').hide();
    modal.modal('show');
}

/**
 * get color options
 */
function get_colors(designTypeSlug){
    var formData = new FormData();
    // formData.append('type', designTypeSlug);
    formData.append('type', "");
    $.ajax({
        type: 'post',
        url: SITEURL + "/api/get-colors",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                let ol = `<option value="">Select Color</option>`;
                $.each(response.colors, function (k, c) {
                    ol += `<option value="${c.title},${c.sw_code},${c.red},${c.green},${c.blue},${c.hex_color},${c.texture}">${c.title} - ${c.sw_code}</option>`;
                });
                $(".selectpicker").html(ol);
                // $(".selectpicker").selectpicker("refresh");
            } else {
                console.log("color not found.");
            }
        }
    });
}

/**
 * convert Hex color code to RGB code
 * @param hex
 * @returns {[number, number, number]}
 */
const HexToRGB = (hex) => {
    const red = parseInt(hex.substring(1, 3), 16);
    const green = parseInt(hex.substring(3, 5), 16);
    const blue = parseInt(hex.substring(5, 7), 16);
    return [red, green, blue];
}

const componentToHex = (c) => {
    const hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

/**
 * convert RGB color code to HEX
 * @param r
 * @param g
 * @param b
 * @returns {string}
 * @constructor
 */
const RGBtoHex = (r, g, b) => {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

$(document).ready(function (){
    $(".rgb-color").keyup(function (){
        const colorR = $('#color_red').val();
        const colorG = $('#color_green').val();
        const colorB = $('#color_blue').val();
        if(colorR != '' && colorG != '' && colorB !=''){
            var hexColor = RGBtoHex(parseInt(colorR), parseInt(colorG), parseInt(colorB));
            $("#hex_color").val(hexColor);
        }
    });

    $("#hex_color").keyup(function (){
        const colorHex = $(this).val();
        if(colorHex != '' && colorHex.length >= 7){
            var rgb_color = HexToRGB(colorHex);
            $('#color_red').val(rgb_color[0]);
            $('#color_green').val(rgb_color[1]);
            $('#color_blue').val(rgb_color[2]);
        }
    });

    $("input[name=design_type]").change(function (){
        $("#thumbnailImage").val(null);
        if(cropper){
            resetCropping();
        }else{
            $('#image').attr('src', `${SITEURL}/media/placeholder.jpg`);
            thumbnail = null;
        }
        $(".design-option, .new-layer, .generate-layer").hide();
        var design_type = $(this).val();
        if(design_type=="1"){
            $(".color-options").show();
            $(".thumbnail").html("Thumbnail/Icon");
        }else if(design_type=="3"){
            $(".thumbnail").html("Thumbnail");
            $(".new-layer").show();
        }else{
            $(".thumbnail").html("Thumbnail/Texture");
        }
    });

    pickerBtn.addEventListener('click', function() {
        eyeDropper.open()
            .then(res => {
                var rgb_color = HexToRGB(res.sRGBHex);
                $(".haxColor").html(`Hex Color: ${res.sRGBHex}, <br> RGB (${rgb_color[0]}, ${rgb_color[1]}, ${rgb_color[2]})`);
                $(".save-colors").show().css("background",res.sRGBHex);
                // set value
                $("#hex_color").val(res.sRGBHex);
                $('#color_red').val(rgb_color[0]);
                $('#color_green').val(rgb_color[1]);
                $('#color_blue').val(rgb_color[2]);
            })
            .catch(err => {
                console.log("User canceled the selection.");
            })
    });

    $(".save-colors").click(function (){
        $("#colorPicker").modal("hide");
        $(".clrPkr").attr("src","");
        $("#cp1").val(null);
        $(".haxColor").html("");
        $(".open-picker, .save-colors").hide();
    });

    /* select color form library or add new */
    $("input[name=data_from]").change(function (){
        var data_from = $(this).val();
        if(data_from==1) {
            $(".get-library").show();
            $(".design-data").hide();
            $(".library-check").hide();
            $("#search_box_panel").hide();
        }else if(data_from==2){
            $("#search_box_panel").show();
            $(".get-library, .design-data, .library-check").hide();
        }else{
            $(".get-library").hide();
            $("#search_box_panel").hide();
            $(".design-data").show();
            $(".library-check").show();
        }
    });

    $(".selectpicker").change(function(){
        let v = $(this).val();
        let c_val = v.split(',');
        var modal = $('#addDesignModal');
        modal.find('#title').val(c_val[0]);
        modal.find('#productId').val(c_val[1]);
        modal.find('#color_red').val(c_val[2]);
        modal.find('#color_green').val(c_val[3]);
        modal.find('#color_blue').val(c_val[4]);
        modal.find('#hex_color').val(c_val[5]);
        if(c_val[6]!="" && c_val[6]!="null"){
            modal.find('#thumbnailImage').prev().attr('src', `${c_val[6]}`);
            modal.find('.preview').attr('src', `${c_val[6]}`);
            thumbnail = c_val[6];
        }else{
            modal.find('#thumbnailImage').prev().attr('src', `${SITEURL}/media/placeholder.jpg`);
        }

        $(".design-option").hide();
        if(c_val[5] || (c_val[2] && c_val[3] && c_val[4])){
            $("#dt-1").prop('checked', true);
            $(".color-options").show();
            $(".thumbnail").html("Thumbnail/Icon");
        }else{
            $("#dt-2").prop('checked', true);
            $(".thumbnail").html("Thumbnail/Texture");
        }

        $(".design-data").show();
    });
});

/**
 * on change thumbnail call function
 */
$("body").on("change", "#thumbnailImage", function (e) {
    cropper_type = "";
    $(".brick-imgs").hide();
    simg = '';
    var files = e.target.files;
    var done = function (url) {
        image.src = url;
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
        if(cropper){resetCropping();}
        file = files[0];
        if (URL) {
            done(URL.createObjectURL(file));
            reader = new FileReader();
            reader.onload = function (e) {
                simg = reader.result;
            };
            reader.readAsDataURL(file);
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                done(reader.result);
                simg = reader.result;
            };
            reader.readAsDataURL(file);
        }
    }

    var design_type = $("input[name=design_type]:checked").val();
    if(design_type=="1" || design_type=="3"){
        thumbnail = file;
        isChange = 'thumbnail';
        $('.applycropbtn').hide();
        $(".texture-options").hide();
    }else{
        $('.croppreview').addClass('croppreviewcss');
        cropper = new Cropper(image, {
            viewMode: 1,
            preview: ".croppreview",
        });
        $('.applycropbtn').show();
        $(".texture-options").show();
    }
});

function applyCropping() {
    canvas = cropper.getCroppedCanvas();
    canvas.toBlob(function (blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            $('.applycropbtn').hide();
            cropper.destroy();
            cropper = null;
            $('#image').attr('src', `${SITEURL}/media/placeholder.jpg`);
            if(cropper_type==""){
                thumbnail = reader.result;
                isChange = 'thumbnail';
                $('.croppreview').removeClass('croppreviewcss').html(`<figure class="position-relative w-full mb-0"><img src="${thumbnail}" class="img-thumbnail preview"></figure>`);
                $("#thumbnailImage").val(null);
                $(".brick-imgs").show();
                $('.croppreview').css("width","120px");
            }else{
                if(cropper_type=="1"){
                    brick1 = reader.result;
                    isChange = 'brick1';
                }else if(cropper_type=="2"){
                    brick2 = reader.result;
                    isChange = 'brick2';
                }else{
                    brick3 = reader.result;
                    isChange = 'brick3';
                }

                $(".upload-brick-"+cropper_type).removeClass('croppreviewcss').css("height","auto");
                $('.preview-b'+cropper_type).attr('src',`${reader.result}`);
                var brick_images = [];
                $(".brk-prev").each(function(){
                    brick_images.push(this.src)
                });
                loadImages(brick_images, function(e) {
                    getPatternImage(e,brick_images.length);
                });
            }
        };
    });
}

function resetCropping() {
    cropper.destroy();
    cropper = null;
    $('#image').attr('src', `${SITEURL}/media/placeholder.jpg`);
    $('.croppreview').removeClass('croppreviewcss').html(`<figure class="position-relative w-full mb-0"><img src="${SITEURL}/media/placeholder.jpg" class="img-thumbnail preview"></figure>`);
    $('.applycropbtn').hide();
    $(".texture-options").hide();
    thumbnail = null;
}

/**
 * Google Search
 */
var image_no = 1;
let startIndex = 1; // Initial start index for pagination
const imagesPerPage = 10;
let totalImagesToFetch = 30; // Set the total number of images to fetch
let currentPage = 1;
var lastSearchQuery = '';
var design_type_data = [];

function searchImages() {
    if(document.getElementById('searchQuery').value == '') {
        alert("Search Input required");
        return false;
    }
    // seamless pattern tile texture
    const searchQuery = document.getElementById('searchQuery').value + '';
    if(lastSearchQuery != searchQuery){
        currentPage = 1;
        startIndex = 1;
        lastSearchQuery = searchQuery;
    }

    //const searchQuery = document.getElementById('searchQuery').value;
    const apiKey = 'AIzaSyCABfWgi907oLMTOUDNPDfeP-tHYDtXsqM'; // Replace with your actual API key
    const cx = 'a5661d1d5e7dd41f8'; // Replace with your actual custom search engine ID
    const totalPages = Math.ceil(totalImagesToFetch / imagesPerPage);
    // Update the pagination
    document.getElementById('pagination').innerHTML = `Page ${currentPage} of ${totalPages}`;

    const apiUrl = `https://www.googleapis.com/customsearch/v1?q=${searchQuery}&key=${apiKey}&cx=${cx}&searchType=image&start=${startIndex}&num=${imagesPerPage}`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => displayImages(data.items))
        .catch(error => console.error('Error:', error));
}

function nextPage() {
    startIndex += imagesPerPage;
    currentPage += 1;
    searchImages();
}

function previousPage() {
    if (startIndex > 1) {
        startIndex -= imagesPerPage;
        currentPage -= 1;
        searchImages();
    }
}

function displayImages(images) {
    document.getElementById("page_controls").style.display = "block";
    const imageResults = document.getElementById('imageResults');
    imageResults.innerHTML = '';

    let img_html = '<div class="row2">';



    images.forEach(image => {
        /*<p class="card-text">${image.htmlSnippet}</p>*/
        img_html +=`
                <div class="img-card">
                    <div class="card mb-0" style="">
                        <img  src="${image.link}" onclick="setGoogleImage('${image.link}')" class="card-img-top g-img" alt="${image.title}">
                        <div class="card-body">
                            <h5 class="card-title">${image.htmlTitle}</h5>
                            <a href="${image.image.contextLink}" target="_blank" class="">${image.displayLink}</a>
                        </div>
                    </div>
                </div>
                `;


        /*const imgElement = document.createElement('img');
        imgElement.src = image.link;
        imgElement.alt = image.title;
        imgElement.style.width = '120px';
        imgElement.style.margin = '5px';
        //imgElement.crossorigin = 'anonymous';
        imgElement.addEventListener('click', () => {
            document.getElementById('mainLoader').style.display = 'block';
            //crop_image(imgElement.src);
            uploadImageUrl(imgElement.src)
        });
        imageResults.appendChild(imgElement);*/
    });


    img_html += '</div>';

    imageResults.innerHTML = img_html;
}
function clearSearch() {
    startIndex =1;
    currentPage = 1;
    document.getElementById('searchQuery').value = '';
    document.getElementById('imageResults').innerHTML = '';
    document.getElementById("page_controls").style.display = "none";
}

function closeSearch() {
    document.getElementById('search_box_panel').classList.add('d-none');
    //document.getElementById('search_panel').classList.remove('d-none');
    document.getElementById("page_controls").style.display = "none";
}

function setGoogleImage(imageUrl) {
    fetch(SITEURL + "/api/upload-image", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ imageUrl: imageUrl }),
    })
        .then(response => response.json())
        .then(data => {
            if(cropper){
                cropper.destroy();
                cropper = null;
            }
            $('#image').attr('src', `${SITEURL}/media/placeholder.jpg`);
            $("#df2").trigger("click");
            $("#dt-2").trigger("click");
            console.log(data);
            image.src = data.imagePath;
            $('.croppreview').addClass('croppreviewcss');
            cropper = new Cropper(image, {
                viewMode: 1,
                preview: ".croppreview",
            });
            $('.applycropbtn').show();
            $(".texture-options").show();
        })
        .catch(error => console.error('Error:', error));
}