<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $site->name }}</title>

    <style>
        /* Style the tab */
        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            position: fixed;
            top: 0;
            width: 100%;
            left: 0;
            z-index: 2;
        }

        /* Style the buttons inside the tab */
        div.tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        div.tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            border: 1px solid #ccc;
            border-top: none;
            border-bottom: none;
        }
        .email_preview_frame {
            margin: 0;
            border: none;
            width: 100%;
            height: calc(100vh - 50px);
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <iframe class="email_preview_frame" src="{{ action('SiteController@previewContent', $site->uid) }}"></iframe>
</body>

</html>

