<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include('navbar.php'); ?>

    <!-- TOPPERS -->
    <h2 class="section-title">Our Toppers</h2>

    <div class="topper-grid" id="topper-list">

    </div>

    <!-- RESULTS -->
    <h2 class="section-title">Student Results</h2>

    <table class="result-table">
        <tr>
            <th>Class</th>
            <th>Section</th>
            <th>Session</th>
            <th>Result</th>
        </tr>

        <tbody id="result-list">

        </tbody>
    </table>
    <?php include('footer.php'); ?>

    <script>
        function loadResults() {

            $.ajax({
                url: 'ajax_result.php',
                method: 'GET',
                dataType: 'json',
                cache: false,

                success: function(data) {

                    $("#topper-list").html(data.toppers);
                    $("#result-list").html(data.results);

                },

                error: function(xhr) {
                    console.log("AJAX Error:");
                    console.log(xhr.responseText);
                }

            });

        }

        loadResults();
        setInterval(loadResults, 5000);
    </script>
</body>

</html>