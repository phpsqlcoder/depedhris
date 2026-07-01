<?php
// ob_start();
// session_start();
// //if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
// if(!$_SESSION['ndex']){header("location:login.php");}
// if($_SESSION['deptId']>0){
// 	header("location:tools_setshiftingperdept.php");
// }
// 	include("dbcon.php");
// 	include("scripts/scripts.php");
// ?>


<?php include "header.php";?>
<style>
     /* 4. DASHBOARD SPECIFIC STYLES */
        .dashboard-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card.hover-elevate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        /* Metric Icon styling */
        .metric-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        /* Shortcut Actions */
        .shortcut-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem;
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            text-decoration: none;
            color: #374151;
            transition: all 0.2s ease;
            text-align: center;
        }

        .shortcut-button:hover {
            background-color: var(--brand-surface-blue);
            border-color: var(--brand-primary-blue);
            color: var(--brand-primary-blue);
        }

        .shortcut-button i {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: var(--brand-primary-blue);
        }

        /* Table & Approvals styling */
        .table-hris th {
            background-color: var(--brand-surface-blue);
            color: var(--brand-dark-blue);
            font-weight: 600;
            font-size: 0.825rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 1rem 0.75rem;
            border-bottom: 2px solid #cbd5e1;
        }

        .table-hris td {
            font-size: 0.875rem;
            padding: 0.85rem 0.75rem;
            vertical-align: middle;
            color: #334155;
        }

        .badge-status {
            font-weight: 500;
            padding: 0.35rem 0.6rem;
            border-radius: 30px;
            font-size: 0.75rem;
        }

        /* Avatar styles for lists */
        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .avatar-blue {
            background-color: var(--brand-light-blue);
            color: var(--brand-primary-blue);
        }

        .avatar-green {
            background-color: rgba(var(--bs-success-rgb), 0.15);
            color: rgb(var(--bs-success-rgb));
        }

        .avatar-orange {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: #d97706;
        }
</style>
 <main class="container my-4">
        
        <!-- Header Title / Real-time Insights Info -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
            <div>
                <h1 class="h4 fw-bold text-dark mb-1">HRIS Operations Dashboard</h1>
                <p class="text-muted small mb-0">Overview of operational metrics, workforce demographics, perfect attendances, and shifts today.</p>
            </div>
            <div class="text-sm-end">
                <span class="badge bg-white border text-dark py-2 px-3 fw-medium shadow-sm rounded-pill d-inline-flex align-items-center gap-2">
                    <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                    Live Cluster Updates
                </span>
            </div>
        </div>

        <!-- 4. KEY METRICS ROW (KPI CARDS) -->
        <div class="row g-3 mb-4">
            
            <!-- Metric 1: Total Headcount -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="dashboard-card hover-elevate p-3 h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Total Active Force</span>
                        <h3 class="fw-bold mb-1">248</h3>
                        <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>+3 this month</span>
                    </div>
                    <div class="metric-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Today's Attendance -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="dashboard-card hover-elevate p-3 h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Daily Present Rate</span>
                        <h3 class="fw-bold mb-1">94.2%</h3>
                        <span class="text-muted small fw-medium">Target: 95.0%</span>
                    </div>
                    <div class="metric-icon-box bg-success-subtle text-success">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Open Requisitions -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="dashboard-card hover-elevate p-3 h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Open Talent Roles</span>
                        <h3 class="fw-bold mb-1">12</h3>
                        <span class="text-danger small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>4 Urgent Tracks</span>
                    </div>
                    <div class="metric-icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Payroll Budget Forecast -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="dashboard-card hover-elevate p-3 h-100 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Absent and On Leave</span>
                        <h3 class="fw-bold mb-1">5</h3>
                        <span class="text-primary small fw-semibold">+2 This week</span>
                    </div>
                    <div class="metric-icon-box bg-info-subtle text-info">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- 5. ANALYTICS ROW (CHARTS & GRAPHS) -->
        <div class="row g-4 mb-4">

            <!-- Left Panel: Birthdays for the Month (June) -->
            <div class="col-12 col-lg-6">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-0">🎂 Birthdays This Month</h3>
                            <span class="text-muted small">Celebrating June Celebrants in the workforce</span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">June</span>
                    </div>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-orange">ER</div>
                                <div>
                                    <div class="fw-semibold text-dark">Elena Rostova</div>
                                    <span class="text-muted small">Engineering Team — <b>June 30 (Tomorrow)</b></span>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm rounded-pill py-1 px-3" title="Send wishes via internal Slack/Mail">
                                <i class="bi bi-gift me-1"></i> Wish
                            </button>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-blue">CS</div>
                                <div>
                                    <div class="fw-semibold text-dark">Chloe Sterling</div>
                                    <span class="text-muted small">Engineering Team — <b>June 22</b></span>
                                </div>
                            </div>
                            <button class="btn btn-light btn-sm rounded-pill py-1 px-3 text-muted" disabled>
                                <i class="bi bi-check-circle me-1"></i> Wished
                            </button>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-green">MV</div>
                                <div>
                                    <div class="fw-semibold text-dark">Marcus Vance</div>
                                    <span class="text-muted small">Human Resources — <b>June 15</b></span>
                                </div>
                            </div>
                            <button class="btn btn-light btn-sm rounded-pill py-1 px-3 text-muted" disabled>
                                <i class="bi bi-check-circle me-1"></i> Wished
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
          

            <!-- Middle Chart: Total Employees per Employment Status -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-dark mb-0">Employment Status</h3>
                        <span class="text-muted small">Total: 248</span>
                    </div>
                    <div style="height: 200px; position: relative;" class="d-flex align-items-center justify-content-center">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                    <div id="statusLegend" class="d-flex justify-content-center gap-2 mt-3 flex-wrap small text-muted"></div>
                </div>
            </div>

            <!-- Right Chart: DTR Today Summary -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-dark mb-0">DTR Today Summary</h3>
                        <span class="badge bg-success-subtle text-success py-1 px-2 rounded-pill small">94% Present</span>
                    </div>
                    <div style="height: 200px; position: relative;" class="d-flex align-items-center justify-content-center">
                        <canvas id="dtrSummaryChart"></canvas>
                    </div>
                    <div id="dtrLegend" class="d-flex justify-content-center gap-2 mt-3 flex-wrap small text-muted"></div>
                </div>
            </div>

        </div>

        <!-- 6. SPOTLIGHT SECTION: BIRTHDAYS & PERFECT ATTENDANCE -->
        <div class="row g-4 mb-4">
            

            <!-- Left Chart: Attendance Trend -->
            <div class="col-12 col-xl-6">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-dark mb-0">Attendance & Compliance Trend (Q2)</h3>
                        <span class="badge bg-light border text-secondary rounded-pill font-monospace small">Weekly</span>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="attendanceTrendChart"></canvas>
                    </div>
                </div>
            </div>
            

            <!-- Right Panel: Perfect Attendance for the Month (No Absent, No Late) -->
            <div class="col-12 col-lg-6">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-0">🏆 Perfect Attendance Roll</h3>
                            <span class="text-muted small">No absences, no late arrivals registered for June</span>
                        </div>
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">42 Elite Members</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-blue">SN</div>
                                <div>
                                    <div class="fw-semibold text-dark">Siddharth Nair</div>
                                    <span class="text-muted small">Senior Payroll Auditor — <b>Finance Desk</b></span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded">100% On-time</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-green">SJ</div>
                                <div>
                                    <div class="fw-semibold text-dark">Sarah Jenkins</div>
                                    <span class="text-muted small">Talent Associate — <b>Human Resources</b></span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded">100% On-time</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-orange">DK</div>
                                <div>
                                    <div class="fw-semibold text-dark">David Kim</div>
                                    <span class="text-muted small">Senior Lead Security — <b>Strategic Ops</b></span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded">100% On-time</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 7. OPERATIONAL ROW (TABLE & QUICK ACTION SHORTCUTS) -->
        <div class="row g-4">
            
            <!-- Left Side: Recent Leave / Approvals Table -->
            <div class="col-12 col-lg-8">
                <div class="dashboard-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-0">Pending Leave Workflows</h3>
                            <span class="text-muted small">Immediate administrative approval requests.</span>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm px-3 rounded-pill">View All Queue</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hris align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Interval</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Elena Rostova</div>
                                        <span class="text-muted small">Engineering Team</span>
                                    </td>
                                    <td>Maternity track</td>
                                    <td>Jun 28 - Jul 10 (12d)</td>
                                    <td><span class="badge bg-warning-subtle text-warning badge-status"><i class="bi bi-clock-fill me-1"></i>Pending Mgr</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-success btn-xs py-1 px-2 rounded-2" title="Approve"><i class="bi bi-check-lg"></i></button>
                                        <button class="btn btn-danger btn-xs py-1 px-2 rounded-2" title="Decline"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Marcus Vance</div>
                                        <span class="text-muted small">Human Resources</span>
                                    </td>
                                    <td>Casual Leave</td>
                                    <td>Jul 02 - Jul 03 (2d)</td>
                                    <td><span class="badge bg-success-subtle text-success badge-status"><i class="bi bi-check-circle-fill me-1"></i>Approved</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-outline-secondary btn-xs py-1 px-2 rounded-2" disabled><i class="bi bi-file-earmark-text"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Siddharth Nair</div>
                                        <span class="text-muted small">Finance Desk</span>
                                    </td>
                                    <td>Medical track</td>
                                    <td>Jun 25 - Jun 26 (2d)</td>
                                    <td><span class="badge bg-warning-subtle text-warning badge-status"><i class="bi bi-clock-fill me-1"></i>Pending HR</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-success btn-xs py-1 px-2 rounded-2" title="Approve"><i class="bi bi-check-lg"></i></button>
                                        <button class="btn btn-danger btn-xs py-1 px-2 rounded-2" title="Decline"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: HR Shortcuts & Fast Track Actions -->
            <div class="col-12 col-lg-4">
                <div class="dashboard-card p-4 h-100">
                    <h3 class="h6 fw-bold text-dark mb-3">System Fast-Track Shortcuts</h3>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="#" class="shortcut-button">
                                <i class="bi bi-person-fill-add"></i>
                                <span class="small fw-semibold">Onboard Staff</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="shortcut-button">
                                <i class="bi bi-calendar3"></i>
                                <span class="small fw-semibold">Roster Sched</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="shortcut-button">
                                <i class="bi bi-credit-card-2-front-fill"></i>
                                <span class="small fw-semibold">Payslip Run</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="shortcut-button">
                                <i class="bi bi-shield-check"></i>
                                <span class="small fw-semibold">Audit Records</span>
                            </a>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light w-100 border text-muted small py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-sliders"></i> Customize Shortcuts Panel
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js Engine CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Render Graphs Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Chart 1: Attendance Trends Line Chart ---
            const ctxTrend = document.getElementById('attendanceTrendChart').getContext('2d');
            const attendanceChart = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: ['Week 18', 'Week 19', 'Week 20', 'Week 21', 'Week 22', 'Week 23', 'Week 24'],
                    datasets: [
                        {
                            label: 'Attendance Rate %',
                            data: [93.8, 94.5, 92.1, 95.4, 94.2, 94.8, 96.1],
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointBackgroundColor: '#0d6efd',
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Policy Compliance Target',
                            data: [95.0, 95.0, 95.0, 95.0, 95.0, 95.0, 95.0],
                            borderColor: '#cbd5e1',
                            borderDash: [5, 5],
                            fill: false,
                            borderWidth: 1.5,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            min: 90,
                            max: 100,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // --- Chart 2: Total Employees per Employment Status (Pie Chart) ---
            const ctxStatus = document.getElementById('statusDistributionChart').getContext('2d');
            const statusChart = new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: ['Regular Full-Time', 'Probationary', 'Project-Based', 'Part-Time'],
                    datasets: [{
                        data: [168, 42, 28, 10],
                        backgroundColor: [
                            '#0d6efd', // Primary corporate blue
                            '#60a5fa', // Soft light blue
                            '#38bdf8', // Sky blue
                            '#cbd5e1'  // Neutral grey
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Generate Custom Clean Legend for Employment Status
            const statusLegend = document.getElementById('statusLegend');
            statusChart.data.labels.forEach((label, i) => {
                const colors = statusChart.data.datasets[0].backgroundColor;
                const value = statusChart.data.datasets[0].data[i];
                statusLegend.innerHTML += `
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.725rem;">
                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:${colors[i]}"></span>
                        <span>${label} (${value})</span>
                    </div>
                `;
            });

            // --- Chart 3: DTR Today Summary (Pie Chart) ---
            const ctxDtr = document.getElementById('dtrSummaryChart').getContext('2d');
            const dtrChart = new Chart(ctxDtr, {
                type: 'pie',
                data: {
                    labels: ['Present', 'Absent', 'On Leave'],
                    datasets: [{
                        data: [225, 8, 15],
                        backgroundColor: [
                            '#198754', // Green
                            '#dc3545', // Red
                            '#ffc107'  // Warning Orange
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Generate Custom Clean Legend for DTR Summary
            const dtrLegend = document.getElementById('dtrLegend');
            dtrChart.data.labels.forEach((label, i) => {
                const colors = dtrChart.data.datasets[0].backgroundColor;
                const value = dtrChart.data.datasets[0].data[i];
                dtrLegend.innerHTML += `
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.725rem;">
                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:${colors[i]}"></span>
                        <span>${label} (${value})</span>
                    </div>
                `;
            });

        });
    </script>
