
// Fungsi untuk menghapus format rupiah sebelum mengirim formulir
function dataSubmit() {
    document.getElementById('edit_employee_form').submit(); // Kirim formulir
}

// Pastikan form di-submit dengan benar
$(document).ready(function () {
    $('#editEmployeeLink').click(function (e) {
        e.preventDefault(); // Prevent the default action of the link
        $('#editEmployeeModal').modal('show'); // Show the modal
    });

    // Handle form submission
    $('#edit_employee_form').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        dataSubmit(); // Remove format and submit the form
    });


});
