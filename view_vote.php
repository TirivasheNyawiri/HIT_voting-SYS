<?php include('db_connect.php');?>
<?php
    // Fetch the end_voting date from the dates table
    $endVotingDateResult = $conn->query("SELECT end_voting FROM dates WHERE id = 1");
    $endVotingDate = $endVotingDateResult->fetch_assoc()['end_voting'];

    // Check if the current date is past the end_voting date
    $currentDate = date("Y-m-d");
    $isVotingOver = ($currentDate > $endVotingDate);
?>

<style>
    .candidate {
        margin: auto;
        width: 16vw;
        padding: 10px;
        border-radius: 3px;
        margin-bottom: 1em
    }
    .candidate img {
        height: 14vh;
        width: 8vw;
        margin: auto;
    }
    #countdown {
        font-size: 50px; /* Increased font size */
        margin-top: 20px;
        display: flex; /* Use Flexbox */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        height: 100vh; /* Full viewport height */
    }
    #countdown.ended {
        font-size: 35px; /* Smaller font size for the message */
    }
</style>

<?php if ($isVotingOver): ?>
    <a class="btn btn-primary btn-sm col-md-2 float-right" href="voting.php?page=home">View Poll</a>
<?php else: ?>
    <p font-size: 35px>The voting period has not yet ended to view the results. Please check back after :</p>
<?php endif ?>

<div id="countdown"></div>

<script>
    // Convert the end_voting date to a JavaScript Date object
    var endVotingDate = new Date("<?php echo $endVotingDate; ?>");
    var countdownElement = document.getElementById('countdown');

    function updateCountdown() {
        var now = new Date();
        var timeRemaining = endVotingDate - now;

        if (timeRemaining <= 0) {
            clearInterval(countdownInterval);
            countdownElement.innerHTML = "Voting period has ended.";
            countdownElement.classList.add('ended'); // Add class to change font size
            return;
        }

        var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

        countdownElement.innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds ";
    }

    // Update the countdown every second
    var countdownInterval = setInterval(updateCountdown, 1000);
</script>
