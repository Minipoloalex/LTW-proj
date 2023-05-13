const closedTicketsChart = document.getElementById('closed-tickets-chart')
if (closedTicketsChart) {
    createClosedTicketsChart(closedTicketsChart)
    const openTicketsChart = document.getElementById('open-tickets-chart')
    createOpenTicketsChart(openTicketsChart)
}
async function createClosedTicketsChart(element) {
    const json = await getData('../api/api_ticket.php', {'request': 'closedTicketsLast7Days'})
    console.log(json)
    if (json['error']) {
        console.error(json['error'])
        return
    }
    console.log(json['success'])
    newBarChart(element, json['tickets'], '# of Closed tickets')
}
async function createOpenTicketsChart(element) {
    const json = await getData('../api/api_ticket.php', {'request': 'openTicketsLast7Days'})
    console.log(json)
    if (json['error']) {
        console.error(json['error'])
        return
    }
    console.log(json['success'])
    newBarChart(element, json['tickets'], '# of Open tickets')
}
function newBarChart(element, data, chartLabel) {
    new Chart(element, {
        type: 'bar',
        data: {
            labels: ['6 days ago', '5 days ago','4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'],
            datasets: [{
                label: chartLabel,
                data: data,
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
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                }
            }
        }
    })
}