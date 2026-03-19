<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamshedpur Public School</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Book List</title>
</head>

<body>
    <?php include('navbar.php'); ?>
    <h2 class="section-title">Book List</h2>

    <table class="result-table">
        <tr>
            <th>Class</th>
            <th>Uploaded Date</th>
            <th>Download</th>
        </tr>
        <tbody id="book-list">

        </tbody>
    </table>

    <?php include('footer.php'); ?>

    <script>
        function loadBooklist() {

            $.ajax({
                url: 'ajax_booklist.php',
                method: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $("#book-list").html(data.booklist);
                },

                error: function(xhr) {
                    console.log("AJAX Error:");
                    console.log(xhr.responseText);
                }
            });
        }
        loadBooklist();
        setInterval(loadBooklist, 5000);
    </script>
</body>

</html>