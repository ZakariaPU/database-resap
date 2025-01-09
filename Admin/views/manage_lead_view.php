<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Management System</title>
    <style>
    h1 {
        font-size: 30px;
    }

    h3 {
        font-size: 20px;
    }

    .lead_title {
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section {
        flex: 1 1 300px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
        margin-bottom: 16px;
    }

    .container {
        display: contents;
        gap: 16px;
        padding: 20px;
    }

    .lead {
        border-radius: 8px;
        padding: 16px;
        border: 1px solid #ccc;
        margin-bottom: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .action {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
    }

    .action:hover {
        background-color: #0056b3;
    }

    .lead-option {
        margin-left: 8px;
    }

    .lead-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    </style>
</head>

<body>

    <header>
        <h1 class="lead_title">Lead Management System</h1>
        <div class="actions">
        </div>
    </header>

    <main class="container">
        <?php

    $sections = ['New Leads', 'Follow ups', 'Offering', 'Closed-Won', 'Closed-Lose'];

    // Process change lead progress
    if (isset($_POST['update_lead'])) {
        $cust_id = $_POST['cust_id'];
        $new_progress = $_POST['new_progress'];

        if (in_array($new_progress, $sections)) {
          $lead_status = $obj-> update_lead($cust_id, $new_progress);
          
        } else {
            echo '<p>Invalid progress value selected.</p>';
        }
    }

    foreach ($sections as $section) {
        echo '<section class="section">';
        echo '<h3>' . htmlspecialchars($section) . '</h3>';

        $lead_data = $obj->show_lead_detail($section);

        if ($lead_data->num_rows > 0) {
            while ($row = $lead_data->fetch_assoc()) {
                echo '<div class="lead">';

                echo '<div class="lead-info">';
                echo '<span>' . htmlspecialchars($row['nama_entitas']) . '</span>';
                echo '<span class="actions">';
                
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="cust_id" value="' . htmlspecialchars($row['cust_id']) . '">';
                
                echo '<div class="lead_option">';
                echo '<select name="new_progress">';
                foreach ($sections as $progress_option) {
                    echo '<option value="' . htmlspecialchars($progress_option) . '"' . ($progress_option == $section ? ' selected' : '') . '>' . htmlspecialchars($progress_option) . '</option>';}
                echo '</select>';
                echo '<button type="submit" name="update_lead" class="action">Move</button>';
                echo '</div>';


                echo '</form>';
                echo '</span>';
                echo '</div>';
                echo '<p>' . htmlspecialchars($row['nama_pic']) . ' - ' . htmlspecialchars($row['phone_number_pic']) . '</p>';
                echo '<p>' . htmlspecialchars($row['prog_date']);
                echo '</div>';
            }
        } else {
            echo '<p>No leads found for this section.</p>';
        }
        echo '</section>';
    }
    ?>
    </main>

</body>

</html>

</html>