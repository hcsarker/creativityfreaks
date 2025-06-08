// Initialize dashboard charts
function initDashboardCharts() {
    // Progress chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    window.progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            },
            elements: { point: { radius: 0 } }
        }
    });
}

// Tab functionality
function setupCourseTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter courses
            const filter = this.dataset.tab;
            filterCourses(filter);
        });
    });
}

function filterCourses(filter) {
    const courseCards = document.querySelectorAll('.course-card');
    
    courseCards.forEach(card => {
        const isCompleted = card.classList.contains('completed');
        
        if(filter === 'all') {
            card.style.display = 'block';
        } else if(filter === 'active' && !isCompleted) {
            card.style.display = 'block';
        } else if(filter === 'completed' && isCompleted) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.student-dashboard')) {
        initDashboardCharts();
        setupCourseTabs();
    }
});