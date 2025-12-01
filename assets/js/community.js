
// Bind reply toggle and like buttons to dynamically loaded comments
function bindCommunityEvents() {
  // Toggle reply form
  document.addEventListener('click', function (e) {
    if (e.target.closest('.reply-toggle-btn')) {
      const btn = e.target.closest('.reply-toggle-btn');
      const commentId = btn.dataset.commentId;
      const form = document.querySelector(`.reply-form[data-comment-id="${commentId}"]`);
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    // Like comment
    if (e.target.closest('.comment-like-btn')) {
      const btn = e.target.closest('.comment-like-btn');
      const commentId = btn.dataset.commentId;

      fetch('/creativityfreaks/pages/community/like_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': getCsrfToken() },
        body: new URLSearchParams({ comment_id: commentId, csrf_token: getCsrfToken() })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            btn.querySelector('.like-count').textContent = data.likes;
          }
        });
    }
  });

  // Submit reply
  document.addEventListener('submit', function (e) {
    if (e.target.classList.contains('reply-form')) {
      e.preventDefault();
      const form = e.target;
      const commentId = form.dataset.commentId;
      const reply = form.querySelector('textarea').value;

      fetch('/creativityfreaks/pages/community/reply_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': getCsrfToken() },
        body: new URLSearchParams({ comment_id: commentId, reply: reply, csrf_token: getCsrfToken() })
      })
        .then(res => res.text())
        .then(html => {
          form.querySelector('textarea').value = '';
          const container = document.querySelector(`.replies-container[data-comment-id="${commentId}"]`);
          container.innerHTML += html;
        });
    }
  });
}

function loadComments(postId, container = null) {
  fetch(`/creativityfreaks/pages/community/load_comments.php?post_id=${postId}`)
    .then(res => res.text())
    .then(data => {
      if (container) {
        container.innerHTML = data;
      } else {
        const target = document.getElementById(`comments-${postId}`);
        if (target) target.innerHTML = data;
      }
    });
}

document.addEventListener('DOMContentLoaded', () => {
  bindCommunityEvents();

  // Load all comment sections on page load
  document.querySelectorAll('.comment-section').forEach(section => {
    const postId = section.getAttribute('data-post-id');
    loadComments(postId);
  });

  // Toggle comments section
  document.querySelectorAll('.comment-btn').forEach(button => {
    button.addEventListener('click', function () {
      const postId = this.dataset.postId;
      const commentSection = document.querySelector(`.comments-section[data-post-id="${postId}"]`);

      if (commentSection.style.display === 'none' || commentSection.style.display === '') {
        commentSection.style.display = 'block';
        const container = commentSection.querySelector('.comments-container');
        loadComments(postId, container);
      } else {
        commentSection.style.display = 'none';
      }
    });
  });

  // Submit comment (text + optional image)
  document.addEventListener('submit', function (e) {
    if (e.target.classList.contains('comment-form')) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch('/creativityfreaks/pages/community/submit_comment.php', {
        method: 'POST',
        body: (function(){ formData.append('csrf_token', getCsrfToken()); return formData; })()
      })
        .then(res => res.text())
        .then(html => {
          const commentsContainer = form.previousElementSibling;
          commentsContainer.innerHTML = html;
          form.reset();
        });
    }
  });

  // Post Like / Dislike
  document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const postId = this.dataset.postId;
      const action = this.dataset.action;

      fetch('/creativityfreaks/pages/community/handle_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': getCsrfToken() },
        body: `post_id=${encodeURIComponent(postId)}&action=${encodeURIComponent(action)}&csrf_token=${encodeURIComponent(getCsrfToken())}`
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const postCard = this.closest('.post-card');
            postCard.querySelector('.like-btn').innerHTML = `<i class="fas fa-thumbs-up"></i> ${data.likes}`;
            postCard.querySelector('.dislike-btn').innerHTML = `<i class="fas fa-thumbs-down"></i> ${data.dislikes}`;
          } else {
            alert(data.message);
          }
        });
    });
  });

  // Tabs filter (e.g. My Posts)
  document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');

      const tab = button.dataset.tab;
      document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
      document.getElementById(tab).classList.add('active');

      if (tab === 'my-posts') {
        fetch('/creativityfreaks/pages/community/my_posts.php')
          .then(res => res.text())
          .then(data => {
            document.getElementById('my-posts-container').innerHTML = data;
            bindCommunityEvents(); // re-bind after loading new content
          });
      }
    });
  });
});

// Load comments dynamically for a post
function loadComments(postId, container = null) {
  fetch(`/creativityfreaks/pages/community/load_comments.php?post_id=${postId}`)
    .then(res => res.text())
    .then(data => {
      if (container) {
        container.innerHTML = data;
      } else {
        const target = document.getElementById(`comments-${postId}`);
        if (target) target.innerHTML = data;
      }
    });
}

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

