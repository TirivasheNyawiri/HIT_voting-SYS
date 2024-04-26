<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            margin-bottom: 20px;
        }
        .chart-title {
            text-align: center;
            margin-bottom: 10px;
        }
        .winner {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php
        // Assuming db_connect.php is included and $conn is your database connection
        include('db_connect.php');

        // Fetch voting data
        $voting = $conn->query("SELECT * FROM voting_list where is_default = 1");
        foreach ($voting->fetch_array() as $key => $value) {
            $$key = $value;
        }

        // Fetch votes
        $votes = $conn->query("SELECT * FROM votes where voting_id = $id");
        $v_arr = array();
        while ($row = $votes->fetch_assoc()) {
            if (!isset($v_arr[$row['voting_opt_id']]))
                $v_arr[$row['voting_opt_id']] = 0;

            $v_arr[$row['voting_opt_id']] += 1;
        }

        // Fetch options
        $opts = $conn->query("SELECT * FROM voting_opt where voting_id=" . $id);
        $opt_arr = array();
        while ($row = $opts->fetch_assoc()) {
            $opt_arr[$row['category_id']][] = $row;
        }

        // Prepare chart data and calculate winners
        $cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '" . $id . "')");
        $chartData = [];

        while ($row = $cats->fetch_assoc()) {
            $category = $row['category'];
            $categoryId = $row['id'];
            $candidates = $opt_arr[$categoryId];
            $categoryData = [];
            $maxVotes = 0;
            $winner = '';
            $winnerVotes = 0;

            foreach ($candidates as $candidate) {
                $candidateName = $candidate['opt_txt'];
                $votes = isset($v_arr[$candidate['id']]) ? $v_arr[$candidate['id']] : 0;
                $categoryData[] = ['label' => $candidateName, 'value' => $votes];
                if ($votes > $maxVotes) {
                    $maxVotes = $votes;
                    $winner = $candidateName;
                    $winnerVotes = $votes;
                }
            }

            $chartData[] = ['category' => $category, 'data' => $categoryData, 'winner' => $winner, 'winnerVotes' => $winnerVotes];
        }
        ?>

        <?php foreach ($chartData as $index => $categoryData): ?>
            <div class="chart-container">
                <h3 class="chart-title"><?php echo $categoryData['category']; ?></h3>
                <div class="winner"><?php echo "Winner: " . $categoryData['winner'] . " with " . $categoryData['winnerVotes'] . " votes"; ?></div>
                <canvas id="chart-<?php echo $index; ?>"></canvas>
            </div>
        <?php endforeach; ?>

       
        <script>
    <?php foreach ($chartData as $index => $categoryData): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('chart-<?php echo $index; ?>').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($categoryData['data'], 'label')); ?>,
                    datasets: [{
                        label: 'Votes',
                        data: <?php echo json_encode(array_column($categoryData['data'], 'value')); ?>,
                        backgroundColor: function(context) {
                            var gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, 'rgba(0, 0, 0, 0.5)'); // Darker gradient for bars
                            gradient.addColorStop(1, 'rgba(0, 0, 0, 0.3)');
                            return gradient;
                        },
                        borderColor: 'rgba(0, 0, 0, 1)', // Darker border color for bars
                        borderWidth: 5
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)', // Light grey grid lines
                                lineWidth: 1 // Increase y-axis line thickness
                            },
                            ticks: {
                                stepSize: 1, // Ensure only whole numbers are shown
                                font: {
                                    weight: 'bold' // Make y-axis labels bold
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false, // Hide grid lines for x-axis
                                lineWidth: 1 // Increase x-axis line thickness
                            },
                            ticks: {
                                font: {
                                    weight: 'bold' // Make x-axis labels bold
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide legend
                        }
                    }
                }
            });
        });
    <?php endforeach; ?>
</script>


    </div>
</body>
</html>
