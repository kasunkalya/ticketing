@extends('layouts.app')
@section('content')
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    padding: 10px;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    text-align: left;
    position: relative;
    margin-top: 10%;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        padding: 15px;
        margin-top: 40%;
    }

    .close {
        font-size: 24px;
        right: 10px;
    }
}


</style>

<div class="container">
    <h2 class="mb-4">Dashboard - Ticket Management</h2>

    <div class="mb-3">
        <input type="text" id="searchCustomer" class="form-control" placeholder="Search by customer name">
    </div>
  
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Reference No</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ticketTableBody">
                @foreach($tickets as $ticket)
                    <tr data-id="{{ $ticket->id }}" class="{{ strtolower($ticket->status) == 'new' ? 'table-warning' : '' }}">
                        <td>{{ $ticket->reference_no }}</td>
                        <td>{{ $ticket->customer_name }}</td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm view-ticket"
                                data-id="{{ $ticket->id }}"
                                data-reference="{{ $ticket->reference_no }}"
                                data-customer="{{ $ticket->customer_name }}"
                                data-email="{{ $ticket->email }}"
                                data-status="{{ ucfirst($ticket->status) }}"
                                data-description="{{ $ticket->problem_description }}"
                                data-replies="{{ json_encode($ticket->replies) }}">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>

<div id="ticketModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="text-center">Ticket Details</h2>
        <div class="p-3">
            <p><strong>Reference No:</strong> <span id="ticket-reference"></span></p>
            <p><strong>Customer Name:</strong> <span id="ticket-customer"></span></p>
            <p><strong>Email:</strong> <span id="ticket-email"></span></p>
            <p><strong>Status:</strong> <span id="ticket-status"></span></p>
            <p><strong>Issue:</strong></p>
            <p id="ticket-description" class="border p-2 rounded bg-light"></p>

            <h3 class="mt-3">Reply History</h3>
            <div id="ticket-replies" class="border p-3 bg-light rounded" style="max-height: 250px; overflow-y: auto;"></div>

            <form id="replyForm" method="POST" class="mt-3">
                @csrf
                <input type="hidden" id="ticketId" name="ticket_id">
                <div class="mb-3">
                    <label class="form-label">Reply Message:</label>
                    <textarea name="reply_message" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Send Reply</button>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    
    let modal = document.getElementById("ticketModal");
    let closeModal = document.querySelector(".close");

    function openModal(button) {
        let ticketId = button.dataset.id;
        let modal = document.getElementById("ticketModal");

        document.getElementById("ticket-reference").textContent = button.dataset.reference;
        document.getElementById("ticket-customer").textContent = button.dataset.customer;
        document.getElementById("ticket-email").textContent = button.dataset.email;
        document.getElementById("ticket-status").textContent = button.dataset.status;
        document.getElementById("ticket-description").textContent = button.dataset.description;
        document.getElementById("ticketId").value = ticketId;  

        let repliesContainer = document.getElementById("ticket-replies");
        repliesContainer.innerHTML = "";
        let replies = JSON.parse(button.dataset.replies);
        if (replies.length > 0) {
            replies.forEach(reply => {
                let replyElement = document.createElement("div");
                replyElement.classList.add("border", "p-2", "mb-2", "rounded");
                replyElement.innerHTML = `
                    <p><strong>${reply.user.name}:</strong> ${reply.message}</p>
                    <small class="text-muted">${reply.created_at}</small>
                `;
                repliesContainer.appendChild(replyElement);
            });
        } else {
            repliesContainer.innerHTML = "<p class='text-muted'>No replies yet.</p>";
        }

        modal.style.display = "flex";

        fetch("{{ route('tickets.markAsViewed', '') }}/" + ticketId, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ status: "Opened" })
        })
        .then(response => response.json())
        .then(data => {
            let row = document.querySelector(`tr[data-id="${ticketId}"]`);
            if (row) {
                let statusBadge = row.querySelector(".badge");
                if (statusBadge) {
                    statusBadge.textContent = "Opened";
                }
                row.classList.remove("table-warning"); 
            }
        })
        .catch(error => console.error("Error updating ticket status:", error));
    }


    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("view-ticket")) {
            openModal(event.target);
        }
    });

  
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

 
    document.getElementById("replyForm").addEventListener("submit", function (event) {
        event.preventDefault();
        let formData = new FormData(this);
        let ticketId = document.getElementById("ticketId").value;

        fetch("{{ route('replies.store', '') }}/" + ticketId, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {            
            Swal.fire({
                title: "Success!",
                text: "Reply Sent Successfully!",
                icon: "success",
                confirmButtonText: "OK"
            });
            modal.style.display = "none";
            this.reset();
        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: "Could not send reply.",
                icon: "error",
                confirmButtonText: "Try Again"
            });
            console.error(error);
        });
        
       
    });
   
    function loadTickets() {
        fetch("{{ route('tickets.list') }}")
        .then(response => response.json())
        .then(data => {
            let tbody = document.getElementById("ticketTableBody");
            let existingIds = Array.from(document.querySelectorAll("#ticketTableBody tr"))
                .map(row => row.dataset.id);

            data.forEach(ticket => {
                if (!existingIds.includes(ticket.id.toString())) {
                    let row = document.createElement("tr");
                    row.dataset.id = ticket.id;
                    row.classList.add("table-warning");
                    row.innerHTML = `
                        <td>${ticket.reference_no}</td>
                        <td>${ticket.customer_name}</td>
                        <td>${ticket.email}</td>
                        <td>${ticket.status}</td>
                        <td>
                            <button class="btn btn-primary btn-sm view-ticket" 
                                data-id="${ticket.id}" 
                                data-reference="${ticket.reference_no}" 
                                data-customer="${ticket.customer_name}" 
                                data-email="${ticket.email}" 
                                data-status="${ticket.status}" 
                                data-description="${ticket.problem_description}"
                                data-replies="${ticket.replies}">
                                View
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                }
            });
        })
        .catch(error => console.error("Error loading tickets:", error));
    }
    setInterval(loadTickets, 10000);
     document.getElementById("searchCustomer").addEventListener("keyup", function () {
            let searchValue = this.value.toLowerCase();
            document.querySelectorAll("#ticketTableBody tr").forEach(row => {
                let customerName = row.cells[1].textContent.toLowerCase();
                if (customerName.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
    });
});

</script>
@endsection
