<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <!-- As you can see, we will use vite with jsx syntax for React-->
    @inertiaHead
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>

    <style>
        .background-gradient {
            background: rgb(68, 118, 231);
            background: linear-gradient(0deg,
                    rgba(68, 118, 231, 1) 0%,
                    rgba(243, 244, 246, 1) 100%);
        }
    </style>
</head>

<body>
    @inertia
</body>

</html>
