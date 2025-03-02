@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">Submit a Support Ticket</h2>
     
    <form id="ticketForm" class="mt-6" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Your Name</label>
            <input type="text" name="customer_name" placeholder="Kasun Kalya" required 
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email Address</label>
            <input type="email" name="email" placeholder="you@example.com" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Phone Number</label>
            <input type="text" name="phone" placeholder="+94 77 123 4567" required 
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Describe Your Issue</label>
            <textarea name="problem_description" rows="5" placeholder="Describe your issue in detail" required
                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

         
            <button type="submit" id="submitBtn" class="btn btn-primary">
                Submit Ticket
            </button>
        </div>
   </form>
</div>


<script>
$(document).ready(function(){
    $("#ticketForm").submit(function(e){
        e.preventDefault();
        let formData = $(this).serialize();

        $("#submitBtn").prop("disabled", true).text("Submitting");

        $.ajax({
            url: "{{ route('tickets.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Ticket Submitted!",
                    html: `<p>Your support ticket has been successfully submitted.</p>
                           <p><strong>Reference No:</strong> <span class="text-blue-600">${response.reference_no}</span></p>
                           <p>We will get back to you soon.</p>`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });

                $("#ticketForm")[0].reset();
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
                $("#submitBtn").prop("disabled", false).text("Submit Ticket");
            }
        });
    });
});
</script>


@endsection
