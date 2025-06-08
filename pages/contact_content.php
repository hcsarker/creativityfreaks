<div class="contact-container">
  <h2>Contact Us</h2>

 <?php if (isset($message_sent) && $message_sent): ?>
  <p class="success-msg">Thank you for contacting us! We'll get back to you soon.</p>
<?php else: ?>

    <form action="" method="POST" class="contact-form">
      <label>Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Subject</label>
      <input type="text" name="subject" required>

      <label>Message</label>
      <textarea name="message" rows="5" required></textarea>

      <button type="submit">Send</button>
    </form>
  <?php endif; ?>
</div>

<style>
.contact-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
  background: #f9f9f9;
  border-radius: 8px;
}
.contact-form label {
  display: block;
  margin-top: 1rem;
  font-weight: 600;
}
.contact-form input, .contact-form textarea {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.3rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}
.contact-form button {
  margin-top: 1rem;
  padding: 0.7rem 1.5rem;
  background-color: #0066cc;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.contact-form button:hover {
  background-color: #004999;
}
.success-msg {
  color: green;
  font-weight: bold;
  font-size: 1.2rem;
}
</style>
