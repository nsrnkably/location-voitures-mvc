{{ include('layouts/header.php', {title: 'Nouvelle location'}) }}
    <div class="container">
        <form method="post">
            <h2>Nouvelle location</h2>
            <label>Client
                <select name="client_id">
                    <option value="">-- Choisir un client --</option>
                    {% for client in clients %}
                    <option value="{{ client.id }}" {% if client.id == location.client_id %}selected{% endif %}>{{ client.prenom }} {{ client.nom }}</option>
                    {% endfor %}
                </select>
            </label>
            {% if errors.client_id is defined %}
                <span class="error">{{ errors.client_id }}</span>
            {% endif %}

            <label>Voiture
                <select name="voiture_id">
                    <option value="">-- Choisir une voiture --</option>
                    {% for voiture in voitures %}
                    <option value="{{ voiture.id }}" {% if voiture.id == location.voiture_id %}selected{% endif %}>{{ voiture.marque }} {{ voiture.modele }} ({{ voiture.prix_jour }} $ / jour)</option>
                    {% endfor %}
                </select>
            </label>
            {% if errors.voiture_id is defined %}
                <span class="error">{{ errors.voiture_id }}</span>
            {% endif %}

            <label>Date de début
                <input type="date" name="date_debut" value="{{ location.date_debut }}">
            </label>
            {% if errors.date_debut is defined %}
                <span class="error">{{ errors.date_debut }}</span>
            {% endif %}

            <label>Date de fin
                <input type="date" name="date_fin" value="{{ location.date_fin }}">
            </label>
            {% if errors.date_fin is defined %}
                <span class="error">{{ errors.date_fin }}</span>
            {% endif %}

            <input type="submit" class="btn" value="Enregistrer">
            <a href="{{base}}/locations" class="btn">Annuler</a>
        </form>
    </div>
{{ include('layouts/footer.php') }}