<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Create Student') }}
        </h2>
    </x-slot>

    <form action="{{ route('students.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded m-10" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter Student's Name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter Student's Email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
            <input type="text" name="phone" id="phone" placeholder="Enter Student's Phone Number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
            <textarea name="address" id="address" rows="3" placeholder="Enter Student's Address" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
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
                Submit
            </button>
        </div>
    </form>
</x-app-layout>