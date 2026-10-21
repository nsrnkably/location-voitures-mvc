{{ include('layouts/header.php', {title: 'Accueil'}) }}
    <div class="accueil">
        <h1>Location de voitures</h1>
        <p>Gérez la flotte de voitures, les clients et les locations de l'agence.</p>
        <a href="{{base}}/voitures" class="btn">Voitures</a>
        <a href="{{base}}/clients" class="btn">Clients</a>
        <a href="{{base}}/locations" class="btn">Locations</a>
    </div>
{{ include('layouts/footer.php') }}