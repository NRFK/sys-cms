<?php
if (!$conn)
{
	echo '<div class="alert alert-danger"><b>Error:</b> No database connection!.</div>';
}

$query = "SELECT id, username FROM users ORDER BY id";
$result = $conn->query($query);

$defaultAvatar = '/path/to/generic-profile.png'; // Replace with your actual image path

if ($result && $result->num_rows > 0):
?>
<table class="table table-striped align-middle">
	<thead>
		<tr>
			<th>Profile</th>
			<th>Username</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php while ($user = $result->fetch_assoc()): ?>
		<?php $fullname = htmlspecialchars(formatUsername($user['username'])); ?>
		<tr>
			<td>
				<img src="https://ui-avatars.com/api/?name=<?php echo $fullname; ?>" alt="Profile" width="40" height="40" class="rounded-circle">
			</td>
			<td><?php echo($fullname); ?></td>
			<td>
				<a href="/admin/?route=users/edit&id=<?= urlencode($user['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
				<a href="/admin/?route=users/delete&id=<?= urlencode($user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user permanently?');">Delete</a>
			</td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>
<?php
else:
echo '<div class="alert alert-info">No users found.</div>';
endif;
?>