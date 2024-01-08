feather.replace();
var filterMinPrice,
    filterMaxPrice,
    items,
    openMenu = !0,
    searchApplied = !1,
    filterApplied = !1,
    fromPrice = 0,
    toPrice = 1200,
    itemsShown = [],
    view = "kitchen-view"
plan_title = $("#elevation-title").html();

(canvas = document.getElementById("canvas")), (ctx = canvas.getContext("2d")), (allContent = $(".content-container").find(".design-container").next()), (filterContent = []);
var formatter = new Intl.NumberFormat("en-US", { style: "currency", currency: "USD", minimumFractionDigits: 0, maximumFractionDigits: 0 });
var temp_Canvas = document.getElementById('tempcanvas'), CTX = temp_Canvas.getContext('2d');

function openNav() {
    (openMenu = !openMenu) ? ($("#nav-icon").addClass("open"), $("#sideMenu").addClass("sidemenu-open")) : ($("#nav-icon").removeClass("open"), $("#sideMenu").removeClass("sidemenu-open"));
}

function resizeCanvas() {
    window.innerWidth > 991 ? (canvas.width = $(".main-wrapper").innerWidth() - 140) : (canvas.width = $(".main-wrapper").innerWidth()),
        (canvas.height = $(".main-wrapper").innerHeight()),
        ($(".toggle-btn.active").attr("id") == 'tabtv') ? drawStuff(sourcesView2) : drawStuff(sourcesView1);
        temp_Canvas.width = canvas.width;
        temp_Canvas.height = canvas.height;
}

function drawStuff(e) {
    $("#tempcanvas").show();
    $("#canvas").hide();
    loadImages(e, function(e) {
        $.each(e, function() {
            drawImageScaled(this, ctx);
            // window.innerHeight > window.innerWidth ? drawImageScaled(this, ctx) : drawImageProp(ctx, this, 0, 0, canvas.width, canvas.height), $("#mainLoader").hide();
        });
        setTimeout(function(){
            $("#mainLoader").hide();
            CTX.drawImage(canvas, 0, 0);
        }, 500);
    });
}

function loadImages(e, t) {
    $("#mainLoader").show();
    var n = {},
        a = 0,
        i = 0;
    for (var o in e) i++;
    for (var o in e)
        (n[o] = new Image()),
            (n[o].onload = function() {
                ++a >= i && t(n);
            }),
            (n[o].src = e[o]),
            (n[o].alt = o);
}

function drawImageScaled(e, t) {
    var n = canvas.width / e.width,
        a = canvas.height / e.height,
        i = Math.min(n, a),
        o = (canvas.width - e.width * i) / 2,
        s = (canvas.height - e.height * i) / 2,
        layer_name = e.alt;

    if(layerColors[layer_name]){
        if(layerColors[layer_name].design_type=="1" || layerColors[layer_name].design_type=="2"){
            if(layerColors[layer_name].color){
                var newColor = layerColors[layer_name].color.split(',');
                var color_img = drawImage(e,newColor);
                t.drawImage(color_img, 0, 0);
            }else{
                var pattern_img = new Image;
                pattern_img.onload = function() {
                    var color_img = drawPatternImage(e,pattern_img);
                    t.drawImage(color_img, 0, 0);
                }
                pattern_img.src = layerColors[layer_name].texture;
            }
        }else{
            t.drawImage(e, 0, 0, e.width, e.height, o, s, e.width * i, e.height * i);
        }
    }else{
        t.drawImage(e, 0, 0, e.width, e.height, o, s, e.width * i, e.height * i);
    }
}

function drawImageProp(e, t, n, a, i, o, s, r) {
    2 === arguments.length && ((n = a = 0), (i = e.canvas.width), (o = e.canvas.height)), (s = "number" == typeof s ? s : 0.5) < 0 && (s = 0), (r = "number" == typeof r ? r : 0.5) < 0 && (r = 0), s > 1 && (s = 1), r > 1 && (r = 1);
    var d,
        c,
        l,
        h,
        u = t.width,
        f = t.height,
        m = Math.min(i / u, o / f),
        p = u * m,
        g = f * m,
        w = 1;
    p < i && (w = i / p),
    Math.abs(w - 1) < 1e-14 && g < o && (w = o / g),
    (d = (u - (l = u / ((p *= w) / i))) * s) < 0 && (d = 0),
    (c = (f - (h = f / ((g *= w) / o))) * r) < 0 && (c = 0),
    l > u && (l = u),
    h > f && (h = f),
        e.drawImage(t, d, c, l, h, n, a, i, o);
}

function selectColorOption(e) {
    $(e)
        .find(".check-color-option")
        .on("click", function() {
            changeLayers(
                $(this).attr("data-design-group-view1"),
                $(this).attr("data-design-group-view2"),
                $(this).attr("data-design-type"),
                $(this).attr("data-design-view1"),
                $(this).attr("data-design-view2"),
                $(this).attr("data-design-view3"),
                $(this).attr("data-type"),
                $(this).attr("data-rbg-color"),
                $(this).attr("data-texture"),
                $(this).attr("data-additional-texture")
            ),
                $(e).find(".design-container").find(".check-color-option").removeClass("button-active"),
                $(e).find(".design-container").find("span").removeClass("show-buttons"),
                $(e).find(".design-container").find(".back-image").removeClass("fade-image"),
                $(e).find(".design-container").removeClass("color-active"),
                $(this).addClass("button-active"),
                $(this).parent().addClass("show-buttons"),
                $(this).parent().prev().addClass("fade-image"),
                $(this).parent().parent().addClass("color-active");
        });
}

function showFeatureModal(e, t) {
    $("#featureModal").modal("show"),
        $("#featureModal .modal-body").hide(),
        $("#loader").addClass("show-loader"),
        $.get("/api/get-design-info/" + e, function(e) {
            $(".feature-image-wrapper img").attr("src", `/media/uploads/${t}/${e.thumbnail}`),
                $(".f-material").text(e.material),
                $(".f-manufacturer").text(e.manufacturer),
                $(".f-name").text(e.title),
                $(".f-price").text(formatter.format(e.price)),
                $(".f-id").text(e.product_id),
                $("#loader").removeClass("show-loader"),
                $("#featureModal .modal-body").fadeIn();
        });
}

function showFilteredData() {
    let e = filterData();
    $(allContent).parent().hide(),
        $.each(e, function() {
            $(this).parent().show();
        });
    filterCategory();
}

function filterData() {
    let e,
        t = [],
        n = [];
    var a = allContent;
    let i = document.getElementById("searchInput").value.toUpperCase();
    return "" != i &&
    ($.each(allContent, function() {
        (e = $(this).text()).toUpperCase().indexOf(i) > -1 && t.push(this);
    }),
    0 == (a = _.intersection(allContent, t)).length) ?
        a :
        ($.each(allContent, function() {
            Number(this.getAttribute("data-price")) >= filterMinPrice && Number(this.getAttribute("data-price")) <= filterMaxPrice && n.push(this);
        }),
            (a = _.intersection(a, n)));
}

function sort(e) {
    $("#sortInput").on("change", function() {
        for (var t, n, a, i = !0, o = $(this).val(); i;) {
            for (i = !1, n = $(e).find(".design-container").next(), t = 0; t < n.length - 1; t++)
                if (((a = !1), "asc" == o)) {
                    if (n[t].innerHTML.toLowerCase() > n[t + 1].innerHTML.toLowerCase()) {
                        a = !0;
                        break;
                    }
                } else if ("desc" == o) {
                    if (n[t].innerHTML.toLowerCase() < n[t + 1].innerHTML.toLowerCase()) {
                        a = !0;
                        break;
                    }
                } else if ("low_to_high" == o) {
                    if ((console.log(Number(n[t].getAttribute("data-price"))), Number(n[t].getAttribute("data-price")) > Number(n[t + 1].getAttribute("data-price")))) {
                        a = !0;
                        break;
                    }
                } else if ("high_to_low" == o && Number(n[t].getAttribute("data-price")) < Number(n[t + 1].getAttribute("data-price"))) {
                    a = !0;
                    break;
                }
            a && (n[t].parentNode.parentNode.insertBefore(n[t + 1].parentNode, n[t].parentNode), (i = !0));
        }
    });
}

/* category filter */
function filterCategory(){
    $(".filter-category").each(function() {
        if($(this).is(':visible')){
            var categoryId = $(this).val(),
                design_type = $(this).attr("data-design"),
                type = $(this).attr("data-type");
            if(categoryId){
                $("#"+design_type+"Data").find(".design-container").each(function (){
                    if($(this).is(':visible')){
                        if($(this).hasClass(type+categoryId)){
                            $(this).parent().show();
                        }else{
                            $(this).parent().hide();
                        }
                    }
                });
            }else{
                $("#"+design_type+"Data").find(".design-container").parent().hide();
            }
        }
    });
}

function categoryFilter() {
    $(".filter-category").on("change", function() {
        var data_design = $(this).attr("data-design"),
            categoryId = $(this).val(),
            type = $(this).attr("data-type");

        var design_container = $("#"+data_design+"Data").find(".design-container").next();
        if(categoryId){
            var show_filter = 1;
            if(type=="cate"){
                if(sub_categories[categoryId]){
                    getSubCategory(categoryId,data_design);
                    $(design_container).parent().hide();
                    var show_filter = 0;
                }
            }
            if(show_filter=="1"){
                showFilteredData();
            }
        }else{
            $(design_container).parent().hide();
            if(type=="cate"){
                // $(design_container).parent().show();
                $("#"+data_design+"Data").find(".sub-category").val("").hide();
            }else{
                // showFilteredData();
            }
        }
    });
}

/* get sub category */
function getSubCategory(categoryId,data_design){
    if(sub_categories[categoryId]){
        var html = "<option value=''>Select Sub Category</option>";
        $.each(sub_categories[categoryId], function(indx,val){
            html += "<option value='"+ indx +"'>"+ val +"</option>";
        });
        $("#"+data_design+"Data").find(".filter-subcategory").html(html);
        $("#"+data_design+"Data").find(".sub-category").show();
    }else{
        $("#"+data_design+"Data").find(".sub-category").hide();
    }
}

/* end category filter */

function changeLayers(...e) {
    layerColors[e[2]] = {color: e[6]=="1" ? e[7] :"",texture: e[6]=="2"? e[8]:"",design_type:e[6]};
    "" != e[0] && (sourcesView1.base_image_view1 = e[0]),
    "" != e[1] && (sourcesView2.base_image_view2 = e[1]),

    "" != e[3] && ($("#cb2").is(":checked") && "" != e[5] ? (sourcesView1[e[2]] = e[5]) : (sourcesView1[e[2]] = e[3])),
    "" != e[4] && ($("#cb2").is(":checked") && "" != e[6] ? (sourcesView2[e[2]] = e[6]) : (sourcesView2[e[2]] = e[4]));

    if(e[6]=="3"){
        let lname = e[2]+"_2";
        sourcesView1[lname] = e[3];
        layerColors[lname] = {color: "",texture: "",design_type:e[6]};
    }else {
        if(e[4] != ""){
            let lname = e[2]+"_2";
            sourcesView1[lname] = e[4];
            layerColors[lname] = {color: e[6]=="1" ? e[7] :"",texture: e[6]=="2"? e[9]:"",design_type:e[6]};
        }
        if(e[5] != ""){
            let lname = e[2]+"_3";
            sourcesView1[lname] = e[5];
            layerColors[lname] = {color: e[6]=="1" ? e[7] :"",texture: e[6]=="2"? e[9]:"",design_type:e[6]};
        }
    }
    ($(".toggle-btn.active").attr("id") == 'tabtv') ? drawStuff(sourcesView2) : drawStuff(sourcesView1);
}

function downloadCanvasImage() {
    var e = document.createElement("a");
    (e.download = `${plan_title}.png`), (e.href = canvas.toDataURL()), e.click();
}

function downloadPdf() {
    var e,
        t,
        n,
        a = canvas.toDataURL("image/jpeg", 1),
        i = new jsPDF(),
        o = $(".design-container.color-active").find(".design"),
        s = [],
        r = -40,
        ct=0,
        rw=1,
        mn = 1;

    function d(e) {
        var t = i.getImageProperties(e),
            n = i.internal.pageSize.getWidth();
        return { pdfWidth: n, pdfHeight: (t.height * n) / t.width };
    }!(function() {
        (e = document.createElement("canvas")), (t = e.getContext("2d")), (e.width = 1538), (e.height = 190);
        var n = new Image();
        (n.src = "../images/timbercraft-head.png"),
            (n.onload = function() {
                t.drawImage(n, 0, 0, 1538, 190);
                var a = e.toDataURL();
                i.addImage(a, "PNG", 5, 5, d(a).pdfWidth - 10, d(a).pdfHeight - 2.5);
            });
    })(),

        i.addImage(a, "JPEG", 5, 30, d(a).pdfWidth - 10, d(a).pdfHeight - 10);

        if(o.length>0){
            i.setFontSize(13);
            i.text("Added features", 5, d(a).pdfHeight + 32);
        }
        i.setFontSize(11),
        $.each(o, function(ind) {
            ct = (mn==1)?5: parseInt(ct + 40);
            var cst_ttl = $(this).attr("data-design-title");

            if(rw == "1"){
                i.text(cst_ttl, ct, d(a).pdfHeight + 40);
            }else if(rw == "2"){
                i.text(cst_ttl, ct, d(a).pdfHeight + 92);
            }else{
                i.text(cst_ttl, ct, d(a).pdfHeight + 144);
            }

            var e = $(this).css("background-image");
            (e = e.replace("url(", "").replace(")", "").replace(/\"/gi, "")), s.push(e);
            if(mn == 5){
                rw++;mn = 1;
            }else{
                mn++;
            }
        }),

        i.setFontSize(8);
    mn = 1; rw = 1;
    $.each(o, function(indx) {
        ct = (mn==1)?5: parseInt(ct + 40);
        if(indx <= 4){
            i.text(o[indx].dataset.value, ct, d(a).pdfHeight + 85);
        }else if(indx > 4 && indx < 10){
            i.text(o[indx].dataset.value, ct, d(a).pdfHeight + 138);
        }else{
            i.text(o[indx].dataset.value, ct, d(a).pdfHeight + 188);
        }
        if(mn == 5){ rw++;mn = 1; }else{ mn++; }
    });
    if(s.length > 0){
        loadImages(s, function(e) {
            $.each(e, function(indx) {
                if(indx<=4){
                    ((n = document.createElement("canvas")).width = 79), (n.height = 100), n.getContext("2d").drawImage(this, 0, 0, 79, 100), (r += 40), i.addImage(n.toDataURL("image/jpeg"), "JPEG", 5 + r, d(a).pdfHeight + 42, 30, 40);
                    if(indx == 4){ r = -40; }
                }else if(indx > 4 && indx< 10){
                    ((n = document.createElement("canvas")).width = 79), (n.height = 100), n.getContext("2d").drawImage(this, 0, 0, 79, 100), (r += 40), i.addImage(n.toDataURL("image/jpeg"), "JPEG", 5 + r, d(a).pdfHeight + 95, 30, 40);
                    if(indx == 9){ r = -40; }
                }else{
                    ((n = document.createElement("canvas")).width = 79), (n.height = 100), n.getContext("2d").drawImage(this, 0, 0, 79, 100), (r += 40), i.addImage(n.toDataURL("image/jpeg"), "JPEG", 5 + r, d(a).pdfHeight + 145, 30, 40);
                }
            }),
                i.save(`${plan_title}.pdf`);
            $("#mainLoader").hide();
        });
    }
}

function setDefaultLayer(){
    $(".designs-wrapper").find(".check-color-option.button-active").each(function (){
        let dt = $(this).attr("data-design-type"),
            v1 = $(this).attr("data-design-view1")
            v2 = $(this).attr("data-design-view2"),
            v3 = $(this).attr("data-design-view3"),
            data_type = $(this).attr("data-type"),
            color = $(this).attr("data-rbg-color"),
            texture = $(this).attr("data-texture");
            texture2 = $(this).attr("data-additional-texture");

        sourcesView1[dt] = v1
        layerColors[dt] = {color: data_type=="1" ? color :"",texture: data_type=="2"? texture:"",design_type:data_type};
        if(v2 != ""){
            sourcesView1[dt+"_2"] = v2;
            layerColors[dt+"_2"] = {color: data_type=="1" ? color :"",texture: data_type=="2"? texture2:texture,design_type:data_type};
        }
        if(v3 != ""){
            sourcesView1[dt+"_3"] = v3;
            layerColors[dt+"_3"] = {color: data_type=="1" ? color :"",texture: data_type=="2"? texture2:texture,design_type:data_type};
        }
    });
    console.log(sourcesView1);
    drawStuff(sourcesView1);
    resizeCanvas();
}

// change layer color
function changeColor(data,newcolor) {
    // Convert to unitary value for calculations
    newcolor[0] = newcolor[0] / 255;
    newcolor[1] = newcolor[1] / 255;
    newcolor[2] = newcolor[2] / 255;

    var r, g, b;
    for (var i = 0; i < data.length; i+= 4) {
        // Get unitary value of the pixel
        r = data[i] / 255;
        g = data[i+1] / 255;
        b = data[i+2] / 255;

        // Multiply the values and store them
        data[i] = Math.floor(255 * r * newcolor[0]);
        data[i+1] = Math.floor(255 * r * newcolor[1]);
        data[i+2] = Math.floor(255 * r * newcolor[2]);
    }
}

// draw image with canvas for change color
function drawImage(e,newColor) {
    var tempCanvas = document.createElement('canvas');
    var context = tempCanvas.getContext('2d');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;

    var n = canvas.width / e.width,
        a = canvas.height / e.height,
        i = Math.min(n, a),
        o = (canvas.width - e.width * i) / 2,
        s = (canvas.height - e.height * i) / 2;

    context.drawImage(e, 0, 0, e.width, e.height, o, s, e.width * i, e.height * i);
    var imageData = context.getImageData(0, 0, e.height, e.width);

    changeColor(imageData.data,newColor);
    // Update the canvas with the new data
    context.putImageData(imageData, 0, 0);
    return tempCanvas;
}

function drawPatternImage(img1,patImg,newColor=null){
    var imageWidth = img1.width;
    var imageHeight = img1.height;
    var tempCanvas = document.createElement('canvas');
    var ctx = tempCanvas.getContext('2d');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    var n = tempCanvas.width / imageWidth,
        a = tempCanvas.height / imageHeight,
        i = Math.min(n, a),
        o = (tempCanvas.width - imageWidth * i) / 2,
        s = (tempCanvas.height - imageHeight * i) / 2;

    // create a pattern
    ctx.fillStyle = ctx.createPattern(patImg, "repeat");
    // fill canvas with pattern
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    // use blending mode multiply
    ctx.globalCompositeOperation = "multiply";

    // draw layer on top
    ctx.drawImage(img1, 0, 0, imageWidth, imageHeight, o, s, imageWidth * i, imageHeight * i);
    // change composition mode
    ctx.globalCompositeOperation = "destination-in";

    // draw to cut-out layer
    ctx.drawImage(img1, 0, 0, imageWidth, imageHeight, o, s, imageWidth * i, imageHeight * i);
    if(newColor){
        var imageData = ctx.getImageData(0, 0, imageWidth, imageHeight);
        changeColor(imageData.data,newColor);
        // Update the canvas with the new data
        ctx.putImageData(imageData, 0, 0);
    }
    return tempCanvas;
}

function is_touch_enabled() {
    return "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
}
openNav(),
window.innerHeight > window.innerWidth && toastr.info("For better experience switch to landscape mode"),
    (window.onorientationchange = function(e) {
        0 == e.target.screen.orientation.angle ? toastr.info("For better experience switch to landscape mode") : 90 == e.target.screen.orientation.angle && toastr.clear();
    }),
    window.addEventListener("resize", resizeCanvas, !1),
    resizeCanvas(),
    setDefaultLayer(),
    $("#tabkv").on("click", function() {
        $('.toggle-btn').removeClass("active");
        $(this).addClass("active"), drawStuff(sourcesView1), (view = "kitchen-view");
    }),
    $("#tabtv").on("click", function() {
        $('.toggle-btn').removeClass("active");
        $(this).addClass("active"), drawStuff(sourcesView2), (view = "top-view");
    }),
    $(".image-icons-wrap").on("mouseenter", function() {
        $(this).find("span").addClass("show-buttons"), $(this).find(".back-image").addClass("fade-image");
    }),
    $(".image-icons-wrap")
        .not(".image-icons-wrap span")
        .on("mouseleave", function() {
            $(this).hasClass("design-active") || $(this).hasClass("color-active") || ($(this).find("span").removeClass("show-buttons"), $(this).find(".back-image").removeClass("fade-image"));
        }),
    $(".design-icons-wrap")
        .find(".check-design-option")
        .on("click", function() {
            $(".design-icons-wrap").find(".check-design-option").removeClass("button-active"),
                $(".design-icons-wrap").find("span").removeClass("show-buttons"),
                $(".design-icons-wrap").find(".back-image").removeClass("fade-image"),
                $(".design-icons-wrap").removeClass("design-active"),
                $(this).addClass("button-active"),
                $(this).parent().addClass("show-buttons"),
                $(this).parent().prev().addClass("fade-image"),
                $(this).parent().parent().addClass("design-active"),
                $(".extended").addClass("open"),
                $(".extended").addClass("custom-border border-left");
            var e = $(this).attr("data-target");
            $(".content-container").hide(), $(e).fadeIn(), selectColorOption(e), filterCategory();
        }),
    $(".extended .heading-wrapper #closeMenu").on("click", function() {
        $(".extended").removeClass("open"),
            $(".content-container").hide(),
            $(".design-icons-wrap").find(".check-design-option").removeClass("button-active"),
            $(".design-icons-wrap").find("span").removeClass("show-buttons"),
            $(".design-icons-wrap").find(".back-image").removeClass("fade-image"),
            $(".design-icons-wrap").removeClass("design-active");
    }),
    $("#searchIcon").on("click", function() {
        $(".content-actions-icons-wrap svg").not(this).removeClass("button-active"),
            $("#filtersWrap").removeClass("open"),
            $("#sortWrap").removeClass("open"),
            $(".design-options-wrapper .sidemenu.extended .content-container").removeClass("add-sort-height"),
            $(".design-options-wrapper .sidemenu.extended .content-container").removeClass("add-filter-height"),
        "" == $("#searchInput").val() && ($(this).toggleClass("button-active"), $("#searchInput").toggleClass("show-input"));
    }),
    $("#filterIcon").on("click", function() {
        "" == $("#searchInput").val() ?
            ($(".content-actions-icons-wrap svg").not(this).removeClass("button-active"), $("#searchInput").removeClass("show-input")) :
            $(".content-actions-icons-wrap svg").not(this).not(".content-actions-icons-wrap svg:first-child").removeClass("button-active"),
            $("#sortWrap").removeClass("open"),
            $(".design-options-wrapper .sidemenu.extended .content-container").removeClass("add-sort-height"),
            $(".design-options-wrapper .sidemenu.extended .content-container").toggleClass("add-filter-height"),
            $(this).toggleClass("button-active"),
            $("#filtersWrap").toggleClass("open");
    }),
    $("#sortIcon").on("click", function() {
        "" == $("#searchInput").val() ?
            ($(".content-actions-icons-wrap svg").not(this).removeClass("button-active"), $("#searchInput").removeClass("show-input")) :
            $(".content-actions-icons-wrap svg").not(this).not(".content-actions-icons-wrap svg:first-child").removeClass("button-active"),
            $("#filtersWrap").removeClass("open"),
            $(".design-options-wrapper .sidemenu.extended .content-container").removeClass("add-filter-height"),
            $(".design-options-wrapper .sidemenu.extended .content-container").toggleClass("add-sort-height"),
            $(this).toggleClass("button-active"),
            $("#sortWrap").toggleClass("open");
    }),
    $(".tgl").on("click", function() {
        const e = $(this).parents(".designs-wrapper").find(".check-color-option.button-active"),
            t = e.attr("data-design-type"),
            n = e.attr("data-design-view1"),
            a = e.attr("data-design-view2"),
            i = e.attr("data-open-view"),
            o = e.attr("data-open-view2");
        $(this).is(":checked") ? ($(this).prev().text("Close Cabinets"), "" != i && (sourcesView1[t] = i), "" != o && (sourcesView2[t] = o)) : ($(this).prev().text("Open Cabinets"), (sourcesView1[t] = n), (sourcesView2[t] = a)),
            ($(".toggle-btn.active").attr("id") == 'tabtv') ? drawStuff(sourcesView2) : drawStuff(sourcesView1);
    }),
    $(document).on("click", ".dropdown-menu", function(e) {
        e.stopPropagation();
    }),
    $(".filters-wrapper .dropdown").on("show.bs.dropdown", function() {
        $(this).find(".select-filter-input").addClass("filter-button-active");
    }),
    $(".filters-wrapper .dropdown").on("hide.bs.dropdown", function() {
        $(this).find(".select-filter-input").removeClass("filter-button-active");
    }),
    $(".js-example-basic-single").select2({ placeholder: "Select Design" }),
    $(".price-filter").ionRangeSlider({
        type: "double",
        min: minPrice,
        max: maxPrice,
        from: minPrice,
        to: maxPrice,
        grid: !1,
        skin: "round",
        hide_min_max: !0,
        hide_from_to: !0,
        onStart: function(e) {
            $(".select-fil-d-main .dropdown-menu .price .start-value").html(formatter.format(e.from)),
                $(".select-fil-d-main .dropdown-menu .price .end-value").html(formatter.format(e.to)),
                $(".price-badge").html(`${formatter.format(e.from)} - ${formatter.format(e.to)}`),
                (filterMinPrice = e.from),
                (filterMaxPrice = e.to),
                showFilteredData();
        },
        onChange: function(e) {
            (fromPrice = e.from),
                (toPrice = e.to),
                $(".select-fil-d-main .dropdown-menu .price .start-value").html(formatter.format(e.from)),
                $(".select-fil-d-main .dropdown-menu .price .end-value").html(formatter.format(e.to)),
                $(".price-badge").html(`${formatter.format(e.from)} - ${formatter.format(e.to)}`),
                (filterMinPrice = e.from),
                (filterMaxPrice = e.to),
                showFilteredData();
        },
    }),
    $(".rating-filter").ionRangeSlider({
        type: "double",
        min: 0,
        max: 5,
        from: 0,
        to: 5,
        grid: !1,
        skin: "round",
        hide_min_max: !0,
        hide_from_to: !0,
        onStart: function(e) {
            $(".select-fil-d-main .dropdown-menu .rating .start-value").html(e.from), $(".select-fil-d-main .dropdown-menu .rating .end-value").html(e.to), $(".rating-badge").html(`${e.from} - ${e.to}`);
        },
        onChange: function(e) {
            $(".select-fil-d-main .dropdown-menu .rating .start-value").html(e.from), $(".select-fil-d-main .dropdown-menu .rating .end-value").html(e.to), $(".rating-badge").html(`${e.from} - ${e.to}`);
        },
    }),
    $.each($(".content-container"), function() {
        sort(`#${$(this).attr("id")}`);
        categoryFilter();
    }),
    is_touch_enabled() ?
        $(".download-action-wrap").on("click", function() {
            $(".download-action-buttons").toggleClass("show-action-buttons");
        }) :
        ($(".download-action-wrap").on("mouseover", function() {
            $(".download-action-buttons").addClass("show-action-buttons");
        }),
            $(".download-action-container").on("mouseleave", function() {
                $(".download-action-buttons").removeClass("show-action-buttons");
            }));