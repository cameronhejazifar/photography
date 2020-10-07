<div class="w-full h-8 md:h-12 backdrop-blur-10 flex flex-col items-center justify-center z-10">
    <div class="h-full w-full max-w-screen-xl flex flex-row items-center justify-center">

        @if(config('app.social.flickr.enabled'))
            <a target="_blank" href="{{ config('app.social.flickr.url') }}" title="Flickr"
               class="flex justify-center items-center h-full px-2 md:px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="flickr-icon block w-4 md:w-6 h-4 md:h-6"></span>
            </a>
        @endif

        @if(config('app.social.instagram.enabled'))
            <a target="_blank" href="{{ config('app.social.instagram.url') }}" title="Instagram"
               class="flex justify-center items-center h-full px-2 md:px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="instagram-icon block w-4 md:w-6 h-4 md:h-6"></span>
            </a>
        @endif

        @if(config('app.social.web.enabled'))
            <a target="_blank" href="{{ config('app.social.web.url') }}" title="Website"
               class="flex justify-center items-center h-full px-2 md:px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="website-icon block w-4 md:w-6 h-4 md:h-6"></span>
            </a>
        @endif

        @if(config('app.social.redbubble.enabled'))
            <a target="_blank" href="{{ config('app.social.redbubble.url') }}" title="Redbubble"
               class="flex justify-center items-center h-full px-2 md:px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="redbubble-icon block w-4 md:w-6 h-4 md:h-6"></span>
            </a>
        @endif

        @if(config('app.social.fineartamerica.enabled'))
            <a target="_blank" href="{{ config('app.social.fineartamerica.url') }}" title="Fine Art America"
               class="flex justify-center items-center h-full px-2 md:px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="fineartamerica-icon block w-4 md:w-6 h-4 md:h-6"></span>
            </a>
        @endif

    </div>
</div>
