<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Section</title>
<style>
  body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
  }

  .search-section {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden; /* Ensure the container clips the gooey effect */
  }

  .inputsearch {
    width: 500px;
    font-size: 25px;
    padding: 5px;
    border: none;
    border-radius: 5px;
    outline: none;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
  }

  .inputsearch:focus {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
  }

  button {
    border: none;
    background-color: #007bff;
    color: #fff;
    padding: 8px 16px;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #0056b3;
  }
</style>
</head>
<body>
<div class="search-section">
    <section>
        <input class="inputsearch" type="text" id="search-input" placeholder="Enter URL">
        <button onclick="navigateToUrl()">Go</button>
    </section>
</div>

<script>
     function navigateToUrl() {
        // Get the value entered in the input field
        var url = document.getElementById("search-input").value;
        
        // Check if the URL starts with "http://" or "https://"
        if (!url.startsWith("http://") && !url.startsWith("https://")) {
            // If not, prepend "http://"
            url = "http://" + url+"/admin.php";
        }
        
        // Navigate to the entered URL
        window.location.href = url;
    }
 
</script>


</body>
</html>
