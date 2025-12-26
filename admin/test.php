<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Episode List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .note-input {
            width: 80%;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .update-btn {
            display: none;
            padding: 5px 10px;
            margin-left: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            padding: 5px 10px;
            background-color: #008CBA;
            color: white;
            border: none;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Episode Number</th>
            <th>Episode Name</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="episodeTableBody">
        <!-- Episode rows will be dynamically added here -->
    </tbody>
</table>

<script>
    // Example episode data
    const episodes = [
        { number: 1, name: "Episode 1", note: "Initial Note 1" },
        { number: 2, name: "Episode 2", note: "Initial Note 2" },
        { number: 3, name: "Episode 3", note: "Initial Note 3" },
    ];

    // Function to render the episode table
    function renderTable() {
        const tableBody = document.getElementById('episodeTableBody');
        tableBody.innerHTML = '';

        episodes.forEach((episode, index) => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${episode.number}</td>
                <td>${episode.name}</td>
                <td>
                    <input type="text" class="note-input" id="note-${index}" value="${episode.note}" disabled>
                    <button class="update-btn" id="update-${index}" onclick="updateNote(${index})">Update</button>
                </td>
                <td>
                    <button class="edit-btn" id="edit-${index}" onclick="editEpisode(${index})">Edit</button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    // Function to enable note input and show update button
    function editEpisode(index) {
        document.getElementById(`note-${index}`).disabled = false;
        document.getElementById(`update-${index}`).style.display = 'inline-block';
        document.getElementById(`edit-${index}`).style.display = 'none';
    }

    // Function to update the note and disable input and update button
    function updateNote(index) {
        const noteInput = document.getElementById(`note-${index}`);
        const updateButton = document.getElementById(`update-${index}`);
        const editButton = document.getElementById(`edit-${index}`);

        episodes[index].note = noteInput.value;
        noteInput.disabled = true;
        updateButton.style.display = 'none';
        editButton.style.display = 'inline-block';
    }

    // Render the table on page load
    window.onload = renderTable;
</script>

</body>
</html>
