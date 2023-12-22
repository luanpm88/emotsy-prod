@extends('emotsy.layouts.frontend', [
    'menu' => 'dashboard',
])

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('content')
    @if (config('custom.japan') && !empty(trans('messages.dashboard.notice')))
        <h1>{!! trans('messages.dashboard.notice') !!}</h1>
    @endif
    
    <div class="wellcome d-flex d-flex align-items-center">
        <div class="wellcome-bee">
            <svg width="85" height="78" viewBox="0 0 85 78" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M72.8218 73.9245C69.2051 77.972 42.2513 77.8992 27.7036 76.9104C13.1559 75.9216 -2.29494 75.2402 1.32177 71.1927C4.93848 67.1453 26.005 61.6208 40.8052 62.2541C61.2368 63.1278 76.4427 69.8771 72.8218 73.9245Z" fill="#C9F6F9"/>
                <path d="M51.34 74.2961C51.34 74.2961 41.2153 72.1633 34.6405 72.1575C28.0202 72.1517 17.8236 74.2961 17.8236 74.2961C15.5912 74.2961 13.7832 72.4882 13.7832 70.2557C13.7832 68.0233 15.5912 66.2153 17.8236 66.2153H51.34C53.5725 66.2153 55.3804 68.0233 55.3804 70.2557C55.3804 72.4845 53.5688 74.2961 51.34 74.2961Z" fill="#222751"/>
                <path d="M59.5449 57.1332C56.6929 73.9764 35.2948 74.3424 23.7411 70.6096C12.1875 66.8769 -0.0816293 64.4387 2.77035 47.5955C3.65505 42.3706 5.44219 33.9348 11.5325 28.8626C26.413 22.7004 25.9658 8.06265 34.0725 9.61827C50.2966 12.7289 62.4001 40.2901 59.5449 57.1332Z" fill="#222751"/>
                <path d="M37.9953 69.1843C29.2058 73.505 20.9111 62.7719 18.2308 55.6308C15.5504 48.4896 12.0061 41.4287 20.7956 37.108C29.5851 32.7873 46.6284 33.2509 50.0603 39.9869C54.7991 49.285 46.7861 64.8652 37.9953 69.1843Z" fill="white"/>
                <path d="M17.3319 16.4193C20.4648 16.4193 23.0045 13.7977 23.0045 10.5637C23.0045 7.32968 20.4648 4.70801 17.3319 4.70801C14.1989 4.70801 11.6592 7.32968 11.6592 10.5637C11.6592 13.7977 14.1989 16.4193 17.3319 16.4193Z" fill="#222751"/>
                <path d="M40.3892 14.2234C43.7243 14.2234 46.4279 11.6837 46.4279 8.55073C46.4279 5.4178 43.7243 2.87805 40.3892 2.87805C37.0542 2.87805 34.3506 5.4178 34.3506 8.55073C34.3506 11.6837 37.0542 14.2234 40.3892 14.2234Z" fill="#222751"/>
                <path d="M54.1133 16.6913C54.1133 23.5761 39.2655 30.0022 29.9477 30.0022C20.6298 30.0022 10.9277 30.5933 10.9277 23.7085C10.9277 16.8237 20.6262 5.07397 29.9477 5.07397C39.2692 5.07397 54.1133 9.80651 54.1133 16.6913Z" fill="white"/>
                <mask id="mask0_682_817" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="10" y="5" width="45" height="28">
                <path d="M54.1133 17.6306C54.1133 25.072 39.2655 32.0176 29.9477 32.0176C20.6298 32.0176 10.9277 32.6565 10.9277 25.2151C10.9277 17.7737 20.6262 5.07397 29.9477 5.07397C39.2692 5.07397 54.1133 10.1891 54.1133 17.6306Z" fill="white"/>
                </mask>
                <g mask="url(#mask0_682_817)">
                <path d="M29.9445 5.40681C34.5449 5.40681 40.5286 6.67146 45.3596 8.86129C47.7714 9.95288 49.8794 11.2708 51.3763 12.7551C52.8731 14.2394 53.7442 15.8767 53.7442 17.6306H54.4761C54.4761 15.6637 53.4916 13.8733 51.9143 12.3058C50.3369 10.7416 48.1483 9.38046 45.6853 8.26557C40.7592 6.03581 34.662 4.74121 29.9445 4.74121V5.40681ZM11.2942 25.2151C11.2942 21.6009 13.6658 16.6355 17.2304 12.562C20.7987 8.4819 25.4576 5.40681 29.9482 5.40681V4.74121C25.1172 4.74121 20.268 8.01597 16.6558 12.1494C13.0399 16.2861 10.5622 21.3946 10.5622 25.2185H11.2942V25.2151ZM29.9445 31.6848C25.2527 31.6848 20.5608 31.8412 17.0254 31.0824C15.2651 30.7064 13.8378 30.1073 12.8496 29.1855C11.8725 28.2703 11.2906 27.0056 11.2906 25.2151H10.5586C10.5586 27.1454 11.1917 28.5897 12.3226 29.648C13.4462 30.6964 15.0235 31.3354 16.8534 31.7281C20.4986 32.5101 25.3149 32.3471 29.9409 32.3471V31.6848H29.9445ZM53.7478 17.6306C53.7478 19.3878 52.8695 21.145 51.3653 22.8057C49.8611 24.4663 47.7494 26.0072 45.3376 27.3284C40.503 29.9709 34.5266 31.6848 29.9482 31.6848V32.3504C34.6913 32.3504 40.7958 30.5899 45.7146 27.9008C48.1776 26.553 50.3588 24.9655 51.9326 23.2316C53.5026 21.4977 54.4798 19.5975 54.4798 17.6339H53.7478V17.6306Z" fill="black"/>
                </g>
                <path d="M53.1766 17.0351C53.1766 23.7403 39.2803 30.0025 30.5591 30.0025C21.8378 30.0025 12.7578 30.5793 12.7578 23.8741C12.7578 17.169 21.8378 5.7218 30.5591 5.7218C39.2803 5.7218 53.1766 10.33 53.1766 17.0351Z" fill="white"/>
                <path d="M26.9649 5.63404C26.6428 5.75847 26.3976 5.82435 26.211 5.84265C26.0243 5.86095 25.9182 5.83533 25.8633 5.79873C25.8157 5.76945 25.7791 5.71821 25.7681 5.62672C25.7535 5.52791 25.7681 5.39615 25.812 5.22414C25.9035 4.88744 26.1048 4.46291 26.361 4.02739C26.6135 3.59553 26.9136 3.16002 27.1845 2.7977L26.8917 2.57811C26.6135 2.94775 26.3061 3.39425 26.0463 3.8444C25.7864 4.2909 25.5632 4.74471 25.4607 5.12899C25.4095 5.3193 25.3838 5.50961 25.4095 5.67796C25.4351 5.84997 25.5156 6.00734 25.6766 6.10615C25.8303 6.20131 26.028 6.22693 26.2549 6.20131C26.4854 6.17569 26.7672 6.09883 27.1039 5.97074L26.9649 5.63404ZM34.1015 5.97806C34.193 6.011 34.2552 6.03662 34.2918 6.05126C34.3137 6.05858 34.3174 6.06224 34.3174 6.06224C34.3174 6.06224 34.3137 6.05858 34.3101 6.05492C34.3064 6.05126 34.2955 6.04394 34.2808 6.03296C34.2772 6.02564 34.2296 5.98172 34.2332 5.90486C34.2369 5.80239 34.3137 5.75847 34.343 5.74383C34.3723 5.73285 34.3943 5.72919 34.3979 5.72919C34.4052 5.72919 34.4126 5.72919 34.4162 5.72919C34.4199 5.72919 34.4199 5.72919 34.4162 5.72919C34.4089 5.72919 34.3943 5.72553 34.3686 5.72187C34.3247 5.71456 34.2588 5.69992 34.171 5.68162C33.827 5.60842 33.1938 5.46935 32.4107 5.35224C30.8516 5.12167 28.6594 4.97894 26.9649 5.6377L27.0966 5.97806C28.696 5.3559 30.804 5.48765 32.3558 5.71456C33.128 5.82801 33.7501 5.96342 34.0942 6.03662C34.1783 6.05492 34.2479 6.06955 34.2991 6.08053C34.3247 6.08419 34.3467 6.08785 34.365 6.09151C34.3723 6.09151 34.3869 6.09517 34.3979 6.09517C34.4052 6.09517 34.4126 6.09517 34.4235 6.09517C34.4309 6.09517 34.4565 6.09151 34.4858 6.08053C34.515 6.06955 34.5919 6.02198 34.5955 5.9195C34.5992 5.84265 34.5516 5.79507 34.5443 5.78775C34.5223 5.76213 34.4967 5.74749 34.4894 5.74749C34.4711 5.73651 34.4492 5.72919 34.4272 5.71821C34.3833 5.69992 34.3137 5.6743 34.2223 5.6377L34.1015 5.97806ZM33.0658 3.99811C33.1133 3.60651 33.1426 3.33935 33.1646 3.16368C33.1755 3.07584 33.1865 3.01363 33.1902 2.97703C33.1938 2.95873 33.1975 2.94775 33.1975 2.94409C33.1975 2.94409 33.1975 2.94775 33.1939 2.95507C33.1939 2.95507 33.1792 2.99167 33.1426 3.02095C33.1207 3.03925 33.0804 3.05754 33.0292 3.05754C32.9779 3.05389 32.9413 3.03193 32.9194 3.01363C32.8864 2.98435 32.8718 2.94775 32.8718 2.94775C32.8681 2.93677 32.8681 2.92945 32.8681 2.94409C32.8718 2.96605 32.8754 3.00631 32.8828 3.06486C32.8937 3.17832 32.9084 3.34667 32.9303 3.5443C32.9779 3.9359 33.0584 4.45559 33.2268 4.91672C33.3915 5.37053 33.655 5.81337 34.1015 5.98172L34.2296 5.6377C33.9441 5.53157 33.7282 5.22048 33.5708 4.79229C33.4171 4.37141 33.3402 3.88466 33.2963 3.50038C33.2744 3.30641 33.2597 3.14538 33.2487 3.02827C33.2414 2.97337 33.2378 2.92213 33.2304 2.88553C33.2268 2.8709 33.2231 2.84162 33.2121 2.816C33.2085 2.80868 33.1938 2.76842 33.1573 2.73548C33.1353 2.71718 33.095 2.69157 33.0438 2.69157C32.9889 2.68791 32.9486 2.70986 32.923 2.72816C32.8828 2.75744 32.8645 2.7977 32.8608 2.80502C32.8535 2.81966 32.8498 2.8343 32.8462 2.84528C32.8388 2.86724 32.8352 2.88919 32.8315 2.91115C32.8242 2.95873 32.8132 3.02827 32.8023 3.1161C32.7803 3.29543 32.7473 3.56626 32.7034 3.95419L33.0658 3.99811ZM31.4225 2.68059C31.331 3.78584 31.342 4.62394 31.4188 5.2095C31.4554 5.50229 31.514 5.74017 31.5835 5.9195C31.6531 6.09151 31.7482 6.24157 31.891 6.31476C32.0593 6.39894 32.224 6.34038 32.3448 6.23791C32.4619 6.13909 32.5607 5.97806 32.6485 5.78409C32.8242 5.39249 32.9779 4.78863 33.0694 4.00177L32.7071 3.95785C32.6156 4.72641 32.4692 5.29002 32.3155 5.63404C32.2386 5.80971 32.1618 5.91218 32.1069 5.95976C32.0813 5.98172 32.063 5.98904 32.0593 5.98904C32.0593 5.98904 32.0593 5.98904 32.0556 5.98904C32.0374 5.98172 31.9861 5.93414 31.9276 5.78775C31.8727 5.64868 31.8214 5.44373 31.7848 5.16559C31.7116 4.61296 31.7006 3.80414 31.7921 2.71352L31.4225 2.68059ZM30.3209 2.73182C30.4087 2.28899 30.5661 1.79492 30.7454 1.3777C30.8333 1.16909 30.9284 0.982442 31.0199 0.82873C31.1151 0.671359 31.1993 0.561566 31.2688 0.503009C31.3457 0.433473 31.3566 0.466411 31.3274 0.455432C31.3164 0.451772 31.3603 0.470071 31.4042 0.634761C31.4884 0.949504 31.514 1.58631 31.4225 2.68425L31.7885 2.71352C31.88 1.61559 31.8617 0.923885 31.7592 0.543267C31.7116 0.360277 31.6311 0.188267 31.4701 0.118731C31.2944 0.0418748 31.1297 0.137029 31.0236 0.228524C30.9101 0.327339 30.804 0.47373 30.7015 0.642081C30.599 0.814091 30.4966 1.01538 30.4051 1.23863C30.2184 1.6778 30.0537 2.19383 29.9586 2.66595L30.3209 2.73182ZM26.8587 2.7611C27.2284 3.68337 27.5944 4.28724 27.9567 4.6276C28.1397 4.79961 28.3263 4.91306 28.5166 4.96064C28.7106 5.01187 28.8972 4.99358 29.0693 4.92038C29.4023 4.78131 29.6695 4.44827 29.8708 4.06399C30.0757 3.67239 30.2294 3.20028 30.3209 2.72816L29.9622 2.65497C29.8744 3.10146 29.728 3.54064 29.545 3.89198C29.3584 4.25064 29.1425 4.49218 28.9265 4.58002C28.8241 4.62394 28.7179 4.63126 28.6081 4.60564C28.4947 4.57636 28.3593 4.50316 28.2092 4.36043C27.9054 4.07131 27.5651 3.52966 27.2028 2.62569L26.8587 2.7611ZM27.0307 2.69523L27.1991 2.62569L27.0783 2.32193L26.8844 2.58177L27.0307 2.69523Z" fill="#001C35"/>
                <path d="M42.168 14.1906C43.0902 15.9985 42.4351 18.1761 40.7004 19.0618C38.9693 19.9438 36.8173 19.1972 35.8951 17.3892C34.9728 15.5813 34.164 12.7193 35.8951 11.8373C37.6262 10.9553 41.2457 12.3863 42.168 14.1906Z" fill="#222751"/>
                <path d="M22.954 15.8411C21.8524 18.0004 22.6356 20.6098 24.707 21.6675C26.7785 22.7252 29.3513 21.8286 30.4529 19.6693C31.5545 17.51 32.5243 14.0844 30.4529 13.0304C28.3815 11.9764 24.0556 13.6782 22.954 15.8411Z" fill="#222751"/>
                <path d="M37.6458 15.2895C38.0776 15.5017 38.5388 15.3151 38.4436 14.9455C38.4436 14.9418 38.4436 14.9418 38.44 14.9381C38.3741 14.682 38.224 14.4221 38.0044 14.1952C37.7849 13.9646 37.5104 13.7743 37.2029 13.6426C36.8955 13.5072 36.5735 13.4376 36.266 13.4376C35.9586 13.4376 35.6841 13.5072 35.4682 13.6426C35.2523 13.778 35.1022 13.9683 35.04 14.1989C34.9741 14.4294 34.9997 14.6856 35.1059 14.9418C35.1059 14.9455 35.1095 14.9455 35.1095 14.9491C35.2669 15.3188 35.8525 15.5054 36.1416 15.2931L36.4747 15.0479C36.6357 14.9272 36.9065 14.9272 37.1517 15.0479L37.6458 15.2895Z" fill="white"/>
                <path d="M26.4517 16.1948C26.7628 16.1472 27.03 15.9167 26.9531 15.7447C26.9165 15.6641 26.8653 15.5946 26.7994 15.5287C26.6347 15.3714 26.3822 15.2689 26.0748 15.2323C25.7673 15.1957 25.416 15.225 25.0683 15.3201C24.7206 15.4153 24.3913 15.569 24.1204 15.7666C23.8496 15.9606 23.652 16.1875 23.5495 16.4181C23.447 16.6486 23.447 16.8682 23.5495 17.0549C23.652 17.2415 23.8496 17.3842 24.1204 17.4648C24.2266 17.4977 24.34 17.516 24.4645 17.5306C24.7536 17.5563 25.0939 17.3623 25.1232 17.1573L25.1964 16.6742C25.222 16.5096 25.449 16.3449 25.7051 16.3083L26.4517 16.1948Z" fill="white"/>
                <path d="M37.0876 20.4708C37.1315 21.0857 36.0372 23.0949 34.5257 23.201C33.0142 23.3072 31.6528 21.4663 31.6125 20.8514C31.5686 20.2366 32.7617 19.6547 34.2732 19.5485C35.7847 19.4461 37.0436 19.8596 37.0876 20.4708Z" fill="#001C35"/>
                <path d="M34.6505 24.6832C34.903 24.621 35.3019 24.5002 35.7008 24.3355C36.0924 24.1745 36.5024 23.9659 36.7476 23.7207L36.4877 23.4608C36.2974 23.6512 35.9424 23.8415 35.5581 23.9988C35.1812 24.1525 34.7932 24.2697 34.559 24.3282L34.6505 24.6832ZM34.4199 21.576V24.5039H34.7859V21.576H34.4199ZM36.6158 22.8314L36.7439 22.9595L36.528 22.6667L36.6158 22.8314ZM34.6029 23.7463H34.4199V23.9805L34.6468 23.922L34.6029 23.7463Z" fill="#001C35"/>
                <path d="M63.8897 28.2197C60.128 30.2695 51.8459 28.4823 51.2754 24.2978C50.0077 27.2179 58.3899 39.1814 57.9944 44.9347C58.4252 38.6684 62.0756 40.6219 69.2413 38.6701C74.4332 37.2559 78.7209 34.5682 81.4257 30.5369C84.1342 26.5062 84.3889 21.5802 82.4126 19.5208C80.4363 17.4613 76.9347 15.3865 74.4332 18.716C70.4625 24.0013 68.5921 25.6603 63.8897 28.2197Z" fill="#222751"/>
                <path d="M34.8989 41.661C35.8084 41.661 36.5458 40.9237 36.5458 40.0141C36.5458 39.1045 35.8084 38.3672 34.8989 38.3672C33.9893 38.3672 33.252 39.1045 33.252 40.0141C33.252 40.9237 33.9893 41.661 34.8989 41.661Z" fill="white"/>
                <path d="M15.3182 61.9786C19.2597 61.9786 22.4548 58.7835 22.4548 54.842C22.4548 50.9006 19.2597 47.7054 15.3182 47.7054C11.3768 47.7054 8.18164 50.9006 8.18164 54.842C8.18164 58.7835 11.3768 61.9786 15.3182 61.9786Z" fill="#222751"/>
                <path d="M38.6391 21.6887C38.6391 21.6887 37.787 23.5933 36.0473 24.2411C34.4896 24.8211 31.6187 25.5086 29.6572 24.2411" stroke="#001C35" stroke-miterlimit="10"/>
                <path d="M42.439 55.2527C42.1602 55.2022 41.8739 55.2102 41.5983 55.2761C41.3226 55.342 41.0637 55.4645 40.8379 55.6357C39.8072 56.4225 38.605 56.9543 37.3295 57.1876C36.6548 57.3655 35.9504 57.4003 35.2615 57.2895C34.5726 57.1788 33.9146 56.925 33.3297 56.5445C32.5998 55.9738 32.0366 55.2175 31.6992 54.3546C31.6614 54.2609 31.6446 54.16 31.6501 54.0591C31.6556 53.9581 31.6832 53.8596 31.7309 53.7705C31.7864 53.6718 31.8616 53.5856 31.952 53.5174C32.0423 53.4491 32.1458 53.4003 32.2559 53.3739C34.1078 52.989 35.9033 52.3702 37.5991 51.5323C38.6435 51.0374 39.6062 50.386 40.454 49.6006C41.2821 48.7715 41.8946 47.7521 42.2378 46.6316C42.5811 45.5111 42.6447 44.3236 42.4231 43.1729C42.3106 42.2871 41.9966 41.4388 41.5053 40.6932C41.0139 39.9476 40.3582 39.3246 39.5885 38.8718C37.8488 37.9699 35.8459 37.7132 33.9349 38.1472C32.3691 38.3396 30.8699 38.8948 29.5566 39.7688C28.2433 40.6427 27.1521 41.8112 26.3699 43.1811C24.8476 46.0188 24.3662 49.2997 25.0091 52.4551C25.6179 55.9359 26.9588 58.5638 28.9948 60.2725C31.0309 61.9813 33.9593 62.5406 37.5472 61.9171C39.1873 61.6544 40.7635 61.0856 42.1934 60.2404C42.5993 59.9908 42.9658 59.7529 43.2905 59.5188C43.808 59.1514 44.1595 58.5946 44.2686 57.9694C44.3778 57.3442 44.2357 56.7013 43.8734 56.1803C43.7074 55.9386 43.4952 55.7322 43.249 55.573C43.0028 55.4137 42.7275 55.3049 42.439 55.2527Z" fill="#00C7E3"/>
                <path d="M29.8291 49.6426C29.8993 49.7812 30.0001 49.902 30.1239 49.9958C30.2365 50.1003 30.3729 50.1755 30.5213 50.2149C30.6696 50.2544 30.8254 50.2568 30.9749 50.2221C31.984 49.9852 32.9782 49.6888 33.9523 49.3345C35.2786 48.8523 36.4769 48.073 37.4554 47.0561C37.9044 46.6731 38.2493 46.1829 38.458 45.6309C38.6668 45.0789 38.7326 44.4831 38.6495 43.8989C38.2253 41.753 37.0552 40.9167 34.9319 41.2852C33.9967 41.4019 33.1086 41.7623 32.3566 42.3303C31.6045 42.8983 31.0149 43.6539 30.6468 44.5215C30.0057 45.995 29.6893 47.5892 29.7189 49.1959C29.7213 49.3512 29.759 49.504 29.8291 49.6426Z" fill="white"/>
            </svg> 
        </div>
        <div class="wellcome-mes pl-4">
            <h2 class="mt-4 pt-2">{!! trans('messages.frontend_dashboard_hello', ['name' => Auth::user()->customer->getName()]) !!}</h2>
            <p>{!! trans('messages.frontend_dashboard_welcome') !!}</p>
        </div>
    </div>
    <div class="campaign-head">
        <div class="row">
            <div class="col-md-3">
                <div class="card position-relative">
                    <div class="card-body">
                        asdasdasd
                    </div>
                    <div class="campaign-tools position-absolute">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        asdasdasd
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        asdasdasd
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5 mb-3">
        <span class="material-symbols-rounded me-2">donut_large</span>
        {{ trans("messages.used_quota") }}
    </h3>
    <p>{{ trans('messages.dashboard.credit.wording') }}</p>
    <div class="row quota_box">
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.sending_quota') }}</label>
                    <div class="pull-right text-semibold">
                        <span class="text-muted">{{ number_with_delimiter($sendingCreditsUsed) }}/{{ ($sendingCreditsLimit == -1) ? '∞' : number_with_delimiter($sendingCreditsLimit) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ number_to_percentage($sendingCreditsUsedPercentage) }}</span>
                    </div>
                </div>
                
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ ($sendingCreditsUsedPercentage >= 0.8) ? 'danger' : 'primary' }}" style="width: {{ $sendingCreditsUsedPercentage*100  }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.list') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter($listsCount) }}/{{ $maxLists == -1 ? '∞' : number_with_delimiter($maxLists) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ number_to_percentage($listsUsed) }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ $listsUsed >= 0.8 ? 'danger' : 'primary' }}" style="width: {{ 100*$listsUsed }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.campaign') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter($campaignsCount) }}/{{ $maxCampaigns == -1 ? '∞' : number_with_delimiter($maxCampaigns) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ number_to_percentage($campaignsUsed) }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ $campaignsUsed >= 0.8 ? 'danger' : 'primary' }}" style="width: {{ 100*$campaignsUsed }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.subscriber') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter($subscribersCount) }}/{{ ($maxSubscribers == -1) ? '∞' : number_with_delimiter($maxSubscribers) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ number_to_percentage($subscribersUsed) }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ $subscribersUsed >= 0.8 ? 'danger' : 'primary' }}" style="width: {{ $subscribersUsed*100 }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('_dashboard_campaigns')

    @include('_dashboard_list_growth')    


    @if (isSiteDemo())
    <h3 class="mt-5 mb-3"><span class="material-symbols-rounded me-2">star_half</span> {{ trans('messages.top_5') }}
    </h3>

    <ul class="nav nav-tabs nav-underline" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="campaign_opens-tab" data-bs-toggle="tab" data-bs-target="#campaign_opens" role="button" role="tab" aria-controls="campaign_opens" aria-selected="true">
                {{ trans('messages.campaign_opens') }}
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="campaign_clicks-tab" data-bs-toggle="tab" data-bs-target="#campaign_clicks" role="button" role="tab" aria-controls="campaign_clicks" aria-selected="false">
                {{ trans('messages.campaign_clicks') }}
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="clicked_links-tab" data-bs-toggle="tab" data-bs-target="#clicked_links" role="button" role="tab" aria-controls="clicked_links" aria-selected="false">
                {{ trans('messages.clicked_links') }}
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="campaign_opens" role="tabpanel" aria-labelledby="campaign_opens-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topOpens(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                                {{ $item->name }}
                                            </a>
                                        </h6>
                                        <p class="mb-0">
                                            {!! $item->displayRecipients() !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->aggregate) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.opens') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->readCache('UniqOpenCount')) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.uniq_opens') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ (null !== $item->lastOpen()) ? Auth::user()->customer->formatDateTime($item->lastOpen()->created_at, 'datetime_full') : "" }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_open') }}</span>
                            </div>
                        </div>

                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">auto_awesome</span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="tab-pane fade" id="campaign_clicks" role="tabpanel" aria-labelledby="campaign_clicks-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topClicks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                                {{ $item->name }}
                                            </a>
                                        </h6>
                                        <p>
                                            {!! $item->displayRecipients() !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->urlCount() }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.urls') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">auto_awesome</span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="tab-pane fade" id="clicked_links" role="tabpanel" aria-labelledby="clicked_links-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topLinks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold url-truncate">
                                            <a title="{{ $item->url }}" href="{{ $item->url }}" target="_blank">
                                                {{ $item->url }}
                                            </a>
                                        </h6>
                                        <p>
                                            {{ $item->aggregate }} {{ trans('messages.campaigns') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">auto_awesome</span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    @endif

    <h3 class="mt-5 mb-3"><span class="material-symbols-rounded me-2">web_stories</span>
         {{ trans('messages.activity_log') }}</h3>

    @if (Auth::user()->customer->logs()->count() == 0)
        <div class="empty-list">
            <span class="material-symbols-rounded">auto_awesome</span>
            <span class="line-1">
                {{ trans('messages.no_activity_logs') }}
            </span>
        </div>
    @else
        <div class="action-log-box">
            <!-- Timeline -->
            <div class="">
                <div class="mt-4">
                    @foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
                        <!-- Sales stats -->
                        <div class="d-flex mb-3">
                            <div class="card px-0 shadow-sm container-fluid">
                                <div class="card-body pt-2">
                                    <div class="d-flex align-items-center pt-1">
                                        <label class="panel-title text-semibold my-0 fw-600">{{ $log->customer->getName() }}</label>
                                        <div class="d-flex align-items-center ms-auto text-muted">
                                            <span style="font-size: 18px" class="material-symbols-rounded ms-auto me-2">history</span>
                                            <div class="">
                                                <span class="heading-text"><i class="icon-history position-left text-success"></i> {{ $log->created_at->timezone($currentTimezone)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0">{!! $log->message() !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /sales stats -->
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <br>
    <br>
@endsection