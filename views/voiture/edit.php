{{ include('layouts/header.php', {title: 'Modifier la voiture'}) }}
    <div class="container">
        <form method="post">
            <h2>Modifier la voiture</h2>
            <label>Marque
                <input type="text" name="marque" value="{{ voiture.marque }}">
            </label>
            {% if errors.marque is defined %}
                <span class="error">{{ errors.marque }}</span>
            {% endif %}

            <label>Modèle
                <input type="text" name="modele" value="{{ voiture.modele }}">
            </label>
            {% if errors.modele is defined %}
                <span class="error">{{ errors.modele }}</span>
            {% endif %}

            <label>Année
                <input type="number" name="annee" value="{{ voiture.annee }}">
            </label>
            {% if errors.annee is defined %}
                <span class="error">{{ errors.annee }}</span>
            {% endif %}

            <label>Prix par jour ($)
                <input type="number" name="prix_jour" step="0.01" value="{{ voiture.prix_jour }}">
            </label>
            {% if errors.prix_jour is defined %}
                <span class="error">{{ errors.prix_jour }}</span>
            {% endif %}

            <label>Catégorie
                <select name="categorie_id">
                    <option value="">-- Choisir une catégorie --</option>
                    {% for categorie in categories %}
                    <option value="{{ categorie.id }}" {% if categorie.id == voiture.categorie_id %}selected{% endif %}>{{ categorie.nom }}</option>
                    {% endfor %}
                </select>
            </label>
            {% if errors.categorie_id is defined %}
                <span class="error">{{ errors.categorie_id }}</span>
            {% endif %}

            <input type="submit" class="btn" value="Enregistrer">
            <a href="{{base}}/voitures" class="btn">Annuler</a>
        </form>
    </div>
{{ include('layouts/footer.php') }}