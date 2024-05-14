<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Teachers') }}
            </h2>
            <a href="{{ route('admin.teachers.create') }}" class="font-bold py-4 px-6 bg-indigo-700 text-white rounded-full">
                Add New
            </a>
        </div>
    </x-slot>
    <div>
        
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 flex flex-col gap-y-5">
              @forelse ($teachers as $teacher)
                <div class="item-card flex flex-row justify-between items-center">
                  <div class="flex flex-row items-center gap-x-3">
                      <img src="{{ Storage::url($teacher->user->avatar) }}" alt="" class="rounded-2xl object-cover w-[90px] h-[90px]">
                      <div class="flex flex-col">
                          <h3 class="text-indigo-950 text-xl font-bold">{{ $teacher->user->name }}</h3>
                          <p class="text-slate-500 text-sm">{{ $teacher->user->occupation }}</p>
                          <p class="text-slate-500 text-sm">{{ $teacher->user->email }}</p>
                      </div>
                  </div> 
                  <div class="hidden md:flex flex-col">
                      <p class="text-slate-500 text-sm">Updated</p>
                      <h3 class="text-indigo-950 text-xl font-bold">{{ $teacher->updated_at }}</h3>
                  </div>
                  <div class="hidden md:flex flex-row items-center gap-x-3">
                      <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="font-bold py-4 px-6 bg-red-700 text-white rounded-full" onclick="return confirm('Apakah anda yakin?')">
                              Delete
                          </button>
                      </form>
                  </div>
                </div>
              @empty
                <p>Belum ada guru tersedia</p>
              @endforelse  
            </div>
        </div>
    </div>
</x-app-layout>
