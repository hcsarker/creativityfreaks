<section class="courses-page container" data-aos="fade-up">
    <div class="courses-layout">
        <!-- Left Sidebar -->
        <aside class="sidebar">
            <h3>Categories</h3>
            <ul>
                <li>
                    <button class="main-cat-btn " data-main="All">All Categories</button>
                </li>
                <?php foreach ($categoryStructure as $main => $subs): ?>
                <li>
                    <button class="main-cat-btn" data-main="<?= htmlspecialchars($main) ?>">
                        <?= htmlspecialchars($main) ?>
                    </button>
                </li>
                <?php endforeach; ?>
            </ul>
        </aside>


        <!-- Main Content -->
        <div class="courses-content">
            <h2 class="section-title">Explore Our Courses</h2>
            <div class="subcategory-filter" id="subcategoryFilter"></div>
            <div class="courses-grid" id="coursesGrid">
                <!-- Courses will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</section>

<script>
let selectedMain = 'All';
let selectedSub = 'All';

function loadCourses(main = 'All', sub = 'All') {
    fetch('../ajax/load_courses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `main=${encodeURIComponent(main)}&subcategory=${encodeURIComponent(sub)}`
        })
        .then(res => res.json())
        .then(data => {
            // Subcategory filter buttons
            const subFilter = document.getElementById('subcategoryFilter');
            subFilter.innerHTML = '';

            if (data.subcategories && data.subcategories.length > 0) {
                data.subcategories.forEach(subcat => {
                    const btn = document.createElement('button');
                    btn.innerText = subcat;
                    btn.classList.add('subcat-btn');
                    if (subcat === selectedSub) btn.classList.add('active');
                    btn.addEventListener('click', () => {
                        selectedSub = subcat;
                        loadCourses(selectedMain, selectedSub);
                    });
                    subFilter.appendChild(btn);
                });
            }

            // Courses grid
            const grid = document.getElementById('coursesGrid');
            grid.innerHTML = '';
            if (data.courses.length > 0) {
                data.courses.forEach(course => {
                    grid.innerHTML += `
                        <a href="course_details.php?id=${course.id}" class="course-card-link">
                            <div class="course-card">
                            <img src="${course.thumbnail ? '../uploads/thumbnail/' + course.thumbnail : '../uploads/thumbnail/default-course.jpg'}" alt="${course.title}">
                            <div class="course-info">
                                <h3>${course.title}</h3>
                                <p>${course.description.substring(0, 100)}...</p>
                                <div class="meta">
                                <span class="category">${course.category}</span>
                                <span class="price">${course.price == 0 ? 'Free' : '$' + parseFloat(course.price).toFixed(2)}</span>
                                </div>
                            </div>
                            </div>
                        </a>`;

                });
            } else {
                grid.innerHTML = '<p>No courses found in this category.</p>';
            }

            // Update active state on main category buttons
            document.querySelectorAll('.main-cat-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.main === selectedMain);
            });
        });
}

document.querySelectorAll('.main-cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        selectedMain = btn.dataset.main;
        selectedSub = 'All'; // Reset subcategory filter
        loadCourses(selectedMain, selectedSub);
    });
});

// Initial load with 'All'
loadCourses();
</script>