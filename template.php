<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars(Config::SITE_NAME . ' - ' . Config::BOARD_NAME) ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #FFFFEE;
            color: #800000;
            font-family: arial, helvetica, sans-serif;
            font-size: 10pt;
            margin: 8px;
        }
        .header {
            text-align: center;
            font-size: 28pt;
            color: #AF0A0F;
            font-weight: bold;
            margin: 10px 0;
        }
        .board-title {
            text-align: center;
            font-size: 20pt;
            color: #0F0C5D;
            margin: 10px 0;
        }
        .postform {
            background: #EEAA88;
            border: 1px solid #800000;
            padding: 4px;
            margin: 10px 0;
            display: table;
        }
        .postform table {
            border-collapse: separate;
            border-spacing: 2px;
        }
        .postform td {
            padding: 2px;
        }
        input[type="text"], input[type="email"], textarea, input[type="file"] {
            background: #FFFFFF;
            border: 1px solid #666666;
            font-family: arial, helvetica, sans-serif;
            font-size: 10pt;
        }
        input[type="submit"] {
            background: #EEEEEE;
            border: 2px outset #EEEEEE;
            padding: 2px 6px;
            cursor: pointer;
            font-family: arial, helvetica, sans-serif;
            font-size: 10pt;
        }
        input[type="submit"]:hover {
            background: #DDDDDD;
        }
        .post {
            margin: 4px 0;
        }
        .post-header {
            background: #0F0C5D;
            color: #FFFFFF;
            padding: 2px 4px;
            font-weight: bold;
            font-size: 11pt;
        }
        .post-info {
            background: #D6DAF0;
            padding: 2px 4px;
            font-size: 9pt;
            border: 1px solid #B7C5D9;
        }
        .post-content {
            background: #FFFFFF;
            border: 1px solid #D6DAF0;
            padding: 8px;
            margin: 2px 0;
            word-wrap: break-word;
        }
        .name {
            color: #117743;
            font-weight: bold;
        }
        .subject {
            color: #0F0C5D;
            font-weight: bold;
        }
        .post-num {
            color: #FF6600;
        }
        .post-image {
            float: left;
            margin: 0 10px 10px 0;
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #CCCCCC;
            cursor: pointer;
        }
        .post-image:hover {
            opacity: 0.8;
        }
        .error {
            color: #FF0000;
            font-weight: bold;
            background: #FFEEEE;
            border: 1px solid #FF0000;
            padding: 4px;
            margin: 4px 0;
        }
        .success {
            color: #008000;
            font-weight: bold;
            background: #EEFFEE;
            border: 1px solid #008000;
            padding: 4px;
            margin: 4px 0;
        }
        a {
            color: #0000EE;
        }
        a:visited {
            color: #551A8B;
        }
        .reply-link {
            font-size: 9pt;
            color: #FF6600;
            text-decoration: none;
        }
        .reply-link:hover {
            text-decoration: underline;
        }
        .return-link {
            font-size: 9pt;
            color: #0000EE;
            text-decoration: none;
        }
        .return-link:hover {
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            font-size: 9pt;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #CCCCCC;
        }
        .no-posts {
            text-align: center;
            margin: 20px;
            color: #666;
            font-style: italic;
        }
        
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .header {
                font-size: 20pt;
            }
            .board-title {
                font-size: 16pt;
            }
            .postform {
                display: block;
            }
            .postform table {
                width: 100%;
            }
            .postform input[type="text"], 
            .postform input[type="email"], 
            .postform textarea {
                width: 95%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

<div class="header"><?= htmlspecialchars(Config::SITE_NAME) ?></div>
<div class="board-title"><?= htmlspecialchars(Config::BOARD_NAME) ?></div>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="postform">
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><b>Name:</b></td>
                <td><input type="text" name="name" size="35" maxlength="35" placeholder="Anonymous"></td>
            </tr>
            <tr>
                <td><b>E-mail:</b></td>
                <td><input type="email" name="email" size="35" maxlength="35"></td>
            </tr>
            <tr>
                <td><b>Subject:</b></td>
                <td><input type="text" name="subject" size="35" maxlength="75"></td>
            </tr>
            <tr>
                <td><b>Comment:</b></td>
                <td><textarea name="comment" rows="5" cols="35" maxlength="1500" required placeholder="Enter your message here..."></textarea></td>
            </tr>
            <tr>
                <td><b>File:</b></td>
                <td><input type="file" name="image" accept="image/*"><br>
                    <small>Max file size: 5MB (JPG, PNG, GIF, WebP)</small></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php if ($thread_id > 0): ?>
                        <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
                        <a href="?" class="return-link">[Return to board]</a> 
                    <?php endif; ?>
                    <input type="submit" name="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>
</div>

<?php if (empty($posts)): ?>
    <div class="no-posts">
        No posts yet. Be the first to post!
    </div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <span class="subject"><?= htmlspecialchars($post['subject'] ?: 'No Subject') ?></span>
            </div>
            <div class="post-info">
                <span class="name"><?= htmlspecialchars($post['name']) ?></span>
                <?php if ($post['email']): ?>
                    <a href="mailto:<?= htmlspecialchars($post['email']) ?>"><?= htmlspecialchars($post['email']) ?></a>
                <?php endif; ?>
                <?= date('m/d/y(D)H:i', $post['timestamp']) ?>
                <span class="post-num">No.<?= $post['id'] ?></span>
                <?php if ($thread_id == 0 && $post['thread_id'] == 0): ?>
                    <a href="?thread=<?= $post['id'] ?>" class="reply-link">[Reply]</a>
                <?php endif; ?>
            </div>
            <div class="post-content">
                <?php if ($post['image_url']): ?>
                    <a href="<?= htmlspecialchars($post['image_url']) ?>" target="_blank" title="<?= htmlspecialchars($post['image_filename']) ?>">
                        <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                             alt="<?= htmlspecialchars($post['image_filename']) ?>"
                             class="post-image">
                    </a>
                <?php endif; ?>
                <?= formatPost($post) ?>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="footer">
    <?= htmlspecialchars(Config::SITE_NAME) ?> - Anonymous Image Board<br>
    <small>Powered by PHP, Cloudflare D1, and ImgBB</small>
</div>

</body>
</html>
