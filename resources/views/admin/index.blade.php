<x-dashboard-layout>
    <style>
        .admin-dashboard-header h3 {
            color: #FFFFFF;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .admin-dashboard-header p {
            color: #94A3B8;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .kpi-card {
            background-color: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            padding: 28px;
            margin-bottom: 24px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .kpi-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.35);
        }

        .kpi-card-top-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 4px 4px 0 0;
        }
        
        .kpi-card-top-line.users { background: linear-gradient(90deg,#3B82F6,#60A5FA); }
        .kpi-card-top-line.countries { background: linear-gradient(90deg,#22C55E,#4ADE80); }
        .kpi-card-top-line.ports { background: linear-gradient(90deg,#F59E0B,#FBBF24); }
        .kpi-card-top-line.articles { background: linear-gradient(90deg,#A855F7,#C084FC); }

        .kpi-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .kpi-icon {
            font-size: 30px;
        }

        .kpi-title {
            color: #94A3B8;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .kpi-number {
            color: #FFFFFF;
            font-size: 40px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Quick Stats Section */
        .quick-stats-card {
            background-color: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
        }
        
        .quick-stats-card h4 {
            color: #CBD5E1;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .stat-row {
            margin-bottom: 20px;
        }
        .stat-row:last-child {
            margin-bottom: 0;
        }

        .stat-label {
            display: flex;
            justify-content: space-between;
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .progress-modern {
            background: rgba(255,255,255,0.05);
            height: 8px;
            border-radius: 8px;
            overflow: hidden;
        }
        .progress-modern-bar {
            height: 100%;
            border-radius: 8px;
        }
    </style>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12 admin-dashboard-header">
                <h3>Welcome Administrator</h3>
                <p>GERIP Administration Panel</p>
            </div>
        </div>

        <!-- KPI Cards Section -->
        <div class="row">
            <!-- Users -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-card-top-line users"></div>
                    <div class="kpi-header">
                        <div class="kpi-icon" style="color: #3B82F6;">👤</div>
                        <h6 class="kpi-title">Total Users</h6>
                    </div>
                    <h2 class="kpi-number">{{ number_format($stats['total_users'] ?? 0) }}</h2>
                </div>
            </div>

            <!-- Countries -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-card-top-line countries"></div>
                    <div class="kpi-header">
                        <div class="kpi-icon" style="color: #22C55E;">🌍</div>
                        <h6 class="kpi-title">Countries</h6>
                    </div>
                    <h2 class="kpi-number">{{ number_format($stats['total_countries'] ?? 0) }}</h2>
                </div>
            </div>

            <!-- Ports -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-card-top-line ports"></div>
                    <div class="kpi-header">
                        <div class="kpi-icon" style="color: #F59E0B;">⚓</div>
                        <h6 class="kpi-title">Ports</h6>
                    </div>
                    <h2 class="kpi-number">{{ number_format($stats['total_ports'] ?? 0) }}</h2>
                </div>
            </div>

            <!-- Articles -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card">
                    <div class="kpi-card-top-line articles"></div>
                    <div class="kpi-header">
                        <div class="kpi-icon" style="color: #A855F7;">📰</div>
                        <h6 class="kpi-title">Analysis Articles</h6>
                    </div>
                    <h2 class="kpi-number">{{ number_format($stats['total_articles'] ?? 0) }}</h2>
                </div>
            </div>
        </div>

        <!-- Quick Statistics Section -->
        <div class="row">
            <div class="col-12">
                <div class="quick-stats-card">
                    <h4>Quick Statistics</h4>
                    
                    <div class="stat-row">
                        <div class="stat-label">
                            <span>Total Registered Users</span>
                            <span>{{ number_format($stats['total_users'] ?? 0) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern-bar" style="width: 75%; background: linear-gradient(90deg,#3B82F6,#60A5FA);"></div>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-label">
                            <span>Active Countries</span>
                            <span>{{ number_format($stats['total_countries'] ?? 0) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern-bar" style="width: 100%; background: linear-gradient(90deg,#22C55E,#4ADE80);"></div>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-label">
                            <span>Total Ports Dataset</span>
                            <span>{{ number_format($stats['total_ports'] ?? 0) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern-bar" style="width: 85%; background: linear-gradient(90deg,#F59E0B,#FBBF24);"></div>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-label">
                            <span>Published Articles</span>
                            <span>{{ number_format($stats['total_articles'] ?? 0) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern-bar" style="width: 40%; background: linear-gradient(90deg,#A855F7,#C084FC);"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
