<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用戶儀表板</title>
    <!-- 加入 Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>歡迎, {{ $user }}!</h2>

    <!-- 登出按鈕 -->
    <a href="/logout">登出</a>

    <!-- 刪除帳號按鈕 -->
    <a href="#" onclick="if(confirm('確定要刪除帳號嗎？此操作無法復原！')) { document.getElementById('delete-account-form').submit(); }" style="color: red; margin-left: 10px;">
        刪除帳號
    </a>

    <form id="delete-account-form" method="POST" action="/delete-account" style="display: none;">
        @csrf
    </form>

    <hr>

    <!-- 日誌監控區塊 -->
    <h1>系統日誌監控</h1>
    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
        <button onclick="fetchLogs()">載入日誌</button>
        <p id="log-count" style="margin: 0;">日誌數量：0</p>
    </div>
    <pre id="log-output"></pre>

    <h3>圖表：</h3>
    <canvas id="logChart" width="600" height="400"></canvas>

    <script>
        let chart;

        function updateChart(counts) {
            const ctx = document.getElementById('logChart').getContext('2d');
            if(chart) {
                chart.data.datasets[0].data = [counts.ERROR, counts.CRITICAL, counts.INFO];
                chart.update();
            } else {
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['ERROR', 'CRITICAL', 'INFO'],
                        datasets: [{
                            label: '日誌類型數量',
                            data: [counts.ERROR, counts.CRITICAL, counts.INFO],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(54, 162, 235, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        }

        function fetchLogs() {
            fetch('/logs')
                .then(response => response.json())
                .then(data => {
                    console.log("收到日誌資料：", data);
                    const logs = data.logs;
                    document.getElementById("log-output").textContent = logs.join("\n");
                    document.getElementById("log-count").textContent = "日誌數量：" + logs.length;

                    const counts = { ERROR: 0, CRITICAL: 0, INFO: 0 };
                    logs.forEach(line => {
                        if(line.includes("ERROR")) counts.ERROR++;
                        if(line.includes("CRITICAL")) counts.CRITICAL++;
                        if(line.includes("INFO")) counts.INFO++;
                    });
                    updateChart(counts);
                })
                .catch(error => console.error("載入日誌失敗！", error));
        }
    </script>
</body>
</html>

