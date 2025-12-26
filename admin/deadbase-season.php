<?php
require('deadbase.php');
require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST['action'] == "delete_link") {
        $linkid = $_POST['linkid'];
        $drive_id = $pdo->query("SELECT drive_id FROM EpisodeLinks WHERE link_id = $linkid")->fetch();
        $drive_id = $drive_id['drive_id'];
        $pdo->query("DELETE FROM Links_info WHERE Id = '$drive_id'");
        $pdo->query("DELETE FROM `EpisodeLinks` WHERE link_id = $linkid");
        $pdo->query("DELETE FROM deadstream_playerx WHERE drive_id = '$drive_id'");
        echo "1";
        exit;
    }
    elseif($_POST['action'] == "update-link") {
        $linkid = $_POST['linkid'];
        $drive = $_POST['drive'];
        $quality = $_POST['quality'];
        $order = $_POST['order'];
        $note = $_POST['note'];
        
$up = $pdo->prepare("UPDATE `EpisodeLinks` SET `drive_id` = :drive, `quality` = :quality, `quality_order` = :order, `note` = :note WHERE link_id = :linkid");
$up->execute([':drive' => $drive, ':quality' => $quality, ':order' => $order, ':note' => $note, ':linkid' => $linkid]);

        
     echo "1";
     exit;
    }
    elseif($_POST['action'] == "showLinks") {
        
        $episode_id = $_POST['episode_id'];
        $res = $pdo->query("SELECT EpisodeLinks.link_id,EpisodeLinks.drive_id,EpisodeLinks.quality,EpisodeLinks.quality_order,EpisodeLinks.note,EpisodeLinks.Hindi_Only,Links_info.size FROM `EpisodeLinks` LEFT JOIN Links_info ON Links_info.Id = EpisodeLinks.drive_id JOIN Episodes ON Episodes.episode_id = EpisodeLinks.episode_id WHERE Episodes.episode_id = $episode_id ORDER BY EpisodeLinks.quality_order ASC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($res);
    exit;
    }
    elseif($_POST['action'] == "add_episodes") {
        $msid = $_POST['msid'];
        $start = $_POST['start'];
        $end = $_POST['end'];
       $sql = $pdo->prepare("INSERT INTO Episodes (season_id, my_season_id, episode_name, ep_num, epSort) VALUES (?, ?, ?, ?, ?)");

        for ($i = $start; $i <= $end; $i++) {
            $sql->execute([null, $msid, "", $i, $i]);
        }
        echo "success";
        exit;
    }
    elseif($_POST['action'] == "update_episode") {
    $episode_id = $_POST['episode_id'];
    $epSort = $_POST['epSort'];
    $part = $_POST['part'];
    $note = $_POST['note'];
    $name = $_POST['name'];
    
    $stmt = $pdo->prepare("UPDATE Episodes SET note = ? , episode_name = ? , epSort = ? , part = ? WHERE episode_id = ?");
    $result = $stmt->execute([$note, $name, $epSort, $part, $episode_id]);

    echo json_encode(['success' => "true"]);
    exit; // Exit after responding to prevent further processing}
}
}



$msid = $_GET['id'];
$episodes = $pdo->query("SELECT * FROM Episodes WHERE my_season_id = $msid")->fetchAll(PDO::FETCH_ASSOC);

$title = "Edit Post - WordPress Style";
$headerTitle = "WordPress Style Edit Post";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <div class="content">

<script>

</script>

<div id="add_episode" class="text-center mb-5">
    <div class="d-inline-block">
        <input id="episode_number_start" type="number" class="form-control" placeholder="Start" style="display: none;" value="0">
        <input id="episode_number_end" type="number" class="form-control" placeholder="End" style="display: none;">
    </div>
    <button id="ep_button" class="btn btn-danger d-inline-block" onclick="add_episode(<?php echo $msid; ?>)">+ Add New Episodes</button>
</div>


        
        <strong>Total: <?= count($episodes) ?></strong>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Number</th>
                    <th scope="col">Id</th>
                    <th scope="col">Part</th>
                    <th scope="col">Episode Name</th>
                    <th scope="col">Note</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="episodeTableBody">
                <?php foreach ($episodes as $e): ?>
                    <tr id="row-<?= $e['episode_id'] ?>">
                        <td scope="row" id="epSort-<?= $e['episode_id'] ?>"><?= htmlspecialchars($e['epSort']) ?></td>
                        <td scope="row" ><?= $e['episode_id'] ?></td>
                        <td scope="row" id="part-<?= $e['episode_id'] ?>"><?=$e['part'] ?></td>
                        <td>
                            <input type="text" class="form-control" id="name-<?= htmlspecialchars($e['episode_id']) ?>" value="<?= htmlspecialchars($e['episode_name']) ?>" disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="note-<?= htmlspecialchars($e['episode_id']) ?>" value="<?= htmlspecialchars($e['note']) ?>" disabled>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger" id="edit-<?= htmlspecialchars($e['episode_id']) ?>" onclick="editEpisode(<?php echo $e['episode_id'].",".$e['my_season_id'] ?>)">Edit</button>
                            <button class="btn btn-danger" id="episode-<?= $e['episode_id'] ?>" onclick="showLinks(<?php echo $e['episode_id'].",".$e['my_season_id'] ?>)">Show Links</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    
function showLinks(index, msid) {
    var formData = new FormData();
    formData.append("action", "showLinks");
    formData.append("episode_id", index);

    var linkbox = document.getElementById("linkbox");
    if (linkbox) {
        linkbox.remove();
    }
    var row = document.getElementById(`row-${index}`);

    linkbox = document.createElement("tr");
    linkbox.id = "linkbox";
    var boxbody = document.createElement("td");
    boxbody.colSpan = 5;
    linkbox.appendChild(boxbody);

    var table = document.createElement("table");
    table.style.width = "100%";
    table.style.textAlign = "center";
    boxbody.appendChild(table);

    var thead = document.createElement("thead");
    thead.classList.add("thead-dark");
    var headerRow = document.createElement("tr");

    var headers = ["id", "drive_id", "size", "quality", "order", "note" , "Honly", "action"];
    headers.forEach(function(headerText) {
        var th = document.createElement("th");
        th.textContent = headerText;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create the table body
    var tbody = document.createElement("tbody");

    fetch("", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        data.forEach(function(item) {
            var row = document.createElement("tr");
            row.id = item.link_id;
            var size = formatBytes(item.size);
            row.innerHTML = `
                <td>${item.link_id}</td>
                <td><input id="drive-${item.link_id}" class="form-control" value="${item.drive_id}" disabled/></td>
                <td><input id="drive-${item.link_id}" class="form-control" value="${size}" disabled/></td>
                <td><input id="quality-${item.link_id}" class="form-control" value="${item.quality}" disabled/></td>
                <td><input id="order-${item.link_id}" type="number" class="form-control" value="${item.quality_order}" disabled/></td>
                <td><input id="note-${item.link_id}" type="text" class="form-control" value="${item.note}" disabled/></td>
                <td><input type="text" class="form-control" value="${item.Hindi_Only}" disabled/></td>
                <td>
                <button id="linkButton-${item.link_id}" class="btn btn-danger mr-2" onclick="editLink(${item.link_id}, 'edit')">Edit</button>
                <button id="deleteLink-${item.link_id}" class="btn btn-danger" onclick="editLink(${item.link_id}, 'delete')">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

    table.appendChild(tbody);

    // Insert the linkbox after the specified row
    row.insertAdjacentElement("afterend", linkbox);
    
    console.log(linkbox);
}


        function editLink (linkid,mod) {
            var tr = document.getElementById(linkid);
            var drive = document.getElementById("drive-" + linkid);
            var quality = document.getElementById("quality-" + linkid);
            var order = document.getElementById("order-" + linkid);
            var note = document.getElementById("note-" + linkid);
            var button = document.getElementById("linkButton-" + linkid);
            
                if(mod == "edit") {
            
            drive.disabled = false;
            quality.disabled = false;
            order.disabled = false;
            note.disabled = false;
            
            button.classList = '';
            button.classList.add("btn", "btn-success");
            button.innerText = "Update";
            button.onclick = function() { editLink(linkid,"update")};
            
            
                } else if (mod == "update") {
            
            drive.disabled = true;
            quality.disabled = true;
            order.disabled = true;                
            note.disabled = true;                
                    
                    var formData = new FormData();
                    formData.append("action","update-link");
                    formData.append("linkid",linkid);
                    formData.append("drive",drive.value);
                    formData.append("quality",quality.value);
                    formData.append("order",order.value);
                    formData.append("note",note.value);
            
                fetch("", {
                    method:"post",
                    body: formData
                })    
                .then(response => response.text())
                .then(data => {
                    if(data == 1) {
            button.classList = '';
            button.classList.add("btn", "btn-danger");
            button.innerText = "Edit Link";
            button.onclick = function() { editLink(linkid,"edit")};                    }
                })
                
            
                
                
                    
                } else if (mod == "delete") {
                    
                    var formData = new FormData();
                    formData.append("action","delete_link");
                    formData.append("linkid", linkid);
                   
                fetch("", {
                    method:"post",
                    body: formData
                })    
                .then(response => response.text())
                .then(data => {
                    if(data == 1) {
                        tr.remove();
                            }
                })
                }
            
        }
    
        function editEpisode(index,msid) {
            document.getElementById(`note-${index}`).disabled = false;
            document.getElementById(`name-${index}`).disabled = false;
            document.getElementById("epSort-" + index).contentEditable = true;
            document.getElementById("part-" + index).contentEditable = true;
            var button = document.getElementById(`edit-${index}`);
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
            button.innerText = "Update";
            button.id = "update-" + index;
            button.onclick = function() { updateEpisode(index,msid); };
        }

        function updateEpisode(index,msid) {
            const noteInput = document.getElementById(`note-${index}`);
            const nameInput = document.getElementById(`name-${index}`);
            const button = document.getElementById(`update-${index}`);

            var epSort = document.getElementById("epSort-" + index);
            var part = document.getElementById("part-" + index);

            const formData = new FormData();
            formData.append("action", "update_episode");
            formData.append("episode_id", index);
            formData.append("epSort", epSort.innerText);
            formData.append("part", part.innerText);
            formData.append("note", noteInput.value);
            formData.append("name", nameInput.value);

            // Send the updated note to the server via AJAX
            fetch('', {
                method: 'POST',
                body: formData, // Correctly use FormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    noteInput.disabled = true;
                    nameInput.disabled = true;
                    epSort.contentEditable = false;
                    part.contentEditable = false;
                    button.classList.remove("btn-success");
                    button.classList.add("btn-danger");
                    button.innerText = "Edit";
                    button.id = "edit-" + index;
                    button.onclick = function() { editEpisode(index,msid); };
                } else {
                    alert('Failed to update note.');
                }
            })
            .catch(error => {
                console.error('Error updating note:', error);
                alert('An error occurred. Please try again.');
            });
        }
        
            function add_episode(msid) {
        var div = document.getElementById("add_episode");
        div.classList.remove("text-center");

        var start = document.getElementById("episode_number_start");
        var end = document.getElementById("episode_number_end");
        start.style.display = "inline-block";
        end.style.display = "inline-block";

        var button = document.getElementById("ep_button");
        button.onclick = function() { post_episode(msid); };
    }

    function post_episode(msid) {
        var start = document.getElementById("episode_number_start");
        var end = document.getElementById("episode_number_end");

        var formData = new FormData();
        formData.append('action', "add_episodes");
        formData.append('msid', msid);
        formData.append('start', start.value);
        formData.append('end', end.value);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data == "success") {
                location.reload();
            } else {
                alert('Failed to add episode.');
            }
        })
        .catch(error => {
            console.error('Error adding episode:', error);
            alert('An error occurred. Please try again.');
        });
    }
        
    function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}        
        

    </script>
</div>

<?php include 'footer.php'; ?>
