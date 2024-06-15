document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.querySelector('.form-control');
    const tableRows = document.querySelectorAll('.table tbody tr');


    searchInput.addEventListener('keyup', function (event) {
        const searchTerm = event.target.value.toLowerCase();

        tableRows.forEach(row => {
            const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const position = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

            if (name.includes(searchTerm) || position.includes(searchTerm)) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });

    const totalSalaryDataElement = document.getElementById('totalSalaryData');
    const totalSalaries = JSON.parse(totalSalaryDataElement.getAttribute('data-total-salaries'));

    const labels = totalSalaries.map(item => item.position);
    const data = totalSalaries.map(item => item.total_salary);

    const ctx = document.getElementById('total-salary-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Salary',
                data: data,
                backgroundColor: ['rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});




