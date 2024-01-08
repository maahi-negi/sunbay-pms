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
    view = "kitchen-view";
    allContent = $(".content-container").find(".design-container").next(), (filterContent = []);
var formatter = new Intl.NumberFormat("en-US", { style: "currency", currency: "USD", minimumFractionDigits: 0, maximumFractionDigits: 0 });

function openNav() {
    (openMenu = !openMenu) ? ($("#nav-icon").addClass("open"), $("#sideMenu").addClass("sidemenu-open")) : ($("#nav-icon").removeClass("open"), $("#sideMenu").removeClass("sidemenu-open"));
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
        (n[o].src = e[o]);
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
                    $(this).attr("data-open-view"),
                    $(this).attr("data-open-view2")
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


function downloadCanvasImage() {
    var e = document.createElement("a");
    (e.download = `design-${view}.png`), (e.href = canvas.toDataURL()), e.click();
}

function downloadPdf() {
    var e,
        t,
        n,
        a = canvas.toDataURL("image/jpeg", 1),
        i = new jsPDF(),
        o = $(".design-container.color-active").find(".design"),
        s = [],
        r = -40;

    function d(e) {
        var t = i.getImageProperties(e),
            n = i.internal.pageSize.getWidth();
        return { pdfWidth: n, pdfHeight: (t.height * n) / t.width };
    }!(function() {
        (e = document.createElement("canvas")), (t = e.getContext("2d")), (e.width = 1538), (e.height = 190);
        var n = new Image();
        (n.src = "../media/biorev_header.png"),
        (n.onload = function() {
            t.drawImage(n, 0, 0, 1538, 190);
            var a = e.toDataURL();
            i.addImage(a, "PNG", 10, 10, d(a).pdfWidth - 20, d(a).pdfHeight - 2.5);
        });
    })(),
    i.addImage(a, "JPEG", 10, 40, d(a).pdfWidth - 20, d(a).pdfHeight - 20),
        i.setFontSize(12),
        i.text("Cabinet", 10, d(a).pdfHeight + 30),
        i.text("Backsplash", 50, d(a).pdfHeight + 30),
        i.text("Countertop", 90, d(a).pdfHeight + 30),
        $.each(o, function() {
            var e = $(this).css("background-image");
            (e = e.replace("url(", "").replace(")", "").replace(/\"/gi, "")), s.push(e);
        }),
        loadImages(s, function(e) {
            $.each(e, function() {
                    ((n = document.createElement("canvas")).width = 79), (n.height = 100), n.getContext("2d").drawImage(this, 0, 0, 79, 100), (r += 40), i.addImage(n.toDataURL("image/jpeg"), "JPEG", 10 + r, d(a).pdfHeight + 32, 30, 40);
                }),
                i.save(`design-${view}.pdf`);
        });
}

function is_touch_enabled() {
    return "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
}
openNav(),
    window.innerHeight > window.innerWidth && toastr.info("For better experience switch to landscape mode"),
    (window.onorientationchange = function(e) {
        0 == e.target.screen.orientation.angle ? toastr.info("For better experience switch to landscape mode") : 90 == e.target.screen.orientation.angle && toastr.clear();
    }),

    $("#tabkv").on("click", function() {
        $('.toggle-btn').removeClass("active");
        $(this).addClass("active"), drawStuff(sourcesView1), (view = "kitchen-view");
    }),
    $("#tabtv").on("click", function() {
        $('.toggle-btn').removeClass("active");
        $(this).addClass("active"), drawStuff(sourcesView2), (view = "top-view");
    }),
    /* $(".cb-value").on("click", function() {
        var e = $(this).parent(".toggle-btn");
        $(e).find("input.cb-value").is(":checked") ? ($(e).addClass("active"), drawStuff(sourcesView2), (view = "top-view")) : ($(e).removeClass("active"), drawStuff(sourcesView1), (view = "kitchen-view"));
    }), */
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
        $(".content-container").hide(), $(e).fadeIn(), selectColorOption(e);
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
