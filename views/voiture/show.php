{{ include('layouts/header.php', {title: voiture.marque ~ ' ' ~ voiture.modele}) }}
    <div class="container">
        <h2>{{ voiture.marque }} {{ voiture.modele }}</h2>

        {% if erreur %}
            <p class="message-erreur">{{ erreur }}</p>
        {% endif %}

        <p><strong>Marque : </strong>{{ voiture.marque }}</p>
        <p><strong>Modèle : </strong>{{ voiture.modele }}</p>
        <p><strong>Année : </strong>{{ voiture.annee }}</p>
        <p><strong>Prix par jour : </strong>{{ voiture.prix_jour }} $</p>
        <p><strong>Prix pour une semaine : </strong>{{ voiture.prix_jour * 7 }} $</p>
        <p><strong>Catégorie : </strong>{{ voiture.categorie }}</p>

        <a href="{{base}}/voiture/edit?id={{voiture.id}}" class="btn">Modifier</a>
        <form action="{{base}}/voiture/delete" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cette voiture ?');">
            <input type="hidden" name="id" value="{{ voiture.id }}">
            <button type="submit" class="btn red">Supprimer</button>
        </form>
        <a href="{{base}}/voitures" class="btn">Retour à la liste</a>
    </div>
{{ include('layouts/footer.php') }}