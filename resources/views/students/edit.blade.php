<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <form action="{{ route('students.update', $student) }}" method="POST" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded m-10" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ $student->name }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ $student->email }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ $student->phone }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
            <textarea name="address" id="address" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $student->address }}</textarea>
            <x-input-error :messages="$errors->get('address')" />
        </div>

        <!-- Image -->
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-bold mb-2">Profile Image</label>
            <input type="file" name="image" id="image" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('image')" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Update
            </button>
        </div>
    </form>
</x-app-layout>