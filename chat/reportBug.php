<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ./index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Bug — Easy Chat</title>
    <link rel="stylesheet" href="./style/header.css">
    <link rel="stylesheet" href="./style/headerPhone.css">
    <link rel="stylesheet" href="./style/body.css">
    <link rel="stylesheet" href="./style/footer.css">
    <link rel="stylesheet" href="./style/customeSearch.css">
    <link rel="stylesheet" href="./style/additionalStylesBugReport.css">
</head>
<body>
<header>
    <h1>EASY CHAT HALL</h1>
    <div class="top-nav">
        <h2>
            <a class="" href="./guestRoom.php">Back</a>
        </h2>
    </div>
</header>

<main>
    <?php if ($_SESSION['role'] !== 'admin'): ?>

        <!-- Правила -->
        <section class="rules-block-wrapper">
            <div class="rules-block">
                <div class="rule-card">
                    <h3>Be specific</h3>
                    Describe the problem clearly: what you did, what happened, and what you expected.
                </div>
                <div class="rule-card">
                    <h3>One issue per report</h3>
                    Report only one bug per submission to keep things organized.
                </div>
                <div class="rule-card">
                    <h3>No spam</h3>
                    Don’t submit fake or joke reports. They will be ignored or may result in a warning.
                </div>
                <div class="rule-card">
                    <h3>Attach details</h3>
                    Mention browser, device, or anything that can help us investigate the issue.
                </div>
            </div>
        </section>

        <!-- Форма -->
        <section class="bug-form-wrapper">
            <div class="bug-form-card">
                <div class="bug-form-header">
                    <h3>🐞 Bug Report</h3>
                    <span class="badge">Feedback</span>
                </div>

                <div class="bug-form-body">
                    <form method="post" action="submitBugReport.php">
                        <label for="bug_types"><strong>Select bug type(s):</strong></label>
                        <select id="bug_types" name="bug_types[]" multiple required class="bug-select">
                            <option value="1">UI Problem</option>
                            <option value="2">Message Error</option>
                            <option value="3">Login/Registration</option>
                            <option value="4">Performance</option>
                            <option value="5">Other</option>
                        </select>

                        <label for="comment"><strong>Your comment:</strong></label>
                        <textarea id="comment" name="comment" rows="5" required class="bug-textarea"></textarea>

                        <button type="submit" class="go-to-chat-btn" style="width: 100%;">Submit Report</button>
                    </form>
                </div>
            </div>
        </section>

    <?php else: ?>
    <!-- Админ все репорты -->
    <section class="bug-report-list-wrapper">
        <h2 class="h2-indents">📋 Bug Reports </h2>
        <?php
        require '../connect.php';

        $sql = "SELECT
            br.id,
            br.comment,
            br.created_at,
            u.login AS user_login,
            bt.name AS bug_type
        FROM bug_report br
        JOIN bug_report_type brt ON br.id = brt.bug_report_id
        JOIN bug_type bt ON brt.bug_type_id = bt.id
        JOIN users u ON brt.user_id = u.id
        ORDER BY br.id DESC;";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            echo "<div class='report-list'>";
            while ($row = $result->fetch_assoc()):
                echo "
                    <div class='report-card'>
                        <div class='card-header'>
                            <h3>📝 Report #{$row['id']}</h3>
                            <span class='badge-1'>{$row['user_login']}</span>
                        </div>
                        <div class='card-body'>
                            <p><strong>Type:</strong> {$row['bug_type']}</p>
                            <p><strong>Comment:</strong> " . htmlspecialchars($row['comment']) . "</p>
                            <p><small><strong>Created:</strong> {$row['created_at']}</small></p>
                        </div>
                    </div>
                ";
            endwhile;
            echo "</div>";
        else:
            echo "<p>No bug reports yet.</p>";
        endif;
        ?>
    </section>
    <?php endif; ?>
</main>


<?php if (isset($_GET['success'])): ?>
    <div id="success-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="checkmark">✔</div>
            <p>Bug report submitted successfully!</p>
        </div>
    </div>
    <script>
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            }
    </script>
<?php endif; ?>

<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>
</body>
</html>
