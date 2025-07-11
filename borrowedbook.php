<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css" />

</head>
<body>
    <?php include 'sidebar.php'; ?>
<main class = "content">
    <h2 class ="button-like">Your Borrowed Book</h2>
    <table class ="data-table">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Copies</th>
                <th>Date Borrowed</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
       </thead>
       <tbody>
        <tr>
            <td>The Alchemist</td>
            <td>John c. Maxwell</td>
            <td>225-1-201</td>
            <td> 1</td>
            <td>07-07-2025</td>
            <td>10-07-2025</td>
            <td><span class="status due">Due Soon</span></td>
        </tr>
     </tbody>
</table>
</main>
</body>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }
</script>
</body>
</html>
