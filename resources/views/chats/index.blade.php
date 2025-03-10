<x-app-layout>

    @php
    $authUser = auth()->user()->id;
    @endphp
    <script>
        var senderId = @json($authUser);
    </script>
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div id="hello-message">

            <!-- The received hello message will be displayed here -->

        </div>

        <div class="container mx-auto shadow-lg rounded-lg">

            <!-- headaer -->

            <div class="px-5 py-5 flex justify-between items-center bg-white border-b-2">

                <div class="font-semibold text-2xl">Laravel ChatApp</div>

                <div

                    class="h-12 w-12 p-2 bg-yellow-500 rounded-full text-white font-semibold flex items-center justify-center">

                    RA

                </div>

            </div>

            <!-- end header -->

            <!-- Chatting -->

            <div class="flex flex-row justify-between bg-white" style="height: 60vh;">

                <!-- chat list -->

                <div class="flex flex-col w-2/5 border-r-2 overflow-y-auto" style="overflow-y: auto;">

                    <!-- search compt -->

                    <div class="border-b-2 py-4 px-2" style="max-height: 300px; ">

                        <input type="text" placeholder="search chatting"

                            class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full" />

                    </div>
                    <!-- end search compt -->

                    <!-- user list -->

                    @foreach ($users as $user)
                    <div class="user_list flex flex-row py-4 px-2 justify-center items-center border-b-2"
                        style="font-size:10px;"
                        data-id="{{ $user->id }}">

                        <div class="w-1/4">

                            <img src="https://source.unsplash.com/_7LbC5J-jw4/600x600"

                                class="object-cover h-12 w-12 rounded-full" alt="" />

                        </div>

                        <div class="w-full">

                            <div class="text-lg font-semibold">{{ $user->name }}</div>

                            <span class="text-gray-500 lastest__message">

                            </span>

                            <span class="text-white bg-sky-300 rounded-full px-1 py-1"
                                style="background-color: blue;color:white;border-radius:20px"

                                id="{{ $user->id }}-status">offline</span>

                        </div>

                    </div>

                    @endforeach

                    <!-- end user list -->

                </div>

                <!-- end chat list -->

                <!-- message -->

                <div class="w-full flex flex-col justify-between chatbox">

                    <div class="px-5 overflow-y-auto chat-overflow">

                        <div class="flex flex-col mt-5 chat-div">

                        </div>

                    </div>

                    <div class="py-5">

                        <form id="message-form" class="flex">

                            <input class="bg-gray-300 w-full  py-1 px-3" type="text" name="message" id="message"

                                placeholder="type your message here..." />

                            <button class="w-1/6 bg-sky-400" id="submit_btn">Send</button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

    <script>
        var receiverId;

        $(document).ready(function() {
            if ($('.chatbox').is(':empty')) {
                $('.chatbox').css('display', 'none').append("<p style='text-align:center'>no messages found</p>");
            }
            $(".user_list").click(function() {
                var getUserId = $(this).attr('data-id');
                receiverId = getUserId;
                $('.user_list').removeClass('active');
                $(this).addClass('active');
                $('.chatbox').show();
                loadOldChats();
                scrollChat();
            })
            $("#submit_btn").on('click', function(e) {
                e.preventDefault();
                const formData = new FormData($("#message-form").val()[0]);
                formData.append('receiver_id', receiverId);
                formData.append('sender_id', senderId);
                $.ajax({
                    url: "{{route('chats.store')}}",
                    data: formData,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    cache: false,
                }).then(function(response) {
                    $("#message").val('');
                    loadOldChats();
                    scrollChat();
                }).fail(function(e) {});
            })

            scrollChat();

            function scrollChat() {
                $('.chat-overflow').animate({
                    scrollTOp: $('.chat-overflow').offset().top + $('.chat-overflow')[0].scrollHeight
                }, 0);
            }

            function loadOldChats() {
                $.ajax({
                    method: 'POST',
                    url: '{{route("chats.show")}}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token]"').attr('content')
                    },
                    data: {
                        sender_id: senderId,
                        receiver_id: receiverId
                    },
                    success: function(response) {
                        $('.chat-div').empty();
                        if (response.success == "true") {
                            var resultProductData =
                                response.data.filter(function(a) {
                                    var hitDates = a.created_at || {};
                                    hitDates = response.data.map(date => {
                                        date.created_at = new Date(date.created_at);
                                        return date.created_at;
                                    })
                                    return hitDates;
                                })

                            for (var i = 0; i < resultProductData.length - 1; i++) {
                                var created = new Date(resultProductData[i].created_at);
                                var next = new Date(resultProductData[i + 1].created_at);
                                if (created < next) {
                                    var temp = resultProductData[i];
                                    resultProductData[i] = resultProductData[i + 1];
                                    resultProductData[i + 1] = temp;
                                }
                            }

                            response.data.forEach(data => {
                                let senderName = data.sender_name.split(" ").map(n => n.charAt(0)).join("").toUpperCase();
                                let receiver = data.receiver_name.split(" ").map(n => n.charAt(0)).join("").toUpperCase();

                                if (data.sender_id = senderId) {
                                    $('.chat-div').append(`
                                <div class="senderChat">
                              <div class="sImg">
                                  <img src="https://via.placeholder.com/640x480.png/fffff?text=${senderName}" alt="" class="senderProfile">
                              </div>
                              <div class="senderMessage">
                                  <p>${data.message}</p>
                              </div>
                          </div>`)
                                }
                                if (data.receiver_id = receiverId) {
                                    $('.chat-div').append(`
                                       <div class="receiverChat">
                                <div class="rImg">
                                    <img src="https://via.placeholder.com/640x480.png/dfffff?text=${receiver}" alt="" class="senderProfile">
                                </div>
                                <div class="receiverMessage" class="receiverProfile">
                                     <p>${data.message}</p>
                                </div>
                            </div>`)
                                }





                            });
                        }
                        scrollChat();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                })
            }
        });
    </script>

</x-app-layout>