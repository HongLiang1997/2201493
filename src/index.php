<?php 
	session_start();

	// Function to sanitize the search term to prevent XSS
	function sanitizeSearchTerm($term) {
		return htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
	}

	// Function to fetch search terms from the database
	function fetchSearchTerms($pdo) {
		$stmt = $pdo->prepare("SELECT term FROM search_terms");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

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

	$searchTerms = fetchSearchTerms($pdo);

	if (isset($_POST['submit'])) {
		if (!empty($_POST['search_term'])) {
			$search_term = trim($_POST['search_term']);
			$sanitized_search_term = sanitizeSearchTerm($search_term);

			if (in_array($sanitized_search_term, $searchTerms)) {
				$_SESSION['search_term'] = $sanitized_search_term;
				header('Location: search_results.php');
				exit;
			} else {
				$errorMsg = "Search term not found";
			}
		} else {
			$errorMsg = "Please enter a search term";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Page</title>
<link rel="stylesheet" href="style.css">
</head>

<body>
	<div class="container">
		<h1>Search Example with XSS and SQL Injection Prevention</h1>
		<?php 
			if(isset($errorMsg))
			{
				echo "<div class='error-msg'>";
				echo $errorMsg;
				echo "</div>";
				unset($errorMsg);
			}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<div class="field-container">
				<label>Search Term</label>
				<input type="text" name="search_term" required placeholder="Enter Search Term">
			</div>
			<div class="field-container">
				<button type="submit" name="submit">Submit</button>
			</div>
		</form>
	</div>
</body>
</html>
