{{ include('layouts/header.php', {title: 'Locations'}) }}
    <h1>Locations</h1>

    {% if message %}
        <p class="message-succes">{{ message }}</p>
    {% endif %}

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Voiture</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Durée</th>
                <th>Montant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {% for location in locations %}
            <tr>
                <td>{{ location.prenom }} {{ location.nom }}</td>
                <td>{{ location.marque }} {{ location.modele }}</td>
                <td>{{ location.date_debut }}</td>
                <td>{{ location.date_fin }}</td>
                <td>{{ location.jours }} jour(s)</td>
                <td>{{ (location.jours * location.prix_jour)|number_format(2, ',', ' ') }} $</td>
                <td class="actions">
                    <a href="{{base}}/location/edit?id={{location.id}}" class="btn">Modifier</a>
                    <form action="{{base}}/location/delete" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cette location ?');">
                        <input type="hidden" name="id" value="{{ location.id }}">
                        <button type="submit" class="btn red">Supprimer</button>
                    </form>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="7">Aucune location pour le moment.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    <a href="{{base}}/location/create" class="btn">Nouvelle location</a>
{{ include('layouts/footer.php') }}