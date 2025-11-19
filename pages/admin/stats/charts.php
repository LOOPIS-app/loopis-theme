<?php
/**
 * Statistics with charts.
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 * Will be improved to use reusable scripts.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Diagram</h1>
<hr>
<p class="small">💡 Diagram för annonser, paxningar och nya medlemmar.</p>
<?php

global $wpdb;

// Get the date of the first post and first user registration
$first_post_date = $wpdb->get_var("SELECT MIN(post_date) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish'");
$first_user_date = $wpdb->get_var("SELECT MIN(user_registered) FROM $wpdb->users");

// Determine the earliest start date
$start_date = min($first_post_date, $first_user_date);

// Set fixed start date instead?
// $start_date = '2024-01-01 00:00:00';

// Get the current date as the end date
$end_date = current_time('Y-m-d H:i:s');

// Set excluded user ID's
$excluded_user_ids = [1, 2, 3, 66];
$excluded_user_ids = array_map('intval', $excluded_user_ids);


// Get weekly counts for posts
$query = "
    SELECT 
        DATE_FORMAT(post_date, '%Y-%u') AS week,
        COUNT(ID) AS post_count
    FROM 
        $wpdb->posts
    WHERE 
        post_date >= %s AND post_type = 'post' AND post_status = 'publish'
        AND post_author NOT IN (" . implode(',', $excluded_user_ids) . ")
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

$post_results = $wpdb->get_results($wpdb->prepare($query, $start_date));

// Get weekly counts for new users
$query = "
    SELECT 
        DATE_FORMAT(user_registered, '%Y-%u') AS week,
        COUNT(ID) AS user_count
    FROM 
        $wpdb->users
    WHERE 
        user_registered >= %s
        AND ID NOT IN (1, 11, 66)
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

$user_results = $wpdb->get_results($wpdb->prepare($query, $start_date));

// Get weekly counts for book_date
$query = "
    SELECT 
        DATE_FORMAT(pm.meta_value, '%Y-%u') AS week,
        COUNT(pm.post_id) AS book_count
    FROM 
        $wpdb->postmeta pm
    JOIN 
        $wpdb->posts p ON pm.post_id = p.ID
    WHERE 
        pm.meta_key = 'book_date' AND pm.meta_value IS NOT NULL
        AND p.post_author NOT IN (" . implode(',', $excluded_user_ids) . ")
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

$book_date_results = $wpdb->get_results($wpdb->prepare($query, $start_date));

// Get weekly counts for posts with book_date
$query = "
    SELECT 
        DATE_FORMAT(p.post_date, '%Y-%u') AS week,
        COUNT(p.ID) AS post_count
    FROM 
        $wpdb->posts p
    INNER JOIN 
        $wpdb->postmeta pm ON p.ID = pm.post_id
    WHERE 
        p.post_date >= %s 
        AND p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm.meta_key = 'book_date'
        AND pm.meta_value IS NOT NULL
        AND p.post_author NOT IN (" . implode(',', $excluded_user_ids) . ")
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

$booked_results = $wpdb->get_results($wpdb->prepare($query, $start_date));

// Get weekly counts for posts with book_date within a week after post_date
$query = "
    SELECT 
        DATE_FORMAT(p.post_date, '%Y-%u') AS week,
        COUNT(p.ID) AS post_count
    FROM 
        $wpdb->posts p
    INNER JOIN 
        $wpdb->postmeta pm ON p.ID = pm.post_id
    WHERE 
        p.post_date >= %s 
        AND p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm.meta_key = 'book_date'
        AND DATE(pm.meta_value) BETWEEN DATE(p.post_date) AND DATE_ADD(DATE(p.post_date), INTERVAL 7 DAY)
        AND p.post_author NOT IN (" . implode(',', $excluded_user_ids) . ")
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

$quickbooked_results = $wpdb->get_results($wpdb->prepare($query, $start_date));


// Prepare data for the combined chart
// Initialize arrays for cumulative and weekly counts
$cumulative_counts = [
    'posts' => 0,
    'users' => 0,
    'book_date' => 0,
    'booked' => 0,
	'quickbooked' => 0
];

$weekly_counts = [
    'posts' => [],
    'users' => [],
    'book_date' => [],
    'booked' => [],
	'quickbooked' => []
];

$cumulative_results = [
    'posts' => [],
    'users' => [],
    'book_date' => [],
    'booked' => [],
	'quickbooked' => []
];

$weeks = [];

// Function to calculate weekly and cumulative counts
function calculate_counts($results, $week, &$cumulative_count, &$cumulative_results, &$weekly_counts, $count_key) {
    $count = 0;
    foreach ($results as $result) {
        if ($result->week === $week) {
            $count = $result->$count_key;
            break;
        }
    }
    $cumulative_count += $count;
    $cumulative_results[] = $cumulative_count;
    $weekly_counts[] = $count;
}

// Create an array for each week from the earliest start date to the current date
$start = strtotime($start_date);
while ($start <= strtotime($end_date)) {
    $week = date('Y-W', $start);
    $weeks[] = $week;

    calculate_counts($post_results, $week, $cumulative_counts['posts'], $cumulative_results['posts'], $weekly_counts['posts'], 'post_count');
    calculate_counts($user_results, $week, $cumulative_counts['users'], $cumulative_results['users'], $weekly_counts['users'], 'user_count');
    calculate_counts($book_date_results, $week, $cumulative_counts['book_date'], $cumulative_results['book_date'], $weekly_counts['book_date'], 'book_count');
    calculate_counts($booked_results, $week, $cumulative_counts['booked'], $cumulative_results['booked'], $weekly_counts['booked'], 'post_count');
	calculate_counts($quickbooked_results, $week, $cumulative_counts['quickbooked'], $cumulative_results['quickbooked'], $weekly_counts['quickbooked'], 'post_count');

    $start = strtotime('+1 week', $start);
}

// Calculate weekly percentages
$weekly_percentages_quickbooked = [];
$weekly_percentages_booked = [];

foreach ($weeks as $week) {
    $post_count = 0;
    $booked_count = 0;
    $quickbooked_count = 0;	

    // Find the post count for the week
    foreach ($post_results as $result) {
        if ($result->week === $week) {
            $post_count = $result->post_count;
            break;
        }
    }

    // Find the booked count for the week
    foreach ($booked_results as $result) {
        if ($result->week === $week) {
            $booked_count = $result->post_count;
            break;
        }
    }
	
	// Find the quickbooked count for the week
    foreach ($quickbooked_results as $result) {
        if ($result->week === $week) {
            $quickbooked_count = $result->post_count;
            break;
        }
    }



    // Calculate quickbooked percentage and format to two decimals
    $percentage_quickbooked = ($post_count > 0) ? ($quickbooked_count / $post_count) * 100 : 0;
    $weekly_percentages_quickbooked[] = number_format($percentage_quickbooked, 2);

    // Calculate booked percentage and format to two decimals
    $percentage_booked = ($post_count > 0) ? ($booked_count / $post_count) * 100 : 0;
    $weekly_percentages_booked[] = number_format($percentage_booked, 2);
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--CHART 1 OUTPUT-->
<div class="columns"><div class="column1"><h3>📊 Veckovis</h3></div>
<div class="column2 bottom small"><a href="#" id="download-weeklyChart">📄WeeklyChart.csv</a></div></div>
<hr>
<canvas id="weeklyChart" width="400" height="200"></canvas>

<script>
// Weekly Chart
const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
const weeklyChart = new Chart(ctxWeekly, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($weeks); ?>,
        datasets: [
            {
                label: 'Annonser',
                data: <?php echo json_encode($weekly_counts['posts']); ?>,
                borderColor: '#78B159',
                backgroundColor: 'rgba(120, 177, 89, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Paxade',
                data: <?php echo json_encode($weekly_counts['booked']); ?>,
                borderColor: '#FF969D',
                backgroundColor: 'rgba(255, 150, 157, 0.2)',
                borderWidth: 1,
            },
            {
                label: 'Snabbpaxade',
                data: <?php echo json_encode($weekly_counts['quickbooked']); ?>,
                borderColor: '#FFBC82',
                backgroundColor: 'rgba(255, 188, 130, 0.2)',
                borderWidth: 1,
                hidden: true
            },
			{
                label: 'Paxningar',
                data: <?php echo json_encode($weekly_counts['book_date']); ?>,
                borderColor: '#FF969D',
				backgroundColor: 'rgba(255, 150, 157, 0.2)',
                borderWidth: 1,
				hidden: true
            },
            {
                label: 'Medlemmar',
                data: <?php echo json_encode($weekly_counts['users']); ?>,
                borderColor: '#6dadde',
                backgroundColor: 'rgba(150, 210, 255, 0.2)',
                borderWidth: 1,
                hidden: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
            }
        }
    }
});
// Download CSV for Weekly Chart
document.getElementById('download-weeklyChart').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Prepare CSV data
    const labels = <?php echo json_encode($weeks); ?>;
    const datasets = [
        { label: 'Annonser', data: <?php echo json_encode($weekly_counts['posts']); ?> },
        { label: 'Paxade', data: <?php echo json_encode($weekly_counts['booked']); ?> },
        { label: 'Snabbpaxade', data: <?php echo json_encode($weekly_counts['quickbooked']); ?> },
		{ label: 'Paxningar', data: <?php echo json_encode($weekly_counts['book_date']); ?> },
        { label: 'Medlemmar', data: <?php echo json_encode($weekly_counts['users']); ?> }
    ];

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Week," + datasets.map(ds => ds.label).join(",") + "\n";

    labels.forEach((label, index) => {
        let row = [label];
        datasets.forEach(ds => {
            row.push(ds.data[index]);
        });
        csvContent += row.join(",") + "\n";
    });

    // Create a link and trigger the download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);

    // Get the current date
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Format as "year-month-day"

    link.setAttribute("download", `${formattedDate} WeeklyChart.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>

<!--CHART 2 OUTPUT-->
<div class="columns">
    <div class="column1"><h3>♻ Veckovis (%)</h3></div>
    <div class="column2 bottom small"><a href="#" id="download-percentageChart">📄PercentageChart.csv</a></div>
</div>
<hr>
<canvas id="percentageChart" width="400" height="200"></canvas>

<script>
// Percentage Chart
const ctxPercentage = document.getElementById('percentageChart').getContext('2d');
const percentageChart = new Chart(ctxPercentage, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($weeks); ?>,
        datasets: [
            {
                label: 'Paxade (%)',
                data: <?php echo json_encode($weekly_percentages_booked); ?>,
                borderColor: '#FF969D',
                backgroundColor: 'rgba(255, 150, 157, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Snabbpaxade (%)',
                data: <?php echo json_encode($weekly_percentages_quickbooked); ?>,
                borderColor: '#FFBC82',
                backgroundColor: 'rgba(255, 188, 130, 0.2)',
                borderWidth: 1,
                hidden: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
// Download CSV for Percentage Chart
document.getElementById('download-percentageChart').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Prepare CSV data
    const labels = <?php echo json_encode($weeks); ?>;
    const datasets = [
        { label: '% Paxade', data: <?php echo json_encode($weekly_percentages_booked); ?> },
        { label: '% Snabbpaxade', data: <?php echo json_encode($weekly_percentages_quickbooked); ?> }
    ];

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Week," + datasets.map(ds => ds.label).join(",") + "\n";

    labels.forEach((label, index) => {
        let row = [label];
        datasets.forEach(ds => {
            row.push(ds.data[index]);
        });
        csvContent += row.join(",") + "\n";
    });

    // Create a link and trigger the download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);

    // Get the current date
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Format as "year-month-day"

    link.setAttribute("download", `${formattedDate} PercentageChart.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>

<!--CHART 3 OUTPUT-->
<div class="columns">
    <div class="column1"><h3>📈 Ackumulerat</h3></div>
    <div class="column2 bottom small"><a href="#" id="download-cumulativeChart">📄CumulativeChart.csv</a></div>
</div>
<hr>
<canvas id="cumulativeChart" width="400" height="200"></canvas>

<script>
// Cumulative Chart
const ctxCumulative = document.getElementById('cumulativeChart').getContext('2d');
const cumulativeChart = new Chart(ctxCumulative, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($weeks); ?>,
        datasets: [
            {
                label: 'Annonser',
                data: <?php echo json_encode($cumulative_results['posts']); ?>,
                borderColor: '#78B159',
                backgroundColor: 'rgba(120, 177, 89, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Paxningar',
                data: <?php echo json_encode($cumulative_results['book_date']); ?>,
                borderColor: '#FF969D',
                backgroundColor: 'rgba(255, 150, 157, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Snabbpaxade',
                data: <?php echo json_encode($cumulative_results['quickbooked']); ?>,
                borderColor: '#FFBC82',
                backgroundColor: 'rgba(255, 188, 130, 0.2)',
                borderWidth: 1,
                hidden: true
            },
            {
                label: 'Medlemmar',
                data: <?php echo json_encode($cumulative_results['users']); ?>,
                borderColor: '#6dadde',
                backgroundColor: 'rgba(150, 210, 255, 0.2)',
                borderWidth: 1,
                hidden: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
            }
        }
    }
});
document.getElementById('download-cumulativeChart').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Prepare CSV data
    const labels = <?php echo json_encode($weeks); ?>;
    const datasets = [
        { label: 'Annonser', data: <?php echo json_encode($cumulative_results['posts']); ?> },
        { label: 'Paxningar', data: <?php echo json_encode($cumulative_results['book_date']); ?> },
        { label: 'Snabbpaxade', data: <?php echo json_encode($cumulative_results['quickbooked']); ?> },
        { label: 'Medlemmar', data: <?php echo json_encode($cumulative_results['users']); ?> }
    ];

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Week," + datasets.map(ds => ds.label).join(",") + "\n";

    labels.forEach((label, index) => {
        let row = [label];
        datasets.forEach(ds => {
            row.push(ds.data[index]);
        });
        csvContent += row.join(",") + "\n";
    });

    // Create a link and trigger the download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);

    // Get the current date
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Format as "year-month-day"

    link.setAttribute("download", `${formattedDate} CumulativeChart.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>