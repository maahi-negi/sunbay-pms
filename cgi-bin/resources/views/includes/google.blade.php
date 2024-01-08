<button id="search_panel" class="search_panel" onclick="callSearch()">
    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>

    </svg>
</button>
<section id="search_box_panel" class="search_box d-none">
<a class="close close-icon" onclick="closeSearch()">X</a>
<h1>Image Search</h1>
<input type="text" id="searchQuery" placeholder="Enter search query">
<button onclick="searchImages()">
    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
        <path d="M 9 2 C 5.1458514 2 2 5.1458514 2 9 C 2 12.854149 5.1458514 16 9 16 C 10.747998 16 12.345009 15.348024 13.574219 14.28125 L 14 14.707031 L 14 16 L 20 22 L 22 20 L 16 14 L 14.707031 14 L 14.28125 13.574219 C 15.348024 12.345009 16 10.747998 16 9 C 16 5.1458514 12.854149 2 9 2 z M 9 4 C 11.773268 4 14 6.2267316 14 9 C 14 11.773268 11.773268 14 9 14 C 6.2267316 14 4 11.773268 4 9 C 4 6.2267316 6.2267316 4 9 4 z"></path>
    </svg>
</button>
<button onclick="clearSearch()">Clear</button>
<div id="imageResults"></div>
<div id="page_controls" style="display: none;">
<div id="pagination"></div>
<button id="previousPageBtn" onclick="previousPage()">Previous Page</button>
<button id="nextPageBtn" onclick="nextPage()">Next Page</button>
</div>
    <!--<div id="img_grid"></div>-->
    <div class="crop_box">
        <a class="close_crop_box" onclick="closeCropBox()">X</a>

        <div class="crop_box_container">

            <div>
                <input type="text" name="" placeholder="Title" id="img_title"><br><br>
                <div class="" style="width:300px;">
                    <img id="sourceImage" style="width: 100%;" src="{{ asset('media/placeholder.jpg') }}" alt="Source Image">
                </div>
            </div>
            <div style="display: flex;flex-direction: column;">
                <button onclick="cropImage()">Crop Image</button>
                <br>
                <img src="{{ asset('media/placeholder.jpg') }}" id="croppedImage" alt="Cropped Image" style="width: 76px;">
                <br>

                <label for="g_design_type"></label>
                <select name="g_design_type" id="g_design_type">
                    @foreach($design_types as $design_type)
                        <option value="{{$design_type->slug}}" data-id="{{$design_type->id}}">{{$design_type->title}}</option>
                    @endforeach
                </select>
                <br>
                <button onclick="googleSaveImage()">Save Image</button>
            </div>
        </div>
    </div>
</section>


<style>
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
    .search_panel {
        position: fixed;
        z-index: 99;
        top: 100px;
        right: 0px;
        border: none;
    }
    .search_panel svg {
        width: 40px;
        height: 40px;
    }
    div#img_grid {
        width: 100%;
        height: 400px;
    }
    section.search_box {
        position: absolute;
        top: 23px;
        right: 5px;
        background: #ffffffc7;
        padding: 15px;
        border: 1px solid #8f8f8f;
        width: 50%;
        max-width: 450px;
        overflow-y: auto;
        height: calc(100% - 46px);
    }
    input#searchQuery {
        width: calc(100% - 110px);
    }
    section.search_box h1 {
        font-size: 24px;
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #ccc;
    }

    .search_box button {
        border: 0;
        background: #3a3b3d;
        padding: 2px 8px;
        box-shadow: 0px 0px 1px;
        color: #cecdcd;
    }

    .search_box button svg {
        fill: #cecdcd;
    }


    img#blah6 {
        width: auto;
        height: 240px;
    }

    a.close_crop_box {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px;
        background: red;
        color: #fff !important;
        width: 24px;
        height: 24px;
        line-height: 18px;
        border-radius: 100%;
        text-align: center;
        box-shadow: 0px 0px 3px;
    }
    .crop_box {
        position: fixed;
        left: calc(50% - 220px);
        top: 140px;
        z-index: 999999;
        background: #ffffffe8;
        width: 649px;
        /*height: 300px;*/
        padding: 0px;
        display: none;
    }

    .crop_box_container {
        display: flex;
        flex-wrap: wrap;
        align-content: center;
        justify-content: space-between;
        align-items: flex-start;
        padding: 18px;
        background: #fff;
    }

    .crop_box:before {
        content: "";
        position: fixed;
        background: #000000de;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: -2;
    }

</style>
<link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
<script src="https://unpkg.com/cropperjs"></script>
<script>
    var image_no = 1;
    let startIndex = 1; // Initial start index for pagination
    const imagesPerPage = 10;
    let totalImagesToFetch = 30; // Set the total number of images to fetch
    let currentPage = 1;
    var cropper;

    var lastSearchQuery = '';

    var design_type_data = [];

    @foreach($design_types as $design_type)
    @php
        $image_view1 = ($design_type->image_view1)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view1):"";
        $image_view2 = ($design_type->image_view2)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view2):"";
        $image_view3 = ($design_type->image_view3)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view3):"";
    @endphp

    design_type_data.push({
        "design_type": "{{ $design_type->slug }}",
        "image_view1": "{{ $image_view1 }}",
        "image_view2": "{{ $image_view2 }}",
        "image_view3": "{{ $image_view3 }}",
    });

    @endforeach

    function searchImages() {
        const searchQuery = document.getElementById('searchQuery').value + ' seamless pattern tile texture';

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

        images.forEach(image => {
            const imgElement = document.createElement('img');
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
            imageResults.appendChild(imgElement);
        });
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
        document.getElementById('search_panel').classList.remove('d-none');
        document.getElementById("page_controls").style.display = "none";
    }

    function callSearch() {

        document.getElementById('search_box_panel').classList.remove('d-none');
        document.getElementById('search_panel').classList.add('d-none');
    }


    function closeCropBox() {
        document.getElementsByClassName('crop_box')[0].style.display = 'none';
        cropper.destroy();
        document.getElementById('croppedImage').src = "{{ asset('media/placeholder.jpg') }}";
        document.getElementById('img_title').value = "";
    }


    function uploadImageUrl(imageUrl) {
        fetch('{{ url("/api/upload-image") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ imageUrl: imageUrl }),
        })
            .then(response => response.json())
            .then(data => {


                setTimeout(function () {
                    document.getElementsByClassName('crop_box')[0].style.display = 'block';
                    document.getElementById('mainLoader').style.display = 'none';
                    // Get a reference to the image element
                    const image = document.getElementById('sourceImage');
                    image.src = data.imagePath;
                    // Initialize Cropper.js
                    cropper = new Cropper(image, {
                        //aspectRatio: free, // Set the aspect ratio as needed
                        //checkCrossOrigin: false,
                        crop: function (event) {
                            // You can use the crop event to update the UI or get crop data
                            /*console.log(event.detail.x);
                            console.log(event.detail.y);
                            console.log(event.detail.width);
                            console.log(event.detail.height);
                            console.log(event.detail.rotate);
                            console.log(event.detail.scaleX);
                            console.log(event.detail.scaleY);*/
                        }
                    });
                    // Function to crop the image and display the result
                    window.cropImage = function () {
                        // Get the cropped data as a data URL
                        const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();

                        // Display the cropped image
                       // const croppedImage = document.getElementById('croppedImage');
                        //croppedImage.src = croppedDataUrl;
                        resizeImage(croppedDataUrl, 100, 100);




                    };
                }, 3000);
                //document.getElementById('result').innerHTML = data.message;
            })
            .catch(error => console.error('Error:', error));
    }

    function googleSaveImage(uploaded_image = '', design_type_val = '') {
        var img_title = document.getElementById('img_title').value;
        let design_type_id = $("#g_design_type").find(':selected').attr('data-id');

        let croppedDataUrl = document.getElementById('croppedImage').getAttribute('src');
        if(uploaded_image){
            croppedDataUrl = uploaded_image;
        }
        if(uploaded_image == '') {

            fetch('{{ url("/api/save-texture") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    imageUrl: croppedDataUrl,
                    title: img_title,
                    design_type: 2,
                    elevation_id: {{ $design_group->id }},
                    design_type_id: design_type_id,
                    status: 1
                }),
            }).then(response => response.json()).then(data => {

            }).catch(error => console.error('Error:', error));

        }



        let design_type = document.getElementById('g_design_type').value;
        let image_view1 = '';
        let image_view2 = '';
        let image_view3 = '';
        if(design_type_val){
            design_type = design_type_val;
        }
        design_type_data.forEach(element => {
            if(element.design_type == design_type){
                image_view1 = element.image_view1;
                image_view2 = element.image_view2;
                image_view3 = element.image_view3;
            }
        });




        var abc = `
        <div class="col-sm-4 col-6" style="">
            <div class="design-container image-icons-wrap mb-1  cateundefined subcateundefined">
                <div data-design-title="Brick" data-value="Test Stone ${image_no}" class="w-100 design back-image fade-image" style="background: url(${croppedDataUrl}); background-repeat:repeat; background-size: contain; background-position: center;"></div>
                <span class="text-white d-flex show-buttons">
                    <svg xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-check-circle mr-1 check-color-option "
                    data-design-group-view1="" data-design-group-view2="" data-design-type="${design_type}"
                    data-design-view1="${image_view1}"
                    data-design-view2="${image_view2}"
                    data-design-view3="${image_view3}" data-rbg-color=""
                    data-type="2"
                    data-texture="${croppedDataUrl}"
                    data-additional-texture="${croppedDataUrl}">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </span>
            </div>
            <p class="text-capitalize p-0 m-0" data-price=""> ${img_title} </p>
        </div>
        `;
        $(`#${design_type}Data .designs-wrapper .row`).append(abc);
        image_no++;
        if(uploaded_image == ''){
            closeCropBox();
        }

    }

    function deleteDesign(id) {
        confirm('Are you sure?')?fetch('{{ url("/api/delete-design") }}', {
            method: 'delete',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                design_id: id
            })
        }).then(response => response.json()).then(data => {
            alert(data);
            $("#texture_design_"+id).remove();
        }).catch(error => console.error('Error:', error)):'';
    }


    function resizeImage(croppedDataUrl, newWidth, newHeight) {
        // Get the cropped data as a data URL
       // const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();

        // Create a new Image element for resizing
        const resizeImage = new Image();

        // Set the source of the resizing image to the cropped data URL
        resizeImage.src = croppedDataUrl;

        // Create a canvas element for resizing
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Set the dimensions of the canvas to the new dimensions
        canvas.width = newWidth;
        canvas.height = newHeight;

        // When the resizing image has loaded, draw it onto the canvas with the new dimensions
        resizeImage.onload = function () {
            context.drawImage(resizeImage, 0, 0, newWidth, newHeight);

            // Get the resized data as a data URL
            const resizedDataUrl = canvas.toDataURL();

            // Display the resized image
            const resizedImage = document.getElementById('croppedImage');
            resizedImage.src = resizedDataUrl;
        };
    };

</script>


