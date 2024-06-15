$(document).ready(function () {
    // Set basic salary based on position
    $('#position').change(function () {
        let basicSalary = 0;
        if (this.value == "Manager") {
            basicSalary = 1500000;
        } else if (this.value == "Supervisor") {
            basicSalary = 1000000;
        } else if (this.value == "Staff") {
            basicSalary = 500000;
        }
        $('#basic_salary').val(basicSalary);
    });

});

// Handle form submission
function submitForm() {
    document.getElementById('add_employee_form').submit();
}