<?php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>User Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body{
            font-family: Arial;
            background: #f4f6f8;
            margin: 0;
        }
        .container{
            padding: 20px;
        }
        h2{
            margin-bottom: 20px;
        }
        .user-table{
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .user-table th, .user-table td{
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .user-table th{
            background: #1db954;
            color: white;
        }

        .role{
            padding: 4px 8px;
            border-radius: 6px;
            color: white;
            font-size: 12px;
        }

        .role.user{ background: #3498db; }
        .role.admin{ background: #e67e22; }

        .btn{
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn.view{ background: #3498db; color: white; }
        .btn.delete{ background: #e74c3c; color: white; }

        .item{
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
        }
        .btn-back{
    display: inline-block;
    padding: 8px 12px;
    margin-bottom: 15px;
    background: #1db954;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.2s;
}
.btn-back:hover{
    background: #17a74a;
}
    </style>
</head>
<body>
<div class="container">
<a href="index.php?url=home" class="btn-back">
    ⬅ Back to Home
</a>
    <div class="item"><i class="fa fa-users"></i>Manager</div>
    <h2>👤 Quản lý User</h2>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span class="role <?= $user['role'] ?>">
                        <?= $user['role'] ?>
                    </span>
                </td>
                <td>
                <a href="index.php?url=manager_show&id=<?= $user['id'] ?>">
                <button class="btn view">👁 View</button>
            </a>
                    <button class="btn delete" onclick="deleteUser(<?= $user['id'] ?>)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function viewUser(id){
    alert("Xem user: " + id);
}
function deleteUser(id){
    if(!confirm("Xóa user này?")) return;
    fetch("index.php?url=manager_delete&id=" + id, {
        method: "POST"
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            location.reload();
        } else {
            alert("Xóa thất bại");
        }
    });
}
</script>
</body>
</html>