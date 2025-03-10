<!-- resources/views/blogs/create.blade.php -->
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{__('Create Talk')}}
        </h2>
    </x-slot>
    <form action="{{ route('blogs.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded m-10" enctype="multipart/form-data" id="createForm">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" placeholder="Enter Title of Blog" name="title" id="title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('title')" />

        </div>

        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-bold mb-2">Image</label>
            <input type="file" name="image" id="image" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">

            <x-input-error :messages="$errors->get('image')" />
        </div>
        <div class="mb-4">
            <!-- <input type="hidden" id="additional_image_input" name="additional_image[]" value=""> -->
            <label for="additional_image" class="block text-gray-700 font-bold mb-2">Additional Images</label>
            <input type="file" name="additional_image[]" id="additional_image" multiple class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <!-- <input type="hidden" id="additional_image_input" name="additional_image[]" value=""> -->
            <div class="imagesDiv"></div>
            <x-input-error :messages=" $errors->get('additional_image')" />

        </div>

        <div class="mb-4">
            <label for="tag_id" class="block text-gray-700 font-bold mb-2">Tag</label>

            <div class="mainDiv">
                @foreach ($tags as $tag)
                <div class="box tag-checkbox" data-tag-id="{{ $tag->id }}">
                    <h5>{{ $tag->title }}</h5>
                </div>
                @endforeach
            </div>


            <!-- Length -->
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                <select name="category_id" id="category_id">

                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->title }}
                    </option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('length')" />
            </div>

            <!-- Abstract -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="summernote" rows="4" class="w-full  px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 summernotes">
            </textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="color:red !important;background-color:fireback !important;">Submit</button>
            </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var count = 0;
            // var imgur = $("#additional_image").val();
            // var imgurFile = $("#additional_image")[0].files;

            // $('#summernote').each(function(i) {
            //     $("summernote").eq(i).summernote({
            //         height: 300,
            //         onImageUplaod: function(files) {
            //             sendFile(files[0], i);
            //         }
            //     })
            // })
            $('#summernote').summernote({
                height: 300,
                callbacks: {
                    onImageUpload: function(files, editor, wlEditor) {
                        sendFile(files[0], this);
                    }
                }
            });

            let imagePlaceholders = [];
            let imageIndex = 0;

            function sendFile(file, editor) {
                var input = document.getElementById("additional_image");

                var dataTransfer = new DataTransfer();
                for (var i = 0; i < input.files.length; i++) {
                    dataTransfer.items.add(input.files[i]);
                }
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;

                var reader = new FileReader();
                reader.onload = function(e) {
                    $(editor).summernote('insertImage', e.target.result, function($image) {
                        const placeholder = `{${imageIndex}}`;
                        $image.after(placeholder);
                        imagePlaceholders.push(placeholder); // Store the placeholder
                        imageIndex++;
                    });
                    // var files = e.target.file;
                    // var fileLength = files.length;

                    // count++;
                    // for (var i = 0; i < fileLength; i++) {
                    //     var f = files[i];
                    //     var fileReader = new FileReader();
                    //     fileReader.onload = (function(e) {

                    $(".imagesDiv").append(`
                        <span class="pip">
                            <img src="${e.target.result}" name="${e.target.name}" /><br>
                            <span class="removeImg">Remove</span>
                        </span>
                    `);
                    $(".removeImg").last().click(function() {
                        $(this).parent(".pip").remove();
                    });
                }
                reader.readAsDataURL(file);
            }
            $('#createForm').on('submit', function(event) {
                let summernoteContent = $('#summernote').summernote('code');

                summernoteContent = summernoteContent.replace(/<img[^>]*>/g, function(match) {
                    return imagePlaceholders.shift() || '';
                });

                $('#summernote').summernote('code', summernoteContent);

            });

            if (window.File && window.FileList && window.FileReader)[
                $("#additional_image").on('dragover', function(e) {

                })
            ]
            // if (window.File && window.FileList && window.FileReader) {
            //     $("#additional_image").on('change', function(e) {
            //         console.log("images  " + imgur);
            //         console.log("imagesFiles" + imgurFile);
            //         var files = e.target.files;
            //         fileLength = files.length;

            //         count++;
            //         for (var i = 0; i < fileLength; i++) {
            //             var f = files[i];
            //             var fileReader = new FileReader();
            //             fileReader.onload = (function(e) {
            //                 var file = e.target;

            //                 $(".imagesDiv").append("<span class=\"pip\">" +
            //                     "<img class=\"imageThumbnail\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            //                     "<br/><span class=\"removeImg\">&times;</span>" +
            //                     "</span>")
            //                 $(".removeImg").click(function() {
            //                     $(this).parent(".pip").remove();
            //                 })
            //                 $()


            //             })
            //             fileReader.readAsDataURL(f);

            //         }
            //     })
            //     // var files = $("#additional_image")[0].files;
            // }

            const tagDivs = document.querySelectorAll('.tag-checkbox');

            tagDivs.forEach(div => {
                div.addEventListener('click', function() {
                    const tagId = this.getAttribute('data-tag-id');
                    const isSelected = this.classList.contains('box-selected');

                    if (isSelected) {
                        // Unselect the tag
                        this.classList.remove('box-selected');
                        this.classList.add('box-unselected');
                        removeTagId(tagId);
                    } else {
                        // Select the tag
                        this.classList.remove('box-unselected');
                        this.classList.add('box-selected');
                        addTagId(tagId);
                    }

                    // Log the currently selected tag IDs
                    console.log('Selected tag IDs:', getSelectedTagIds());
                });
            });

            function addTagId(tagId) {
                const existingInput = document.querySelector(`input[name="tag_id[]"][value="${tagId}"]`);
                if (!existingInput) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tag_id[]';
                    hiddenInput.value = tagId;
                    document.querySelector('.mainDiv').appendChild(hiddenInput);
                }
            }

            function removeTagId(tagId) {
                const existingInput = document.querySelector(`input[name="tag_id[]"][value="${tagId}"]`);
                if (existingInput) {
                    existingInput.remove();
                }
            }

            function getSelectedTagIds() {
                const selectedInputs = document.querySelectorAll('input[name="tag_id[]"]');
                return Array.from(selectedInputs).map(input => input.value);
            }
        });
    </script>

</x-app-layout>