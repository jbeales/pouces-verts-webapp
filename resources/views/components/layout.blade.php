<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ ($title ?? '')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/forms.css') }}">
</head>
<body>
<header>
    <h1>Liste d'attente<span lang="en-CA">Waitlist</span></h1>
    <img src="/images/logo-couleur.png" width="200" height="200" alt="Le logo de l'organisme les Pouces verts de Verdun">
</header>
{{ $slot }}
<nav class="return-home">
    <a href="https://www.poucesverts.ca/">Accueuil / <span lang="en-CA">Home</span></a>
</nav>
</body>
