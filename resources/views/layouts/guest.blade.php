<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

```
<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

<title>
    {{ config('app.name', 'EsasyExam') }}
</title>


<!-- Fonts -->

<link
    rel="preconnect"
    href="https://fonts.bunny.net"
>

<link
    href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
    rel="stylesheet"
/>


<!-- Vite -->

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])


<!-- Alpine x-cloak -->

<style>

    [x-cloak]{
        display:none !important;
    }

    body{
        font-family:'Figtree',sans-serif;
    }

</style>
```

</head>

<body
class="
font-sans
text-slate-900
antialiased
bg-gradient-to-b
from-blue-50
to-white
min-h-screen
"
>

<div
class="
min-h-screen
flex
items-center
justify-center
px-5
py-10
"
>

<div
class="
w-full
max-w-md
"
>

<!-- Logo -->

<div
class="
text-center
mb-8
"
>

<a
href="/"
wire:navigate

>

<x-application-logo
class="
w-24
h-24
mx-auto
text-blue-600
"
/>

</a>

<h1
class="
mt-4
text-3xl
font-extrabold
text-blue-600
"
>

📘 EsasyExam

</h1>

<p
class="
mt-2
text-slate-500
"
>

Belajar Lebih Cerdas Bukan Lebih Berat 🚀

</p>

</div>

<!-- Card -->

<div
class="
bg-white
rounded-[30px]
shadow-xl
border
border-slate-100
overflow-hidden
"
>

<div
class="
px-8
py-8
"
>

{{ $slot }}

</div>

</div>

<!-- Footer -->

<div
class="
text-center
mt-6
text-sm
text-slate-400
"
>

© 2026 EsasyExam

</div>

</div>

</div>

<!-- Alpine -->

<script
defer
src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
></script>

</body>

</html>
