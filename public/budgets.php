<?php
require __DIR__ . '/../src/helpers/url.php';
require __DIR__ . '/../src/helpers/flash.php';
require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
require_once __DIR__ . '/../src/bootstrap.php';

$title = "Budgets - TraceX";
$errors = [];

function normalizeBudgetMonth(string $value): ?string
{
    $value = trim($value);

    if (preg_match('/^\d{4}-\d{2}$/', $value) === 1) {
        $ts = strtotime($value . '-01');
        return $ts === false ? null : date('Y-m-01', $ts);
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
        $ts = strtotime($value);
        return $ts === false ? null : date('Y-m-01', $ts);
    }

    return null;
}

$hasCategoryUserId = tableHasColumn($pdo, 'categories', 'user_id');
if ($hasCategoryUserId) {
    $categoryStmt = $pdo->prepare("SELECT id, name FROM categories WHERE user_id IS NULL OR user_id = :user_id ORDER BY name ASC");
    $categoryStmt->execute([':user_id' => $_SESSION['user_id']]);
} else {
    $categoryStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
}
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveBudget'])) {
    $categoryId = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $amount = $_POST['amount'] ?? '';
    $monthInput = $_POST['month_year'] ?? '';
    $monthYear = normalizeBudgetMonth((string) $monthInput);

    if ($categoryId <= 0) {
        $errors['category_id'] = 'Category is required.';
    }
    if (!is_numeric($amount) || (float) $amount <= 0) {
        $errors['amount'] = 'Amount must be greater than zero.';
    }
    if ($monthYear === null) {
        $errors['month_year'] = 'Month is required.';
    }

    if (!isset($errors['category_id'])) {
        if ($hasCategoryUserId) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id AND (user_id IS NULL OR user_id = :user_id) LIMIT 1");
            $stmt->execute([
                ':id' => $categoryId,
                ':user_id' => $_SESSION['user_id'],
            ]);
        } else {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $categoryId]);
        }

        if (!$stmt->fetchColumn()) {
            $errors['category_id'] = 'Selected category is invalid.';
        }
    }

    if (empty($errors)) {
        $dupStmt = $pdo->prepare("
            SELECT id
            FROM budgets
            WHERE user_id = :user_id
              AND category_id = :category_id
              AND month_year = :month_year
            LIMIT 1
        ");
        $dupStmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':category_id' => $categoryId,
            ':month_year' => $monthYear,
        ]);

        if ($dupStmt->fetchColumn()) {
            $errors['duplicate'] = 'Budget already exists for this category and month.';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO budgets (user_id, category_id, amount, month_year)
                VALUES (:user_id, :category_id, :amount, :month_year)
            ");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':category_id' => $categoryId,
                ':amount' => (float) $amount,
                ':month_year' => $monthYear,
            ]);
            setFlash('success', 'Budget has been added!');
            header("Location: budget.php");
            exit;
        } catch (PDOException $e) {
            if (($e->getCode() ?? '') === '23000') {
                setFlash('error', 'Budget already exists for this category and month.');
            } else {
                setFlash('error', 'Something went wrong while creating budget.');
            }
            header("Location: budget.php");
            exit;
        }
    }

    setFlash('error', array_values($errors)[0]);
    header("Location: budget.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdateBudget'])) {
    $id = isset($_POST['edit_budget_id']) ? (int) $_POST['edit_budget_id'] : 0;
    $categoryId = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $amount = $_POST['amount'] ?? '';
    $monthInput = $_POST['month_year'] ?? '';
    $monthYear = normalizeBudgetMonth((string) $monthInput);

    if ($id <= 0) {
        $errors['id'] = 'Invalid budget ID.';
    }
    if ($categoryId <= 0) {
        $errors['category_id'] = 'Category is required.';
    }
    if (!is_numeric($amount) || (float) $amount <= 0) {
        $errors['amount'] = 'Amount must be greater than zero.';
    }
    if ($monthYear === null) {
        $errors['month_year'] = 'Month is required.';
    }

    if (!isset($errors['category_id'])) {
        if ($hasCategoryUserId) {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id AND (user_id IS NULL OR user_id = :user_id) LIMIT 1");
            $stmt->execute([
                ':id' => $categoryId,
                ':user_id' => $_SESSION['user_id'],
            ]);
        } else {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $categoryId]);
        }

        if (!$stmt->fetchColumn()) {
            $errors['category_id'] = 'Selected category is invalid.';
        }
    }

    if (empty($errors)) {
        $dupStmt = $pdo->prepare("
            SELECT id
            FROM budgets
            WHERE user_id = :user_id
              AND category_id = :category_id
              AND month_year = :month_year
              AND id <> :id
            LIMIT 1
        ");
        $dupStmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':category_id' => $categoryId,
            ':month_year' => $monthYear,
            ':id' => $id,
        ]);

        if ($dupStmt->fetchColumn()) {
            $errors['duplicate'] = 'Budget already exists for this category and month.';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE budgets
                SET category_id = :category_id,
                    amount = :amount,
                    month_year = :month_year
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $_SESSION['user_id'],
                ':category_id' => $categoryId,
                ':amount' => (float) $amount,
                ':month_year' => $monthYear,
            ]);

            if ($stmt->rowCount() === 0) {
                setFlash('error', 'Budget not found or access denied.');
                header("Location: budget.php");
                exit;
            }

            setFlash('success', 'Budget has been updated!');
            header("Location: budget.php");
            exit;
        } catch (PDOException $e) {
            if (($e->getCode() ?? '') === '23000') {
                setFlash('error', 'Budget already exists for this category and month.');
            } else {
                setFlash('error', 'Something went wrong while updating budget.');
            }
            header("Location: budget.php");
            exit;
        }
    }

    setFlash('error', array_values($errors)[0]);
    header("Location: budget.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDeleteBudget'])) {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id <= 0) {
        setFlash('error', 'Invalid budget ID.');
        header("Location: budget.php");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM budgets WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':id' => $id,
        ':user_id' => $_SESSION['user_id'],
    ]);

    if ($stmt->rowCount() === 0) {
        setFlash('error', 'Budget not found or access denied.');
        header("Location: budget.php");
        exit;
    }

    setFlash('success', 'Budget has been deleted!');
    header("Location: budget.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        b.*,
        c.name AS category_name,
        COALESCE(SUM(e.amount), 0) AS spent_amount
    FROM budgets b
    LEFT JOIN categories c ON c.id = b.category_id
    LEFT JOIN expenses e
      ON e.user_id = b.user_id
     AND e.category_id = b.category_id
     AND YEAR(e.expense_date) = YEAR(b.month_year)
     AND MONTH(e.expense_date) = MONTH(b.month_year)
    WHERE b.user_id = :user_id
    GROUP BY b.id, b.user_id, b.category_id, b.amount, b.month_year, b.created_at, b.updated_at, c.name
    ORDER BY b.month_year DESC, b.id DESC
");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
include __DIR__ . '/../views/budgets/budget-view.php';
$content = ob_get_clean();
include __DIR__ . '/../views/components/layout.php';

$flash = getFlash();
if ($flash):
?>
<script>
  Swal.fire({
    toast: true,
    position: "top-end",
    icon: "<?= $flash['type'] ?>",
    title: <?= json_encode($flash['message']) ?>,
    showConfirmButton: false,
    timer: 1500,
    width: "500px",
    timerProgressBar: true
  });
</script>
<?php endif; ?>
