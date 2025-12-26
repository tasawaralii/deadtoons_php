<?php
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.0/codemirror.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
        }
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            min-width: 250px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
        }
        .toolbar button {
            margin-right: 5px;
        }
        .CodeMirror {
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white text-center py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="mb-0"><?php echo $headerTitle; ?></h1>
            <button class="btn btn-secondary d-lg-none" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                ☰
            </button>
        </div>
    </header>
