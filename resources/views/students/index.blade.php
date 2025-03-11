<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            <button onclick="openModal()" class="bg-indigo-500 text-white px-4 py-2 rounded">Create Student</button>
        </h2>
    </x-slot>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hide the modal initially
            $("#createModal").addClass("translate-x-full hidden");
            $("#updateModal").addClass("translate-x-full hidden");






            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();

                    // Fetch student data from data attributes
                    let studentId = this.getAttribute("data-id");
                    let name = this.getAttribute("data-name");
                    let email = this.getAttribute("data-email");
                    let phone = this.getAttribute("data-phone");
                    let address = this.getAttribute("data-address");
                    let image = this.getAttribute("data-image");

                    console.log("Student ID:", studentId); // Debugging: Ensure ID is being fetched

                    // Set form action dynamically
                    let form = document.querySelector("#updateModal form");
                    form.action = `/students/${studentId}`;

                    // Populate the fields
                    document.querySelector("#updateModal #name").value = name;
                    document.querySelector("#updateModal #email").value = email;
                    document.querySelector("#updateModal #phone").value = phone;
                    document.querySelector("#updateModal #address").value = address;
                    document.querySelector("#updateModal #preview").src = image;

                    // Show the update modal

                    openupdateModal();
                });
            });
        });
        $("form").on("submit", function(e) {
            let hasErrors = false;

            // $(".error-message").remove();
            if ($(".error-message").length > 0) {
                e.preventDefault();
            }

            $(this).find("input, textarea").each(function() {
                if ($(this).val().trim() === "") {
                    hasErrors = true;
                    $(this).after('<span class="error-message text-red-500 text-sm">This field is required</span>');
                }
            });

            // Prevent form submission if there are errors
            if (hasErrors) {
                e.preventDefault();
            }
        });

        function openModal() {
            $("#createModal")
                .removeClass("translate-x-full hidden")
                .addClass("translate-x-0")
                .fadeIn();
        }

        function openupdateModal() {
            $("#updateModal")
                .removeClass("translate-x-full hidden")
                .addClass("translate-x-0")
                .fadeIn();
        }

        function previewImage(event) {
            const image = document.getElementById("preview");
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    image.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function closeupdateModal() {
            $("#updateModal")
                .removeClass("translate-x-0")
                .addClass("translate-x-full")
                .fadeOut(function() {
                    $(this).addClass("hidden"); // Hide completely after fadeOut
                });
        }

        function closeModal() {
            $("#createModal")
                .removeClass("translate-x-0")
                .addClass("translate-x-full")
                .fadeOut(function() {
                    $(this).addClass("hidden"); // Hide completely after fadeOut
                });
        }

        function openSubject() {
            $("#addSubject").removeClass("hidden translate-x-full").addClass("translate-x-0").fadeIn();
        }

        function closeSubjectModal() {
            $("#addSubject").fadeOut(function() {
                $(this).addClass("hidden translate-x-full");
            });
        }
    </script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Image</th>
                                <!-- <th>Subjects</th> -->

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->address }}</td>

                                <td>
                                    <div style="width: 50px; height: 50px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $student->image) }}" alt="Student Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>


                                <td>
                                    <!-- <a href="#" class="btn btn-danger btn-sm" onclick="openSubject()">
                                        <i class="fas fa-eye"></i>
                                    </a> -->
                                    <a href="#" onclick="openupdateModal()" class="btn btn-warning btn-sm edit-btn"
                                        data-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}"
                                        data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}"
                                        data-address="{{ $student->address }}"
                                        data-image="{{ Storage::url($student->image) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault();document.getElementById('delete-form-{{$student->id}}').submit();">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <form method="post" id="delete-form-{{$student->id}}" style="display:none"
                                        action="{{ route('students.destroy', $student->id) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="createModal" class="fixed top-0 right-0 w-1/3 h-full bg-white shadow-lg transform translate-x-full  transition-transform duration-300 ease-in-out overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Create Student</h2>
            <button onclick="closeModal()" id="closeForm" class="text-gray-600 text-lg">&times;</button>
        </div>

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- <input type="text" name="id" id="id" value="{{ $individualStudent->id ?? '' }}"> -->

            <div class="mb-3">
                <label for="name" class="block text-gray-700 font-bold mb-1">Name</label>
                <input type="text" name="name" id="name" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('name')" />

            </div>

            <div class="mb-3">
                <label for="email" class="block text-gray-700 font-bold mb-1">Email</label>
                <input type="email" name="email" id="email" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('email')" />

            </div>

            <div class="mb-3">
                <label for="phone" class="block text-gray-700 font-bold mb-1">Phone</label>
                <input type="text" name="phone" id="phone" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('phone')" />

            </div>

            <div class="mb-3">
                <label for="address" class="block text-gray-700 font-bold mb-1">Address</label>
                <textarea name="address" id="address" rows="2" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500"></textarea>
                <x-input-error :messages="$errors->get('address')" />

            </div>

            <div class="mb-3">
                <label for="image" class="block text-gray-700 font-bold mb-1">Profile Image</label>
                <input type="file" name="image" id="image" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('image')" />

            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitAdd" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600">
                    Submit
                </button>
            </div>
        </form>
    </div>


    <!-- update modal  -->

    <div id="updateModal" class="fixed top-0 right-0 w-1/3 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Edit Student</h2>
            <button onclick="closeupdateModal()" id="closeForm" class="text-gray-600 text-lg">&times;</button>
        </div>

        <form action="{{ route('students.update', $student->id ?? 0) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- <input type="text" name="id" id="id" value="{{ $individualStudent->id ?? '' }}"> -->

            <div class="mb-3">
                <label for="name" class="block text-gray-700 font-bold mb-1">Name</label>
                <input type="text" name="name" id="name" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500" value="{{ $student->name ?? "" }}">
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="mb-3">
                <label for="email" class="block text-gray-700 font-bold mb-1">Email</label>
                <input type="email" name="email" id="email" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500" value="{{ $student->email ??""}}">
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-3">
                <label for="phone" class="block text-gray-700 font-bold mb-1">Phone</label>
                <input type="text" name="phone" id="phone" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500" value="{{ $student->phone ??""}}">
                <x-input-error :messages="$errors->get('phone')" />
            </div>

            <div class="mb-3">
                <label for="address" class="block text-gray-700 font-bold mb-1">Address</label>
                <textarea name="address" id="address" rows="2" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">{{ $student->address?? "" }}</textarea>
                <x-input-error :messages="$errors->get('address')" />
            </div>

            <div class="mb-3">
                <label for="image" class="block text-gray-700 font-bold mb-1">Profile Image</label>
                <input type="file" name="image" id="image" class="w-full px-2 py-1 border rounded-md text-sm focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('image')" />
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitAdd" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600">
                    Submit
                </button>
            </div>
        </form>
    </div>


    <!-- update student subjects -->
    <div id="addSubject" class="fixed top-0 right-0 w-1/3 h-full bg-white shadow-lg transform hidden transition-transform duration-300 ease-in-out overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">User Subjects</h2>
            <button onclick="closeSubjectModal()" id="closeForm" class="text-gray-600 text-lg">&times;</button>
        </div>

        <form action="{{ route('students.addsubject' ) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <ul>Subjects


            </ul>

            <div class="flex justify-end">
                <button type="submit" id="submitAdd" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-gray-600">
                    Update
                </button>
            </div>
        </form>


    </div>

</x-app-layout>