const ctx = document.getElementById('admin-chart')
if (ctx) {
    createAdminChart()
}
async function createAdminChart() {
    const json = await getData('../api/api_ticket.php', {'request': 'closedTicketsLast7Days'})
    console.log(json)
    if (json['error']) {
        console.error(json['error'])
        return
    }
    console.log(json['success'])
    new Chart(ctx, {
        type: 'bar',
        data: {
        labels: ['6 days ago', '5 days ago','4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'],
        datasets: [{
            label: '# of Closed tickets',
            data: json['tickets'],
            borderWidth: 1,
            
        }]
    },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // display only integers
                    }
                }
            },
            elements: {
                bar: {
                    backgroundColor: 'rgba(153, 102, 255, 0.2)', // TODO change color
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                }
            }
        }
    });
}
