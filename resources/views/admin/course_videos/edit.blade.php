<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <a href="{{ route('admin.courses.index') }}" onclick="windiw.history.go(-1); return false">
            {{ __('Course Videos') }}
          </a>
          {!! __('&raquo; Update &raquo; Video ') . $courseVideo->name !!}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-10 shadow-sm sm:rounded-lg">

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="py-3 w-full rounded-3xl bg-red-500 text-white">
                            {{$error}}
                        </div>
                    @endforeach
                @endif

                <div class="item-card flex flex-row gap-y-10 justify-between items-center">
                    <div class="flex flex-row items-center gap-x-3">
                        <img src="{{ Storage::url($courseVideo->course->thumbnail) }}" alt="" class="rounded-2xl object-cover w-[120px] h-[90px]">
                        <div class="flex flex-col">
                            <h3 class="text-indigo-950 text-xl font-bold">{{ $courseVideo->course->name }}</h3>
                            <p class="text-slate-500 text-sm">{{ $courseVideo->course->category->name }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm">Teacher</p>
                        <h3 class="text-indigo-950 text-xl font-bold">{{ $courseVideo->course->teacher->user->name }}</h3>
                    </div>
                </div>

                <hr class="my-5">
                
                <form method="POST" action="{{ route('admin.course_videos.update', $courseVideo) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $courseVideo->name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="path_video" :value="__('Path Video')" />
                        <iframe width="560" class="rounded-2xl object-cover w-[120px] h-[90px]" height="315" src="https://www.youtube.com/embed/{{ $courseVideo->path_video }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        <x-text-input id="path_video" class="block mt-1 w-full" type="text" name="path_video" :value="old('path_video') ?? $courseVideo->path_video" required autofocus autocomplete="path_video" />
                        <x-input-error :messages="$errors->get('path_video')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
            
                        <button type="submit" class="font-bold py-4 px-6 bg-indigo-700 text-white rounded-full">
                            Add New Video
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
