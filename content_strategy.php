<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crusadersignup";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert comment into the database
    $insert_comment_sql = $conn->prepare("INSERT INTO comments (comment, created_at) VALUES (?, NOW())");
    $insert_comment_sql->bind_param("s", $comment);

    if ($insert_comment_sql->execute()) {
        // Successfully inserted comment
        $comment_id = $insert_comment_sql->insert_id;
        $new_comment = [
            'comment' => $comment,
            'created_at' => 'Just now',
        ];
    } else {
        $new_comment = null;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Content Strategy</title>
  <link rel="icon" type="image/x-icon" href="image/fav.png">
  <link rel="stylesheet" href="Global.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">
  <style>
    .comments-container {
      padding: 20px;
      background-color: #f8f9fa;
      margin-top: 20px;
    }
    .comments-container h3 {
      margin-bottom: 20px;
    }
    .comments-container textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
    }
    .comments-container button {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
    }
    .comments-container .comment {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #e9ecef;
      border-radius: 5px;
    }
    .comments-container .comment span {
      display: block;
      font-size: 0.9em;
      color: #6c757d;
    }
  </style>
</head>
<body>
<header class="header">
  <div class="glass-container">
    <nav>
      <a href="Dashboard.html" class="logo"><img src="image/Logo.png" alt="Logo"></a>
      <div class="nav-center">
        <ul>
          <li><a href="Solutions.html">Solutions</a></li>
          <li><a href="Blog.html">Blog</a></li>
          <li><a href="About.html">About</a></li>
        </ul>
      </div>
      <div class="right-side">
        <div class="search-container">
          <input type="text" id="search-input" placeholder="Search here...">
          <button type="button" onclick="performSearch()">Search</button>
        </div>
        <div class="profile-icon" onclick="toggleDropdownMenu()">
          <img src="image/profile.png" alt="Profile" id="profile-icon">
          <div class="dropdown-menu" id="dropdown-menu">
            <!--<a href="User Account.php">User Account</a>-->
            <a href="upload.php" onclick="openUpload()">Upload</a>
            <a href="Privacy.html">Privacy</a>
            <a href="Logout.php">Logout</a>            
          </div>
        </div>
      </div>
    </nav>
  </div>
</header>

<div class="categories-container">
  <h2>Categories</h2>
  <div class="button-container">
    <a href="Dashboard.html"><button class="filter-button" data-category="DASHBOARD">DASHBOARD</button></a>
    <a href="marketing_trends.php"><button class="filter-button" data-category="MARKETING TRENDS">MARKETING TRENDS</button></a>
    <a href="content_strategy.php"><button class="filter-button active" data-category="CONTENT STRATEGY">CONTENT STRATEGY</button></a>
    <a href="social_media.php"><button class="filter-button" data-category="SOCIAL_MEDIA">SOCIAL MEDIA</button></a>
    <a href="seo.php"><button class="filter-button" data-category="S_E_O">S.E.O</button></a>
    <a href="branding.php"><button class="filter-button" data-category="BRANDING">BRANDING</button></a>
  </div>

  <div class="introduction">
    <h1>Welcome to Content Strategy</h1>
    <h4>Content strategy is a comprehensive approach to planning, creating, delivering, and managing content. It ensures that the content aligns with business goals, meets the needs of the target audience, and is sustainable over time.</h4>
  </div>
  <div class="contents">
    <h2>Contents</h2>
  </div>

  <div class="cards-container" id="cards-container">
    <?php
      $servername = "localhost";
      $username = "";
      $password = "";
      $dbname = "crusadersignup";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Check if the 'category' column exists
      $columnExists = $conn->query("SHOW COLUMNS FROM `uploaded_files` LIKE 'category'");
      if ($columnExists->num_rows == 0) {
        die("The 'category' column does not exist in the 'uploaded_files' table.");
      }

      // Prepare and bind
      $sql = $conn->prepare("SELECT id, title, name, job_title, file_path FROM uploaded_files WHERE category = ?");
      if ($sql === false) {
        die("Prepare failed: " . $conn->error);
      }

      $category = 'CONTENT STRATEGY';
      $sql->bind_param("s", $category);

      // Execute statement
      if (!$sql->execute()) {
        die("Execute failed: " . $sql->error);
      }

      $result = $sql->get_result();

      // Fetch data and display
      while ($row = $result->fetch_assoc()) {
        echo '
        <div class="card">
          <div class="image-box">
            <img src="image/CS.jpg" alt="Article Image">
          </div>
          <a href="' . htmlspecialchars($row['file_path']) . '" class="profile-link" target="_blank">
            <h2>' . htmlspecialchars($row['title']) . '</h2>
          </a>
          <div class="profile-details">
            <img src="image/profile2.jpg" alt="Author">
            <div class="name-job">
              <h3 class="name">' . htmlspecialchars($row['name']) . '</h3>
              <h4 class="job">' . htmlspecialchars($row['job_title']) . '</h4>
            </div>
          </div>
          <p>Share your thoughts or opinions in the comments below.</p>
        </div>';
      }

      // Close connection
      $conn->close();
    ?>
  </div>


  <div class="comments-container">
    <h3>Comments</h3>
    <div id="comments-section">
      <?php
          $servername = "localhost";
          $username = "";
          $password = "";
          $dbname = "crusadersignup";

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $comments_sql = $conn->prepare("SELECT comment, created_at FROM comments ORDER BY created_at DESC");
          $comments_sql->execute();
          $comments_result = $comments_sql->get_result();

          while ($comment_row = $comments_result->fetch_assoc()) {
            echo '<div class="comment">
                    <p>' . htmlspecialchars($comment_row['comment']) . '</p>
                    <span>' . htmlspecialchars($comment_row['created_at']) . '</span>
                  </div>';
          }

          $conn->close();
        ?>
    </div>
    <textarea id="comment-text" placeholder="Add a comment"></textarea>
    <button onclick="postComment()">Post Comment</button>
  </div>

  <script>
    function toggleDropdownMenu() {
      const dropdownMenu = document.getElementById('dropdown-menu');
      dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'flex' : 'none';
    }

    function openUpload() {
      document.getElementById('upload-container').style.display = 'block';
    }

    function closeUpload() {
      document.getElementById('upload-container').style.display = 'none';
    }

    function uploadFile() {
      const fileInput = document.getElementById('pdf-upload');
const file = fileInput.files[0];
if (!file) {
alert('Please select a file');
return;
    }  const formData = new FormData();
  formData.append('pdf', file);
  formData.append('title', document.getElementById('title').value);
  formData.append('abstract', document.getElementById('abstract').value);
  formData.append('creator', document.getElementById('creator').value);
  formData.append('category', document.getElementById('category').value);

  fetch('upload.php', {
    method: 'POST',
    body: formData
  }).then(response => response.text())
    .then(data => {
      alert(data);
      closeUpload();
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function postComment() {
  const commentText = document.getElementById('comment-text').value;
  if (commentText.trim() === '') {
    alert('Please enter a comment');
    return;
  }

  const formData = new FormData();
  formData.append('comment', commentText);

  fetch('', {
    method: 'POST',
    body: formData
  }).then(response => response.text())
    .then(data => {
      // Update the comments section with the new comment
      const commentsSection = document.getElementById('comments-section');
      const newComment = document.createElement('div');
      newComment.className = 'comment';
      newComment.innerHTML = `<p>${commentText}</p><span>Just now</span>`;
      commentsSection.insertBefore(newComment, commentsSection.firstChild);

      // Clear the comment text area
      document.getElementById('comment-text').value = '';
    })
    .catch(error => {
      console.error('Error:', error);
    });
}
function performSearch() {
  const query = document.getElementById('search-input').value.trim().toLowerCase();
  const cards = document.querySelectorAll('.card');

  cards.forEach(card => {
    const title = card.querySelector('h2').innerText.toLowerCase();
    const name = card.querySelector('.name').innerText.toLowerCase();
    const job = card.querySelector('.job').innerText.toLowerCase();

    if (title.includes(query) || name.includes(query) || job.includes(query)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

document.addEventListener('DOMContentLoaded', function () {
  const searchButton = document.querySelector('.search-container button');
  const searchInput = document.querySelector('.search-container input[type="text"]');

  searchButton.addEventListener('click', performSearch);

  searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      performSearch();
    }
  });
});
</script>
</body>
</html>
