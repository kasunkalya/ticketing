@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Update Profile - Ticket Management</h2>
        <div class="flex items-center justify-center">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-700 text-center">Update Profile</h2>
                <form id="registerUpdateForm" method="post" class="mt-6 space-y-6">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PATCH">

                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                        <input id="name" name="name" type="text" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @if ($errors->has('name'))
                            <p class="mt-2 text-red-600 text-sm">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                        <input id="email" name="email" type="email" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-red-600 text-sm">{{ $errors->first('email') }}</p>
                        @endif

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    Your email address is unverified.
                                    <button form="send-verification" 
                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Click here to re-send the verification email.
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        A new verification link has been sent to your email address.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">                        
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            Save
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-gray-600" 
                               x-data="{ show: true }"
                               x-show="show"
                               x-transition
                               x-init="setTimeout(() => show = false, 2000)">
                               Saved.
                            </p>
                        @endif
                    </div>
                </form>

            </div>
       </div>
    
</div>
<script>
$(document).ready(function(){
    $("#registerUpdateForm").submit(function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        $("#submitBtn").prop("disabled", true).text("Submitting");

        $.ajax({
            url: "{{ route('profile.update') }}",
            type: "POST",
            data: formData,
            success: function(response) {               
                Swal.fire({
                    icon: "success",
                    title: "User updated successfully!",                   
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });              
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
