{{ include('layouts/header.php', {title: 'Voitures'}) }}
    <h1>Voitures</h1>

    {% if message %}
        <p class="message-succes">{{ message }}</p>
    {% endif %}

    <table>
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Année</th>
                <th>Prix / jour</th>
                <th>Catégorie</th>
            </tr>
        </thead>
        <tbody>
            {% for voiture in voitures %}
            <tr>
                <td><a href="{{base}}/voiture/show?id={{voiture.id}}">{{ voiture.marque }}</a></td>
                <td>{{ voiture.modele }}</td>
                <td>{{ voiture.annee }}</td>
                <td>{{ voiture.prix_jour }} $</td>
                <td>{{ voiture.categorie }}</td>
            </tr>
            {% else %}
            <tr>
                <td colspan="5">Aucune voiture pour le moment.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    <a href="{{base}}/voiture/create" class="btn">Nouvelle voiture</a>
{{ include('layouts/footer.php') }}