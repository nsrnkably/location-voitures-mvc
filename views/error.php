{{ include('layouts/header.php', {title: 'Erreur'}) }}
    <div class="container">
        <h2>Erreur</h2>
        <p class="message-erreur">{{ message }}</p>
        <a href="{{base}}/" class="btn">Retour à l'accueil</a>
    </div>
{{ include('layouts/footer.php') }}