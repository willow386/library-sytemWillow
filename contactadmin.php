<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <<link rel="stylesheet" href="style.css" />
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
<?php include 'sidebar.php'; ?>
    <main class="content">
  <h2 class="button-like">Contact Admin</h2>
  <form class="contact-form">
    <div class="input-group">
      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" required />
    </div>
    <div class="input-group">
      <label for="message">Message</label>
      <textarea id="message" rows="5" required></textarea>
    </div>
    <button class="btn" type="submit">Send Message</button>
  </form>
</main>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }
</script>
</body>
</html>