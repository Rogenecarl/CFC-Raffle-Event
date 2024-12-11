<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Name Lucky Draw</title>
  <link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css">
  <style>
    /* Global Styles */
    body {
      background: linear-gradient(to right, #ff9a8b, #a7d8b6, #6d88d3);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #444;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100%;
      max-width: 700px;
      padding: 20px;
    }

    /* Header Section */
    .raffle-header {
      background: rgba(255, 255, 255, 0.85);
      width: 60rem;
      color: #f98c88;
      font-size: 4rem; /* Increased title font size */
      font-weight: 700;
      padding: 30px;
      border-radius: 25px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
      text-align: center;
      text-transform: uppercase;
      animation: fadeIn 2s ease-in-out;
    }

    /* Raffle Card */
    .raffle-card {
      width: 100%;
      max-width: 500px;
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease-in-out;
      background: transparent;
      padding: 20px;
    }

    .raffle-card:hover {
      transform: scale(1.05);
    }

    /* Main Content */
    .raffle-main {
      background-color: #ffffff;
      color: #f98c88;
      font-size: 2.5rem;
      font-weight: 600;
      height: 180px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 20px;
      text-transform: uppercase;
      border: 4px solid #f98c88;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      animation: blink 1s infinite;
    }

    /* Animation for the name blinking */
    @keyframes blink {
      50% {
        opacity: 0.6;
      }
    }

    /* Footer Section */
    .raffle-footer {
      display: flex;
      justify-content: center;
      padding-top: 20px;
    }

    /* Button Style */
    .draw {
      background-color: #f98c88;
      color: white;
      padding: 16px 32px;
      font-size: 1.6rem;
      font-weight: bold;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .draw:hover {
      background-color: #f86b8c;
      transform: scale(1.05);
    }

    .draw:active {
      background-color: #f44b65;
    }

    /* Modal Header */
    .modal-header {
      background-color: #f98c88;
      color: white;
    }

    .modal-title {
      font-weight: 700;
      text-transform: uppercase;
    }

    /* Modal Body */
    .modal-body h2 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #f98c88;
    }

    /* Toast Notifications */
    .toast-container {
      z-index: 1050;
    }

    .btn-close-white {
      filter: brightness(0) invert(1);
    }

    .toast.bg-success {
      background-color: #f98c88;
      color: white;
    }

    .toast.bg-danger {
      background-color: #e57373;
      color: white;
    }

    /* Raffle Name */
    .raffle-name {
      font-size: 2.5rem;
      font-weight: bold;
      text-transform: uppercase;
      color: #ff4081;
    }

    /* Animation for Fade In */
    @keyframes fadeIn {
      0% {
        opacity: 0;
      }

      100% {
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <div class="container">
    <!-- Header -->
    <div class="raffle-header">
      <h1>🎉 CFC Thanksgiving Raffle Event 🎉</h1>
    </div>

    <!-- Main Card -->
    <div class="raffle-card shadow-lg">
      <div class="raffle-main m-3">
        <div id="nameDisplay" class="raffle-name text-center  ">Loading names...</div>
      </div>
      <div class="raffle-footer">
        <button class="draw" id="drawButton">Draw</button>
      </div>
    </div>

    <!-- Winner Modal -->
    <div class="modal fade" id="winnerModal" tabindex="-1" aria-labelledby="winnerModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="winnerModalLabel">🎉 Winner Announcement 🎉</h5>
          </div>
          <div class="modal-body text-center">
            <h2 id="winnerName">Winner's Name</h2>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Name Modal -->
    <div class="modal fade" id="addNameModal" tabindex="-1" aria-labelledby="addNameModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNameModalLabel">Add a Name</h5>
          </div>
          <div class="modal-body">
            <input type="text" id="newName" class="form-control" placeholder="Enter name">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="addNameButton">Add Name</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="successToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Success</strong>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Name added successfully!</div>
      </div>
      <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Error</strong>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Name already exists!</div>
      </div>
    </div>
  </div>

  <script src="Bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    let names = [];
    let interval;
    let isDrawing = false;

    const nameDisplay = document.getElementById("nameDisplay");
    const drawButton = document.getElementById("drawButton");
    const winnerModal = new bootstrap.Modal(document.getElementById("winnerModal"));
    const winnerName = document.getElementById("winnerName");

    // Fetch names from database
    function fetchNames() {
      fetch("fetch_names.php")
        .then(response => response.json())
        .then(data => {
          names = data;
          startScrolling();
        });
    }

    // Start slow scrolling names
    function startScrolling(intervalTime = 1500) {
      let index = 0;
      interval = setInterval(() => {
        if (names.length === 0) {
          nameDisplay.textContent = "No names available!";
          return;
        }
        nameDisplay.textContent = names[index].name;
        index = (index + 1) % names.length;
      }, intervalTime);
    }

    // Stop scrolling
    function stopScrolling() {
      clearInterval(interval);
    }

    // Draw a winner with slow scrolling animation
    drawButton.addEventListener("click", () => {
      if (names.length === 0 || isDrawing) {
        alert("No names to draw or a draw is already in progress!");
        return;
      }

      isDrawing = true;
      drawButton.disabled = true;
      let index = 0;
      const scrollInterval = 1000; // Slow scroll speed (1000ms between each name)
      const drawDuration = 5000; // Total time for the draw animation (5 seconds)

      // Start slow name scrolling
      const scrollAnimation = setInterval(() => {
        nameDisplay.textContent = names[index].name;
        index = (index + 1) % names.length;
      }, scrollInterval);

      // Stop scrolling after the duration and show the winner
      setTimeout(() => {
        clearInterval(scrollAnimation);
        const winnerIndex = Math.floor(Math.random() * names.length);
        const winner = names[winnerIndex];
        nameDisplay.textContent = winner.name; // Display winner's name
        winnerName.textContent = winner.name; // Show in modal
        winnerModal.show();

        // Remove the winner from the database
        fetch(`remove_winner.php?id=${winner.id}`).then(() => {
          fetchNames(); // Refresh the names list
          drawButton.disabled = false;
          isDrawing = false;
        });
      }, drawDuration);
    });

    document.addEventListener("DOMContentLoaded", () => {
      const addNameButton = document.getElementById("addNameButton");
      const newNameInput = document.getElementById("newName");

      // Initialize Bootstrap toast
      const successToast = new bootstrap.Toast(document.getElementById("successToast"));
      const errorToast = new bootstrap.Toast(document.getElementById("errorToast"));

      // Add a new name
      addNameButton.addEventListener("click", () => {
        const newName = newNameInput.value.trim();
        if (!newName) {
          document.querySelector("#errorToast .toast-body").textContent = "Name cannot be empty!";
          errorToast.show();
          return;
        }

        fetch("add_name.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `name=${encodeURIComponent(newName)}`
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              document.querySelector("#successToast .toast-body").textContent = data.message;
              successToast.show();
              newNameInput.value = "";
              fetchNames(); // Refresh the list of names
            } else {
              document.querySelector("#errorToast .toast-body").textContent = data.message;
              errorToast.show();
            }
          })
          .catch(() => {
            document.querySelector("#errorToast .toast-body").textContent = "An error occurred.";
            errorToast.show();
          });
      });

      // Fetch names on page load
      fetchNames();
    });
  </script>

</body>

</html>
