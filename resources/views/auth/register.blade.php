@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Add User - Ticket Management</h2>
        <div class="flex items-center justify-center">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-700 text-center">Add User</h2>
                <form id="registerForm" class="mt-6" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                        <input id="name" type="text" name="name" required autofocus 
                               class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                        <input id="email" type="email" name="email" required 
                               class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                        <input id="password" type="password" name="password" required 
                               class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                               class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="flex items-center justify-end mt-4">  
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            User Register
                        </button>
                    </div>                   
                </form>  
            </div>
       </div>
    
</div>
<script>
$(document).ready(function(){
    $("#registerForm").submit(function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        $("#submitBtn").prop("disabled", true).text("Submitting");

        $.ajax({
            url: "{{ route('user.register') }}",
            type: "POST",
            data: formData,
            success: function(response) {               
                Swal.fire({
                    icon: "success",
                    title: "User added successfully!",                   
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });
                $("#registerForm")[0].reset();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = "Something went wrong. Please try again.";

                if (errors) {
                    errorMessage = Object.values(errors).map(msg => msg.join("\n")).join("\n");
                }

                Swal.fire({
                    icon: "error",
                    title: "Submission Failed!",
                    text: errorMessage,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Try Again"
                });
            },
            complete: function() {
                $("#submitBtn").prop("disabled", false).text("User Register");
            }
        });
    });
});
</script>
@endsection
