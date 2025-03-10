<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Blog
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto xm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden ">
                <div class="p-6 text-gray-900">
                    <ul class="list-disc pl-4">
                        {{$blog->title}}

                    </ul>
                </div>
                <div class="p-5 m-6">
                    @if($blog->additional_images)
                    @foreach ($blog->additional_images as $images)
                    <!-- <img src="url" alt=""> -->
                    @endforeach
                    @endif

                </div>
                <div class="p-6 text-gray-900">
                    <ul class="list-disc pl-4">
                        {{$blog->description}}

                    </ul>
                </div>
            </div>
        </div>

        <h2 class="font-semibold text-xl" style="padding:10px 0px;text-align:center">
            Comments
        </h2>
        <div class="" style="max-width:1000px; margin:20px auto">

            <h2 class="font-semibold text-xl" style="padding:10px 0px;text-align:center">
                Approved Comments
            </h2>
            @foreach ($comments as $comment )
            @if ($comment->status)

            <div class="p-10 text-gray-900 bg-white overflow-hidden " style="margin:20px;display: flex; flex-direction:column;justify-content:center;align-items:center;background-color:antiquewhite">

                <div class="">
                    {{$comment->title }}

                </div>
                <div class="">
                    {{$comment->message}}
                </div>
                <div style="display:flex; justify-content:space-around;padding:5px">
                    <div class="mx-10">Written by <strong>{{$comment->name}}</strong></div>
                    <form method="POST" action="{{ route('blogs.updateStatus',  $comment->id) }}" class="status-form">
                        @csrf
                        @method('POST')
                        <button type="submit" style="color: lightgreen; background:none; border:none;">
                            &#x2713; Disable
                        </button>
                    </form>

                </div>
            </div>
            @endif
            @endforeach

            <h2 class="font-semibold text-xl" style="padding:10px 0px;text-align:center">
                Not Approved
            </h2>
            @foreach ($comments as $comment )
            @if (!$comment->status)

            <div class="p-10 text-gray-900  overflow-hidden " style="margin:20px;display: flex; flex-direction:column;justify-content:center;align-items:center;background-color:white">

                <div class="">
                    {{$comment->title }}
                </div>
                <div class="">
                    {{$comment->message}}
                </div>
                <div style="display:flex; justify-content:space-around;padding:5px">
                    <div class="mx-10">Written by <strong>{{$comment->name}}</strong></div>
                    <form method="POST" action="{{ route('blogs.updateStatus', $comment->id) }}" class="status-form">
                        @csrf
                        @method('POST')
                        <button style="color: red;">&#x2713; Enable </butto>
                    </form>

                </div>
            </div>
            @endif

            @endforeach

        </div>
</x-app-layout>