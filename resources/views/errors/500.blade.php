<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            background: linear-gradient(#111, #333, #111);
            background-repeat: no-repeat;
            background-size: cover;
            color: #eee;
            position: relative;
            font-family: 'Roboto', sans-serif;
        }

        .message {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .message h1,
        .message h2,
        .message h3 {
            margin: 0;
            line-height: .8;
        }

        .message h2,
        .message h3 {
            font-weight: 300;
            color: #C8FFF4;
            /* $light-text-color */
        }

        .message h1 {
            font-weight: 700;
            color: #03DAC6;
            /* $dark-text-color */
            font-size: 8em;
        }

        .message h2 {
            margin: 30px 0;
        }

        .message h3 {
            font-size: 2.5em;
        }

        .message h4 {
            display: inline-block;
            margin: 0 15px;
        }

        .message button {
            background: transparent;
            border: 2px solid #C8FFF4;
            /* $light-text-color */
            color: #C8FFF4;
            /* $light-text-color */
            padding: 5px 15px;
            font-size: 1.25em;
            transition: all 0.15s ease;
            border-radius: 3px;
        }

        .message button:hover {
            background: #03DAC6;
            /* $dark-text-color */
            border: 2px solid #03DAC6;
            /* $dark-text-color */
            color: #111;
            cursor: pointer;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="message">
        <h1>500</h1>
        <h3>Server Error</h3>
        <h2>Lỗi hệ thống! vui lòng liên hệ với quản trị viên hoặc thử lại sau</h2>
        <!-- use window.history.back(); to go back -->
        <button onclick="window.history.back();">Go Back</button>
    </div>
</body>

</html>
