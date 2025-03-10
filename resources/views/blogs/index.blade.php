<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            <a href="{{route ('blogs.create')}}">Create Blogs</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto xm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-">
                <div class="p-6 text-gray-900">
                    <ul class="list-disc pl-4">
                    @foreach ($blogs as $blog )
                    <div>
                        <a href="{{route('blogs.show',$blog)}}">{{$blog->title}} </a>
                      
                        <a href="{{route('blogs.edit',$blog)}}">
        <i class="fas fa-edit"></i> Edit
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form-{{$blog->id}}').submit();">
        <i class="fas fa-trash-alt"></i> Delete
    </a>
    <form method="post" id="delete-form-{{$blog->id}}" style="display:none" action="{{route('blogs.destroy',$blog)}}">
        @csrf
        @method('DELETE')
    </form>
                    </div>    
                        
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>