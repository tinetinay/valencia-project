<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'database.php';

$result = $conn->query("SELECT * FROM students WHERE TRIM(firstname) != '' AND TRIM(lastname) != '' ORDER BY id DESC");
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$total_students = count($students);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Lounge &bull; Kristine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-pastel: #f0f9ff;
            --card-white: #ffffff;
            --sky-blue: #0284c7;
            --text-dark: #0f172a;
            --text-sub: #64748b;
            --border-soft: #e0f2fe;
            --shadow-soft: 0 10px 30px rgba(2, 132, 199, 0.08);
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-pastel);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 70px;
        }

        .workspace-shell {
            max-width: 1020px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .sky-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1.5rem;
            border-bottom: 1.5px solid var(--border-soft);
            margin-bottom: 2rem;
        }

        .brand-gem {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-dark);
        }

        .gem-circle {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.25);
        }

        .btn-exit-sky {
            background: #ffffff;
            color: #ef4444;
            border: 1px solid #fecaca;
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-exit-sky:hover {
            background: #ef4444;
            color: white;
        }

        .enroll-banner {
            background: var(--card-white);
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 2.2rem;
            box-shadow: var(--shadow-soft);
        }

        .input-sky {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 0.7rem 1.1rem;
            color: var(--text-dark);
            font-size: 0.92rem;
            outline: none;
            transition: all 0.2s;
        }

        .input-sky:focus {
            border-color: var(--sky-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        }

        .btn-sky-action {
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-size: 0.92rem;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.25);
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-sky-action:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            color: white;
        }

        .search-wrap-sky {
            position: relative;
            width: 100%;
            max-width: 320px;
        }

        .search-wrap-sky i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
        }

        .search-input-sky {
            width: 100%;
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 50px;
            padding: 0.55rem 1rem 0.55rem 2.6rem;
            color: var(--text-dark);
            font-size: 0.88rem;
            outline: none;
        }

        .search-input-sky:focus {
            border-color: var(--sky-blue);
        }

        .student-tile-sky {
            background: var(--card-white);
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            padding: 1.4rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease;
            box-shadow: var(--shadow-soft);
        }

        .student-tile-sky:hover {
            border-color: #7dd3fc;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(2, 132, 199, 0.12);
        }

        .initial-avatar {
            width: 42px;
            height: 42px;
            background: #e0f2fe;
            color: #0284c7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .tag-enrolled {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #d1fae5;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
        }

        .btn-edit-sky {
            background: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
            border-radius: 50px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            transition: all 0.2s;
        }

        .btn-edit-sky:hover {
            background: #0284c7;
            color: white;
        }

        .btn-del-sky {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
            border-radius: 50px;
            padding: 5px 12px;
            font-size: 0.78rem;
            font-weight: 700;
            transition: all 0.2s;
        }

        .btn-del-sky:hover {
            background: #e11d48;
            color: white;
        }

        .modal-content-sky {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

<div class="workspace-shell">

    <header class="sky-nav">
        <a href="dashboard.php" class="brand-gem">
            <div class="gem-circle">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0" style="font-size: 1.25rem;">Kristine's Portal</h3>
                <div style="font-size: 0.72rem; color: var(--text-sub); font-weight: 600;">STUDENT ROSTER</div>
            </div>
        </a>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold small"><?= htmlspecialchars($_SESSION['firstname'] ?? 'Kristine'); ?> <?= htmlspecialchars($_SESSION['lastname'] ?? 'Valencia'); ?></div>
                <div style="font-size: 0.72rem; color: var(--sky-blue); font-weight: 600;">Administrator</div>
            </div>
            <a href="logout.php" class="btn-exit-sky">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success alert-dismissible fade show mb-4 small py-2 px-3" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-1"></i> <?= htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger alert-dismissible fade show mb-4 small py-2 px-3" role="alert" style="border-radius: 12px;">
            <i class="bi bi-exclamation-circle-fill me-1"></i> <?= htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <section class="enroll-banner">
        <form action="insert.php" method="POST" class="row g-2 align-items-center">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="col-lg-4 col-md-12 mb-2 mb-lg-0">
                <div class="fw-bold fs-5 text-dark">Register Student</div>
                <div style="font-size: 0.78rem; color: var(--text-sub);">Directly add a new student record below.</div>
            </div>

            <div class="col-lg-3 col-md-5">
                <input type="text" name="firstname" class="input-sky w-100" placeholder="First Name" maxlength="50" required autofocus>
            </div>

            <div class="col-lg-3 col-md-5">
                <input type="text" name="lastname" class="input-sky w-100" placeholder="Last Name" maxlength="50" required>
            </div>

            <div class="col-lg-2 col-md-2">
                <button type="submit" class="btn-sky-action w-100">
                    <i class="bi bi-plus-lg me-1"></i> Add
                </button>
            </div>
        </form>
    </section>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem;">Student Records</h4>
            <div style="font-size: 0.82rem; color: var(--text-sub);">
                Enrolled students: <strong class="text-dark" id="countBadge"><?= $total_students; ?></strong>
            </div>
        </div>

        <div class="search-wrap-sky">
            <i class="bi bi-search"></i>
            <input type="text" id="directorySearch" class="search-input-sky" placeholder="Search by student name...">
        </div>
    </div>

    <div class="row g-3" id="studentRosterGrid">
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <div class="col-lg-4 col-md-6 student-col-item">
                    <div class="student-tile-sky">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="initial-avatar">
                                    <?= strtoupper(substr($student['firstname'], 0, 1)); ?>
                                </div>
                                <span class="tag-enrolled">Enrolled</span>
                            </div>

                            <h5 class="fw-bold mb-1 text-dark target-student-name">
                                <?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                            </h5>
                            <div class="small mb-3" style="color: var(--text-sub); font-size: 0.82rem;">
                                <span>First: <?= htmlspecialchars($student['firstname']); ?></span> &bull; 
                                <span>Last: <?= htmlspecialchars($student['lastname']); ?></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top border-light-subtle">
                            <button type="button" class="btn-edit-sky" data-bs-toggle="modal" data-bs-target="#editModal<?= $student['id']; ?>">
                                <i class="bi bi-pencil-fill me-1"></i> Edit
                            </button>

                            <form action="delete.php" method="POST" onsubmit="return confirm('Do you want to delete this student record?');" class="m-0">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type="hidden" name="id" value="<?= $student['id']; ?>">
                                <button type="submit" class="btn-del-sky">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal<?= $student['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered text-start">
                            <div class="modal-content modal-content-sky">
                                <form action="update.php" method="POST">
                                    <div class="modal-header border-bottom px-4">
                                        <h6 class="modal-title fw-bold text-dark fs-5">Edit Record</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                        <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                        <div class="mb-3">
                                            <label class="small fw-semibold mb-1" style="color: var(--text-sub);">First Name</label>
                                            <input type="text" name="firstname" class="input-sky w-100" value="<?= htmlspecialchars($student['firstname']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="small fw-semibold mb-1" style="color: var(--text-sub);">Last Name</label>
                                            <input type="text" name="lastname" class="input-sky w-100" value="<?= htmlspecialchars($student['lastname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top px-4">
                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-sky-action py-1 px-4">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12" id="noStudentsNotice">
                <div class="text-center py-5 rounded-4 bg-white border border-light-subtle" style="color: var(--text-sub);">
                    <i class="bi bi-person-lines-fill d-block fs-2 mb-2" style="color: var(--sky-blue);"></i>
                    No student records found. Enter a name above to register.
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const searchInput = document.getElementById('directorySearch');
    const items = document.querySelectorAll('.student-col-item');
    const countBadge = document.getElementById('countBadge');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let count = 0;

        items.forEach(item => {
            const name = item.querySelector('.target-student-name').textContent.toLowerCase();
            if (name.includes(query)) {
                item.style.display = '';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        if (countBadge) {
            countBadge.textContent = count;
        }
    });
</script>
</body>
</html>