{{ include('layouts/header.php', {title: 'Clients'}) }}
    <h1>Clients</h1>

    {% if message %}
        <p class="message-succes">{{ message }}</p>
    {% endif %}
    {% if erreur %}
        <p class="message-erreur">{{ erreur }}</p>
    {% endif %}

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Courriel</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {% for client in clients %}
            <tr>
                <td>{{ client.nom }}</td>
                <td>{{ client.prenom }}</td>
                <td>{{ client.courriel }}</td>
                <td>{{ client.telephone }}</td>
                <td class="actions">
                    <a href="{{base}}/client/edit?id={{client.id}}" class="btn">Modifier</a>
                    <form action="{{base}}/client/delete" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?');">
                        <input type="hidden" name="id" value="{{ client.id }}">
                        <button type="submit" class="btn red">Supprimer</button>
                    </form>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="5">Aucun client pour le moment.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    <a href="{{base}}/client/create" class="btn">Nouveau client</a>
{{ include('layouts/footer.php') }}