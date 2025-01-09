<?php
// Default values for startDate, endDate, and selected filters
$startDate = '';
$endDate = date( 'Y-m-d 23:59:59' );
// End of the current day
$selectedFilterDate = '';
$selectedFilterProg = '';
$filterProg = '';
// Initialize $filterProg

// Check if date filter is applied
if ( isset( $_GET[ 'filterDate' ] ) ) {
    $filterDate = $_GET[ 'filterDate' ];
    $selectedFilterDate = $filterDate;
    // Store selected filter date

    // Determine the start and end dates based on the selected filter option
    if ( $filterDate == date( 'Y-m-d' ) ) {
        $startDate = date( 'Y-m-d' );
    } elseif ( $filterDate == date( 'Y-m-d', strtotime( '-7 days' ) ) ) {
        $startDate = date( 'Y-m-d', strtotime( '-7 days' ) );
    } elseif ( $filterDate == date( 'Y-m-d', strtotime( '-30 days' ) ) ) {
        $startDate = date( 'Y-m-d', strtotime( '-30 days' ) );
    } elseif ( $filterDate == date( 'Y-m-d', strtotime( '-365 days' ) ) ) {
        $startDate = date( 'Y-m-d', strtotime( '-365 days' ) );
    } elseif ( $filterDate == '2020-01-01' ) {
        $startDate = '2020-01-01';
    }

    // Adjust endDate for the selected date filter
    $endDate = date( 'Y-m-d 23:59:59' );
}

// Check if progress filter is applied
if ( isset( $_GET[ 'filterprog' ] ) ) {
    $filterProg = $_GET[ 'filterprog' ];
    // Store selected filter progress
    $selectedFilterProg = $filterProg;
    // Store selected filter progress
}

// Mendapatkan data dengan filter
$arry = $obj->getFilteredProgressStock( $filterProg, $startDate, $endDate );

// Calculate the total count
$totalCount = count( $arry );
// Assuming $arry is the array of table data
?>

<!-- HTML code remains the same -->

<div class = 'container'>
    <h2>History Progress Stock</h2>
    <!-- ... ( rest of your code ) ... -->
     <h4 class = 'text-success'>
        <?php if ( isset( $del_msg ) ) echo $del_msg;
        ?>
    </h4>
    <div class = 'table-responsive'>
        <table class = 'table'>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Activity Type</th>
                    <th>Activity Details</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $arry as $progress ) {
                    ?>
                <tr>
                    <td> <?php echo htmlspecialchars( $progress[ 'user_id' ] ); ?> </td>
                    <td> <?php echo htmlspecialchars( $progress[ 'activity_type' ] ); ?> </td>
                    <td> <?php echo htmlspecialchars( $progress[ 'activity_details' ] ); ?> </td>
                    <td> <?php echo htmlspecialchars( $progress[ 'log_time' ] ); ?> </td>
                </tr>
                <?php }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan = '5' class = 'text-right'>
                        Total Records: <?php echo $totalCount;
                        ?>
                        </td>
                    </tr>
            </tfoot>
        </table>
    </div>
</div>