window.onload = function() {
    fetch('php/fetch_bookings.php') // Ensure this path is correct
    .then(response => response.json())
    .then(data => {
        let bookingList = document.getElementById('booking-list');
        // Clear existing content
        bookingList.innerHTML = '';

        if (data.length === 0) {
            bookingList.innerHTML = '<tr><td colspan="6">No bookings found.</td></tr>'; // Adjusted colspan to 6
        } else {
            data.forEach(booking => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="cancel-checkbox" data-id="${booking.id}"></td> <!-- Checkbox added -->
                    <td>${booking.id}</td>
                    <td>${booking.name}</td>
                    <td>${booking.email}</td>
                    <td>${booking.destination}</td>
                    <td>${booking.date}</td>
                `;
                bookingList.appendChild(row);
            });
        }
    })
    .catch(error => {
        console.error('Error fetching bookings:', error);
        let bookingList = document.getElementById('booking-list');
        bookingList.innerHTML = '<tr><td colspan="6">Error loading bookings.</td></tr>'; // Adjusted colspan to 6
    });

    // Adding the event listener for the cancel button
    document.querySelector('.btn-cancel').addEventListener('click', cancelSelectedBookings);
};

// Function to handle cancel button click - moved outside
function cancelSelectedBookings() {
    const checkboxes = document.querySelectorAll('.cancel-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Please select at least one booking to cancel.');
        return;
    }

    let bookingIds = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-id'));

    fetch('php/cancel_bookings.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ bookingIds })
    })
    .then(response => response.text()) // Log the raw response as text
    .then(result => {
        console.log(result); // Log the raw response to see what's actually returned
        try {
            const jsonResponse = JSON.parse(result); // Parse the JSON result
            if (jsonResponse.success) {
                window.location.reload(); // Reload the page on success
            } else {
                alert('Error canceling bookings: ' + jsonResponse.error);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Error: Received invalid response from the server.');
        }
    })
    .catch(error => {
        console.error('Error canceling bookings:', error);
        alert('Error canceling bookings.');
    });
}
