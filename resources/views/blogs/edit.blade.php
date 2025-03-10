<!-- resources/views/blogs/create.blade.php -->
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{__('Edit Talk')}}
        </h2>
    </x-slot>
    <form action="{{ route('blogs.update',$blog) }}" method="post" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded m-10">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" value="{{$blog->title}}" name="title" id="title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="mb-4">
            <label for="tag_id" class="block text-gray-700 font-bold mb-2">Tag</label>

            <div class="mainDiv">
    <!-- Pre-selected Tags -->
    @foreach ($sel_tags as $tag)
    <div class="box box-selected tag-checkbox" data-tag-id="{{ $tag->id }}">
        <h5>{{ $tag->title }}</h5>
        <input type="hidden" name="tag_id[]" value="{{ $tag->id }}">
    </div>
    @endforeach

    <!-- Non-selected Tags -->
    @foreach ($other_tags as $tag)
    <div class="box box-unselected tag-checkbox" data-tag-id="{{ $tag->id }}">
        <h5>{{ $tag->title }}</h5>
    </div>
    @endforeach
</div>

        <!-- Length -->
        <div class="mb-4">
            <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
            <select name="category_id" id="category_id">
                <!-- Preselected Category with Tick Icon -->
                <option value="{{ $sel_cat[0]->id }}" selected>
                    &#x2713; {{ $sel_cat[0]->title }} <!-- Tick icon -->
                </option>

                <!-- Other Categories -->
                @foreach ($other_cat as $category)
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
            <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{$blog->description}}
            </textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="color:red !important;background-color:fireback !important;">Submit</button>
        </div>
    </form>

    
<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagDivs = document.querySelectorAll('.tag-checkbox');

        tagDivs.forEach(div => {
            div.addEventListener('click', function() {
                const tagId = this.getAttribute('data-tag-id');
                const isSelected = this.classList.contains('box-selected');
                
                if (isSelected) {
                    this.classList.remove('box-selected');
                    this.classList.add('box-unselected');
                    removeTagId(tagId);
                } else {
                    this.classList.remove('box-unselected');
                    this.classList.add('box-selected');
                    addTagId(tagId);
                }
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

        function getSelectedTagIds() {
        const selectedInputs = document.querySelectorAll('input[name="tag_id[]"]');
        return Array.from(selectedInputs).map(input => input.value);
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