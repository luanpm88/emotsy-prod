<div>
    <div class="container py-3">
        <div class="text-center pt-4">
            <h1 class="fw-normal fs-4 mt-2">{{ $title }}</h1>
            <p>Published date: {{ $pubDate }}</p>
        </div>
    </div>
</div>

<div>
    <div class="container py-3">
        <div class="border-bottom text-muted small"><span>TOP STORIES FOR YOU</span></div>
    </div>
</div>

@foreach ($records as $r)
    <div>
        <div class="container py-3 mb-3">
            <h5 class="mb-2" style="font-size: 19px;font-weight: bold;">
                {{ $r['title'] }}
            </h5>
            <div class="d-flex align-items-center my-2">
                <div class=" mr-2">
                    <img builder-element src="{{ url('images/avatar1.svg') }}" width="32px" />
                </div>
                <div>
                    <p class="mb-0 text-sm" style="font-size: 14px;"><span class="fw-bold">
                        Scott Egan</span>, Lived in Japan for two years • 
                            Updated 
                            {{ array_key_exists('pubDate', $r) ? $r['pubDate'] : null }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="d-flex">
                <div>
                    <p class="">
                        {{ array_key_exists('description', $r) ? $r['description'] : null }}
                        
                        <a href="{{ $r['link'] }}">Read More »</a>
                    
                    </p>

                    <div>
                        <div class="d-flex align-items-center small">
                            <a href="" class="d-flex align-items-center mr-4" style="color: #636466">
                                <svg style="width:15px;margin-right:5px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 236 209.1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M155,209.1H84a9,9,0,0,1-9-9V149.7L8.9,149a9.1,9.1,0,0,1-8.1-5.2,9.3,9.3,0,0,1,1.3-9.6l109-131A9,9,0,0,1,118,0h0a8.8,8.8,0,0,1,6.9,3.3l109,132A9,9,0,0,1,227,150H164v50.1A9,9,0,0,1,155,209.1Zm-62-18h53V141a9,9,0,0,1,9-9h52.9L118,23.1,28,131.2l56.1.6a9,9,0,0,1,8.9,9Z" style="fill:#616567"/></g></g></svg>
                                <span>4.0K upvotes</span>
                            </a>
                                
                            <a href="" class="d-flex align-items-center" style="color: #636466">
                                <svg style="width:15px;margin-right:5px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 242 218.2"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M121,217.4A131.2,131.2,0,0,1,62.8,204l8.8-17.9A112.2,112.2,0,0,0,121,197.4c55.7,0,101-39.8,101-88.7S176.7,20,121,20,20,59.8,20,108.7c0,19.8,7.3,38.6,21.1,54.2l-15,13.2C9,156.8,0,133.5,0,108.7,0,48.8,54.3,0,121,0S242,48.8,242,108.7,187.7,217.4,121,217.4Z" style="fill:#646566"/><path d="M13.2,218.2a10.1,10.1,0,0,1-7.9-3.8,10.3,10.3,0,0,1-1-10.9l.7-1.1,19.7-37.5a10,10,0,0,1,17.7,9.3l-10,19.1,32.5-7.9a9.9,9.9,0,0,1,12,7.3,10,10,0,0,1-7.3,12.1L15.5,217.9A9.1,9.1,0,0,1,13.2,218.2Z" style="fill:#646566"/></g></g></svg>
                                <span>88 comments</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ml-auto ms-auto pl-4 ps-4">
                    <img class="my-2" src="{{ url('images/newsThumb.svg') }}" height="100px" />
                </div>                
            </div>
            
        </div>
    </div>
@endforeach




