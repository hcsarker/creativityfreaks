<div class="analytics-container">
  <h1>Site Analytics 📊</h1>

  <h3> <strong> Monthly Revenue </strong></h3>
  
<form method="GET" class="filter-form">
  <select name="category" id="category" onchange="updateSubcategories()">
    <option value="">All Categories</option>
    <!-- PHP: Inject dynamic category list -->
    <?php foreach ($categories as $cat): ?>
      <option value="<?= htmlspecialchars($cat['name']) ?>" <?= $_GET['category'] == $cat['name'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <select name="subcategory" id="subcategory">
    <option value="">All Subcategories</option>
    <!-- Injected by JS or dynamically in PHP if you want -->
    <?php if (isset($_GET['subcategory'])): ?>
      <option selected><?= htmlspecialchars($_GET['subcategory']) ?></option>
    <?php endif; ?>
  </select>

  <input type="date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
  <input type="date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">

  <button type="submit">Filter</button>
</form>

    <canvas id="revenueChart" width="600" height="300"></canvas>

  <div class="stats-section">
  <!-- Top Courses -->
  <div class="stat-card">
    <h3>🎓 Top 5 Courses</h3>
    <?php foreach ($topCourses as $course): ?>
      <div class="stat-item">
        <span><?= htmlspecialchars($course['title']) ?></span>
        <span><?= $course['enroll_count'] ?> enrolls</span>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Top Instructors -->
  <div class="stat-card">
    <h3>👨‍🏫 Top 5 Instructors</h3>
    <?php foreach ($popularInstructors as $instructor): ?>
      <div class="stat-item">
        <span><?= htmlspecialchars($instructor['instructor_name']) ?></span>
        <span><?= $instructor['total_enrollments'] ?> enrolls</span>
      </div>
    <?php endforeach; ?>
  </div>
</div>


  <section class="chart-section">
    <h2>Monthly User Registrations (Last 12 months)</h2>
    <canvas id="userRegistrationsChart"></canvas>
  </section>

  <section class="chart-section">
    <h2>Monthly Courses Added (Last 12 months)</h2>
    <canvas id="coursesAddedChart"></canvas>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    const categoryToSub = {
  "Academic": ["Class 6-10", "Class 11-12", "Undergraduate"],
  "Admission": ["Medical", "Engineering", "University"],
  "Skill": ["Graphic Design", "Web Development", "MS Office"],
  "Language": ["English", "IELTS", "Japanese"]
};

function updateSubcategories() {
  const category = document.getElementById('category').value;
  const subSelect = document.getElementById('subcategory');
  subSelect.innerHTML = '<option value="">All Subcategories</option>';
  if (categoryToSub[category]) {
    categoryToSub[category].forEach(sub => {
      const opt = document.createElement('option');
      opt.value = sub;
      opt.textContent = sub;
      subSelect.appendChild(opt);
    });
  }
}

     const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_keys($revenueData)) ?>,
        datasets: [{
          label: 'Monthly Revenue',
          data: <?= json_encode(array_values($revenueData)) ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

  const months = <?= json_encode(array_keys($userRegs)); ?>;
  const userRegistrations = <?= json_encode(array_values($userRegs)); ?>;
  const coursesAdded = <?= json_encode(array_values($courseAdds)); ?>;

  // User Registrations Chart
  const ctxUsers = document.getElementById('userRegistrationsChart').getContext('2d');
  new Chart(ctxUsers, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'User Registrations',
        data: userRegistrations,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true, precision: 0 }
      }
    }
  });

  // Courses Added Chart
  const ctxCourses = document.getElementById('coursesAddedChart').getContext('2d');
  new Chart(ctxCourses, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Courses Added',
        data: coursesAdded,
        backgroundColor: 'rgba(255, 159, 64, 0.7)'
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true, precision: 0 }
      }
    }
  });
</script>

<style>

    /* Global styles */

    .filter-form {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  margin-bottom: 20px;
}

.filter-form select,
.filter-form input[type="date"] {
  padding: 8px 12px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 6px;
  min-width: 160px;
}

.filter-form button {
  background-color: #4CAF50;
  color: white;
  padding: 8px 18px;
  font-size: 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.filter-form button:hover {
  background-color: #45a049;
}

/* Specific styles */
  .analytics-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
  }
  .chart-section {
    margin-bottom: 3rem;
  }
  .chart-section h2 {
    margin-bottom: 1rem;
  }
  .chart-section canvas {
    max-width: 100%;
  }
  @media screen and (max-width: 768px) {
    .analytics-container {
      padding: 0;
    }
    .chart-section {
      margin-bottom: 2rem;
    }
    .chart-section h2 {
      margin-bottom: 0.5rem;
    }
    .chart-section canvas {
      max-width: 100%;
    }
  }
  .stats-section {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  margin-top: 30px;
}

.stat-card {
  flex: 1 1 300px;
  background: #f9f9f9;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
  transition: transform 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-5px);
}

.stat-card h3 {
  font-size: 20px;
  margin-bottom: 15px;
  color: #333;
  border-bottom: 1px solid #ddd;
  padding-bottom: 8px;
}

.stat-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px dashed #ddd;
}
.stat-item:last-child {
  border-bottom: none;
}

.stat-item span:first-child {
  color: #333;
  font-weight: 500;
}

.stat-item span:last-child {
  color: #4CAF50;
  font-weight: bold;
}

</style>