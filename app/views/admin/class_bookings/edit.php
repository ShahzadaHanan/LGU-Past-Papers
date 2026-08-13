<h1>Edit Booking</h1>
<div class="card">
<form method="POST" action="/admin/class-bookings/<?= (int) $item->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>WhatsApp</label><input type="text" name="whatsapp" value="<?= htmlspecialchars($item->whatsapp, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Subject</label><input type="text" name="subject" value="<?= htmlspecialchars($item->subject, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Topic</label><input type="text" name="topic" value="<?= htmlspecialchars($item->topic ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-group"><label>Fee Type</label>
<select name="fee_type">
<option value="single_lecture" <?= $item->fee_type === 'single_lecture' ? 'selected' : '' ?>>Single Lecture</option>
<option value="full_subject" <?= $item->fee_type === 'full_subject' ? 'selected' : '' ?>>Full Subject</option>
</select></div>
<div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= htmlspecialchars($item->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="form-group"><label>Status</label>
<select name="status">
<?php foreach (['new', 'contacted', 'confirmed', 'completed', 'cancelled'] as $s): ?>
<option value="<?= $s ?>" <?= $item->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
<?php endforeach; ?>
</select></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/class-bookings">Cancel</a>
</form>
</div>
