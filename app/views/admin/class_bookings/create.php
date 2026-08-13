<h1>Add Booking</h1>
<div class="card">
<form method="POST" action="/admin/class-bookings">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" required></div>
<div class="form-group"><label>WhatsApp</label><input type="text" name="whatsapp" required></div>
<div class="form-group"><label>Subject</label><input type="text" name="subject" required></div>
<div class="form-group"><label>Topic (optional)</label><input type="text" name="topic"></div>
<div class="form-group"><label>Fee Type</label>
<select name="fee_type"><option value="single_lecture">Single Lecture</option><option value="full_subject">Full Subject</option></select>
</div>
<div class="form-group"><label>Description</label><textarea name="description" rows="4"></textarea></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/class-bookings">Cancel</a>
</form>
</div>
