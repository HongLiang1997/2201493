<?php 
	session_start();

	// Redirect to search page if no search term is set in the session
	if (!isset($_SESSION['search_term'])) {
		header('Location: search.php');
		exit;
	}

	$search_term = $_SESSION['search_term'];

	// Database connection
	$dsn = 'mysql:host=your_host;dbname=your_dbname';
	$username = 'your_username';
	$password = 'your_password';
	try {
		$pdo = new PDO($dsn, $username, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die("Database connection failed: " . $e->getMessage());
	}

	// Fetch search results from the database
	$stmt = $pdo->prepare("SELECT result_message FROM search_results WHERE search_term = :search_term");
	$stmt->bindParam(':search_term', $search_term, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchColumn();

	$resultMessage = $result ? $result : "No results found for '$search_term'";
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Results Page</title>
<link rel="stylesheet" href="style.css">
</head>

<body>
	<div class="container">
		<h1>Search Results for "<?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?>"</h1>
		<div class="result-msg">
			<?php echo htmlspecialchars($resultMessage, ENT_QUOTES, 'UTF-8'); ?>
		</div>
		<div class="field-container">
			<a href="search.php">Back to Search</a>
		</div>
	</div>
</body>
</html>
