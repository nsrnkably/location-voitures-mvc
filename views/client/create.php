{{ include('layouts/header.php', {title: 'Nouveau client'}) }}
    <div class="container">
        <form method="post">
            <h2>Nouveau client</h2>
            <label>Nom
                <input type="text" name="nom" value="{{ client.nom }}">
            </label>
            {% if errors.nom is defined %}
                <span class="error">{{ errors.nom }}</span>
            {% endif %}

            <label>Prénom
                <input type="text" name="prenom" value="{{ client.prenom }}">
            </label>
            {% if errors.prenom is defined %}
                <span class="error">{{ errors.prenom }}</span>
            {% endif %}

            <label>Courriel
                <input type="email" name="courriel" value="{{ client.courriel }}">
            </label>
            {% if errors.courriel is defined %}
                <span class="error">{{ errors.courriel }}</span>
            {% endif %}

            <label>Téléphone
                <input type="tel" name="telephone" value="{{ client.telephone }}">
            </label>
            {% if errors.telephone is defined %}
                <span class="error">{{ errors.telephone }}</span>
            {% endif %}

            <input type="submit" class="btn" value="Enregistrer">
            <a href="{{base}}/clients" class="btn">Annuler</a>
        </form>
    </div>
{{ include('layouts/footer.php') }}