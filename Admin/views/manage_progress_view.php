<?php
// Default values for startDate, endDate, and selected filters
$startDate = "";
$endDate = date("Y-m-d 23:59:59"); // End of the current day
$selectedFilterDate = "";
$selectedFilterProg = "";
$filterProg = ""; // Initialize $filterProg

// Check if date filter is applied
if (isset($_GET['filterDate'])) {
    $filterDate = $_GET['filterDate'];
    $selectedFilterDate = $filterDate; // Store selected filter date

    // Determine the start and end dates based on the selected filter option
    if ($filterDate == date("Y-m-d")) {
        $startDate = date("Y-m-d");
    } elseif ($filterDate == date('Y-m-d', strtotime('-7 days'))) {
        $startDate = date('Y-m-d', strtotime('-7 days'));
    } elseif ($filterDate == date('Y-m-d', strtotime('-30 days'))) {
        $startDate = date('Y-m-d', strtotime('-30 days'));
    } elseif ($filterDate == date('Y-m-d', strtotime('-365 days'))) {
        $startDate = date('Y-m-d', strtotime('-365 days'));
    } elseif ($filterDate == "2020-01-01") {
        $startDate = "2020-01-01";
    }

    // Adjust endDate for the selected date filter
    $endDate = date("Y-m-d 23:59:59");
}

// Check if progress filter is applied
if (isset($_GET['filterprog'])) {
    $filterProg = $_GET['filterprog']; // Store selected filter progress
    $selectedFilterProg = $filterProg; // Store selected filter progress
}

// Mendapatkan data dengan filter
$arry = $obj->getFilteredProgress($filterProg, $startDate, $endDate);

// Calculate the total count
$totalCount = count($arry); // Assuming $arry is the array of table data
?>


<!-- HTML code remains the same -->


<div class='container'>
    <h2>History Progress Customer</h2>
    <!-- ... (rest of your code) ... -->

    <h4 class='text-success'>
        <?php if (isset($del_msg)) echo $del_msg; ?>
    </h4>
    

    <div class="mydiv">
        <form action="" method="get" class="form">
            <div class="form-group">
                <label for="filterDate">Filter by Date</label>
                <select name="filterDate" id="filterDate" class="form-control">
                    <option value="">Select Date</option> <!-- Placeholder option -->
                    <option value="<?php echo date("Y-m-d") ?>" <?php if ($selectedFilterDate == date("Y-m-d")) echo 'selected'; ?>>Today</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-7 days')) ?>" <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-7 days'))) echo 'selected'; ?>>This week</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-30 days')) ?>" <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-30 days'))) echo 'selected'; ?>>This Month</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-365 days')) ?>" <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-365 days'))) echo 'selected'; ?>>This Year</option>
                    <option value="2020-01-01" <?php if ($selectedFilterDate == "2020-01-01") echo 'selected'; ?>>Life Time</option>
                </select>
            </div>

            <div class="form-group">
                <label for="filterprog">Filter by Progress</label>
                <select name="filterprog" id="filterprog" class="form-control">
                    <option value="to Hot" <?php if ($selectedFilterProg == "to Hot") echo 'selected'; ?>>Hot</option>
                    <option value="to Warm" <?php if ($selectedFilterProg == "to Warm") echo 'selected'; ?>>Warm</option>
                    <option value="to Cold" <?php if ($selectedFilterProg == "to Cold") echo 'selected'; ?>>Cold</option>
                    <option value="to New Leads" <?php if ($selectedFilterProg == "to New Leads") echo 'selected'; ?>>New Leads</option>
                    <option value="to Follow Ups" <?php if ($selectedFilterProg == "to Follow Ups") echo 'selected'; ?>>Follow Ups</option>
                    <option value="to Offering" <?php if ($selectedFilterProg == "to Offering") echo 'selected'; ?>>Offering</option>
                    <option value="to Closed-Won" <?php if ($selectedFilterProg == "to Closed-Won") echo 'selected'; ?>>Closed-Won</option>
                    <option value="to Closed-Lose" <?php if ($selectedFilterProg == "to Closed-Lose") echo 'selected'; ?>>Closed-Lose</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>
    </div>

    <div class='table-responsive'>
        <table class='table'>
        <thead>
    <tr>
        <th>User ID</th>
        <th>Activity Type</th>
        <th>Activity Details</th>
        <th>Waktu</th>
        <th>Action</th>
    </tr>
        </thead>
        <tbody>
            <?php foreach ($arry as $progress) { ?>
            <tr>
                <td> <?php echo htmlspecialchars($progress['user_id']); ?> </td>
                <td> <?php echo htmlspecialchars($progress['activity_type']); ?> </td>
                <td> <?php echo htmlspecialchars($progress['activity_details']); ?> </td>
                <td> <?php echo htmlspecialchars($progress['log_time']); ?> </td>
                <td>
                    <a href="?status=delete&&id=<?php echo urlencode($progress['id']); ?>" class='btn btn-sm btn-danger'>Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">
                    Total Records: <?php echo $totalCount; ?>
                </td>
            </tr>
        </tfoot>
        </table>
    </div>
</div>
