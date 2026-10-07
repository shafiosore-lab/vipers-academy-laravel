import Chart from 'chart.js/auto';

// Player Statistics Charts
class PlayerCharts {
    constructor() {
        this.charts = {};
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.createCharts());
        } else {
            this.createCharts();
        }
    }

    createCharts() {
        // Get player data from the page (passed from Laravel)
        const playerData = window.playerData || this.getSampleData();

        this.createSeasonalPerformanceChart(playerData);
        this.createProgressTrackingChart(playerData);
        this.createSkillDistributionChart(playerData);
        this.createPerformanceRadarChart(playerData);
    }

    getSampleData() {
        return {
            name: 'Sample Player',
            seasons: [
                { year: '2022/23', goals: 8, assists: 5, matches: 22, rating: 7.2 },
                { year: '2023/24', goals: 12, assists: 8, matches: 28, rating: 7.8 },
                { year: '2024/25', goals: 15, assists: 10, matches: 25, rating: 8.1 }
            ],
            skills: {
                technical: 85,
                physical: 78,
                mental: 82,
                tactical: 75,
                speed: 88,
                shooting: 82
            },
            currentStats: {
                goals: 15,
                assists: 10,
                matches: 25,
                rating: 8.1
            }
        };
    }

    createSeasonalPerformanceChart(data) {
        const ctx = document.getElementById('seasonalPerformanceChart');
        if (!ctx) return;

        const seasons = data.seasons.map(s => s.year);
        const goals = data.seasons.map(s => s.goals);
        const assists = data.seasons.map(s => s.assists);

        this.charts.seasonalPerformance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: 'Goals',
                        data: goals,
                        backgroundColor: 'rgba(13, 110, 253, 0.8)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Assists',
                        data: assists,
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            stepSize: 2
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    createProgressTrackingChart(data) {
        const ctx = document.getElementById('progressTrackingChart');
        if (!ctx) return;

        const seasons = data.seasons.map(s => s.year);
        const ratings = data.seasons.map(s => s.rating);
        const goals = data.seasons.map(s => s.goals);

        this.charts.progressTracking = new Chart(ctx, {
            type: 'line',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: 'Performance Rating',
                        data: ratings,
                        borderColor: 'rgba(255, 193, 7, 1)',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(255, 193, 7, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Goals Scored',
                        data: goals,
                        borderColor: 'rgba(220, 53, 69, 1)',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return `Rating: ${context.parsed.y}/10`;
                                }
                                return `Goals: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    createSkillDistributionChart(data) {
        const ctx = document.getElementById('skillDistributionChart');
        if (!ctx) return;

        const skills = Object.keys(data.skills);
        const values = Object.values(data.skills);

        this.charts.skillDistribution = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: skills.map(skill => skill.charAt(0).toUpperCase() + skill.slice(1)),
                datasets: [{
                    data: values,
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(111, 66, 193, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.parsed}%`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                cutout: '60%'
            }
        });
    }

    createPerformanceRadarChart(data) {
        const ctx = document.getElementById('performanceRadarChart');
        if (!ctx) return;

        const skills = Object.keys(data.skills);
        const values = Object.values(data.skills);

        this.charts.performanceRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: skills.map(skill => skill.charAt(0).toUpperCase() + skill.slice(1)),
                datasets: [{
                    label: 'Skill Level',
                    data: values,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.parsed.r}%`;
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // Method to update charts with new data
    updateCharts(newData) {
        Object.keys(this.charts).forEach(chartKey => {
            if (this.charts[chartKey]) {
                this.charts[chartKey].destroy();
            }
        });
        window.playerData = newData;
        this.createCharts();
    }

    // Method to destroy all charts
    destroy() {
        Object.keys(this.charts).forEach(chartKey => {
            if (this.charts[chartKey]) {
                this.charts[chartKey].destroy();
            }
        });
    }
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on player detail page
    if (document.querySelector('.player-profile-page')) {
        window.playerCharts = new PlayerCharts();
    }
});

// Export for potential use in other modules
export default PlayerCharts;
