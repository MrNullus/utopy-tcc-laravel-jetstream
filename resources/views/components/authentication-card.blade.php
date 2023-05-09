<div class="min-h-screen flex flex-row sm:justify-center pt-6 sm:pt-0 bg-slate-900">
    <div class=" w-3/5 flex flex-col justify-center items-center bg-slate-900 shadow-xl" style="box-shadow: 4px 0px 4px -2px #0d1424;">
        <div class=" top-0 left-0 ml-7 mt-5 absolute ">
            {{ $logo }}
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-slate-900  overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    <div class="w-full flex justify-center items-center ">
        <h1 class=" text-white">
            <svg width="628" height="628" viewBox="0 0 628 628" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <rect width="628" height="628" fill="url(#pattern0)"/>
                <defs>
                <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#image0_204_822" transform="scale(0.000976562)"/>
                </pattern>
                </defs>
                </svg>
        </h1>
    </div>
</div>