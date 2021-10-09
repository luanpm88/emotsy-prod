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
        <div class="container py-3">
            <h5 class="mb-2" style="font-size: 19px;font-weight: bold;">
                {{ $r['title'] }}
            </h5>
            <div class="d-flex align-items-center my-2">
                <div>
                    <p class="mb-0 text-sm" style="font-size: 14px;"><span class="fw-bold">
                        
                            Updated 
                            {{ array_key_exists('pubDate', $r) ? $r['pubDate'] : null }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="d-flex">
                <div>
                    <p class="pr-3">
                        {{ array_key_exists('description', $r) ? $r['description'] : null }}
                        
                        <a href="">Read More »</a>
                    
                    </p>
                </div>
            </div>
        </div>
    </div>
@endforeach




