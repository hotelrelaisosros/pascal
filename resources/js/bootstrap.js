import Echo from 'laravel-echo';
import Pusher from 'pusher-js';



window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
wsHost: window.location.hostname,
    wsPort : 6001,
    disableStats:true,
    forceTLS:false,
});

window.Echo.private('messageChannel').listen('.getChatMessage',(e)=>{
    let ChatMessage = e.chat.message;
    let senderId = e.chat.senderId;
    let receiverId= e.chat.receiverId;
    if (senderId == authUserId && receiverId == someReceiverId) {
        $('chat-div').append(`
                <div class="flex justify-start mb-4">
                    <div class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white>
                    ${ChatMessage}
                    </div>
                </div>
            `)
            scrollChat();
        }
    }
)
