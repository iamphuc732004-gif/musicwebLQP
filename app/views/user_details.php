<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>User Detail</title>
    <style>
        body{ 
            font-family: Arial; 
            background: #f4f6f8; 
            padding: 20px; 
        }
        .container{ 
            max-width: 1000px; 
            margin: auto; 
        }
        .back-btn{ 
            display:inline-block; 
            padding:8px 12px; 
            background:#1db954; 
            color:white; 
            text-decoration:none; 
            border-radius:6px; 
            margin-bottom:15px; 
        }
        table{ 
            width:100%; 
            border-collapse: collapse; 
            background:white; 
            border-radius:10px; 
            overflow:hidden; 
            box-shadow:0 2px 10px rgba(0,0,0,0.05); 
        }
        th{ 
            background:#1db954; 
            color:white; 
            padding:12px; 
            text-align:left; 
        }
        td{ 
            padding:12px; 
            border-bottom:1px solid #eee; 
            vertical-align: middle; 
        }
        img{ 
            width:50px; 
            height:50px; 
            object-fit:cover; 
            border-radius:6px; }
        .no-data{ 
            text-align:center; 
            padding:20px; 
            color:#888; 
        }
        .btn-action { padding: 5px 10px; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 12px; 
            margin-right: 5px; 
        }
        .btn-edit { 
            background: #ffc107; 
            color: #000; }
        .btn-delete { 
            background: #dc3545; 
            color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php?url=manager" class="back-btn">⬅ Back</a>
    <h2>🎵 Songs Uploaded</h2>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Image</th>
                <th>Track</th>
                <th>Artist</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($songs)): ?>
            <?php $i = 1; foreach($songs as $song): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <?php if(!empty($song['image'])): ?>
                            <img src="/LQP/public/ok/images/<?= htmlspecialchars($song['image']) ?>">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($song['track']) ?></td>
                    <td><?= htmlspecialchars($song['artist']) ?></td>
                    <td><?= htmlspecialchars($song['type']) ?></td>
                    <td>
                        <a href="index.php?url=edit_song&id=<?= $song['id'] ?>" class="btn-action btn-edit">Edit</a>
                        <a href="#" class="btn-action btn-delete" 
                           onclick="confirmDelete(<?= $song['id'] ?>)">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="no-data">Không có bài hát nào</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa bài hát này không?")) {
            window.location.href = 'index.php?url=delete_song&id=' + id;
        }
    }
</script>
</body>
</html>