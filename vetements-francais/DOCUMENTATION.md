# Lylium — Fiche technique

Documentation à destination des développeurs qui reprennent ou font évoluer ce projet.
Site vitrine + compte client pour une marque de vêtements fabriqués en France. Pas de
tunnel de commande/paiement réel à ce stade (voir [Dette technique & limites connues](#dette-technique--limites-connues)).

## Stack

| Composant       | Détail                                                              |
|-----------------|----------------------------------------------------------------------|
| Backend         | PHP natif (pas de framework), procédural + quelques fonctions utilitaires |
| Base de données | SQLite (fichier unique, via PDO)                                     |
| Frontend        | HTML généré côté serveur, CSS pur (variables CSS, pas de préprocesseur), JS vanilla (aucune dépendance front) |
| Email           | PHPMailer 7.x (Composer), SMTP (Gmail testé)                          |
| Auth            | Google Identity Services (Sign in with Google), session PHP native   |
| Dépendances     | Composer (`phpmailer/phpmailer` uniquement)                          |

Aucun bundler, aucun build step côté front : les fichiers dans `css/` et `js/` sont
servis tels quels.

## Installation locale

Prérequis : PHP 8+ avec l'extension `pdo_sqlite`, et Composer.

```bash
cd vetements-francais
composer install
cp includes/mail-config.example.php includes/mail-config.php   # puis renseigner de vrais identifiants SMTP
php -S 127.0.0.1:8090
```

La base SQLite (`database/lylium.sqlite`) et ses tables sont créées automatiquement
au premier appel à `includes/db.php` — rien à initialiser manuellement. Elle est
exclue de git (`.gitignore` à la racine du dépôt), tout comme `includes/mail-config.php`
et `vendor/`.

## Arborescence

```
vetements-francais/
├── index.php                  Accueil (intro plein écran + hero + sections éditoriales)
├── collection.php              Catalogue complet, filtres + tri
├── recherche.php               Recherche produit (nom/description)
├── produit.php?id=N            Fiche produit (avis, taille, favoris)
├── categorie/
│   ├── homme.php / femme.php / enfant.php   Catalogue filtré par catégorie
├── marque.php                  Page "La marque" (éditorial, sans photo)
├── lookbook.php                 Éditorial "silhouettes" (2 vraies photos produit)
├── livraison-retours.php       FAQ (accordéon natif <details>)
├── connexion.php                Connexion (Google Sign-In)
├── auth/google-callback.php     Callback du token Google, crée/relie l'utilisateur
├── deconnexion.php              Détruit la session
├── mon-compte.php               Tableau de bord compte (nécessite connexion)
├── favoris.php                  Liste des favoris (nécessite connexion)
├── mes-commandes.php            Historique de commandes (nécessite connexion, vide pour l'instant)
├── favoris-toggle.php           Endpoint POST : ajoute/retire un favori
├── newsletter.php                Endpoint POST : inscription newsletter + email de bienvenue
├── contact.php                  Formulaire de contact (envoi email réel)
├── includes/
│   ├── header.php               <head>, anti-flash thème sombre
│   ├── navbar.php                Nav (état actif via $navActive, bouton thème)
│   ├── footer.php                Footer + bandeau newsletter (toutes les pages)
│   ├── session.php               session_start, helpers auth + CSRF
│   ├── db.php                    Connexion PDO + (re)création du schéma
│   ├── produits-data.php         Catalogue produit (tableau PHP en dur, pas de table SQL)
│   ├── produit-card.php          Partial carte produit (photo ou pastille couleur)
│   ├── account-hero.php          Partial bandeau + onglets du compte
│   ├── mail-config.php           Secrets SMTP (non versionné)
│   └── mail-config.example.php   Modèle du fichier ci-dessus
├── database/
│   ├── schema.sql                 Toutes les tables, en CREATE TABLE IF NOT EXISTS
│   └── lylium.sqlite               Fichier de données (non versionné)
├── css/style.css                  Toute la feuille de style du site (~1500 lignes)
├── js/app.js                      Tout le JS du site (plusieurs IIFE indépendantes)
├── images/                        Photos (hero, ateliers, homme-hero, 2 photos produit)
└── vendor/                        Dépendances Composer (non versionné)
```

Le dossier `docs/` à la racine du dépôt (en dehors de `vetements-francais/`) est une
**démo statique HTML** de la page d'accueil, publiée sur GitHub Pages (`gh-pages`
n'existe pas : c'est le dossier `/docs` de `master`). Elle ne reflète que l'accueil,
pas le reste du site (PHP/SQLite non exécutables sur GitHub Pages) — voir la section
dédiée plus bas.

## Base de données

SQLite via PDO (`includes/db.php`). Le schéma est **rejoué à chaque connexion** :
chaque instruction de `database/schema.sql` est un `CREATE TABLE IF NOT EXISTS`,
donc ajouter une table = l'ajouter dans `schema.sql`, aucune migration à écrire.

| Table                | Rôle                                                        |
|----------------------|--------------------------------------------------------------|
| `users`               | Comptes (créés uniquement via Google Sign-In)                |
| `favoris`              | Liaison user_id / produit_id                                  |
| `avis`                 | Avis produit (nom, note 1-5, commentaire ; `user_id` nullable si non connecté) |
| `newsletter_abonnes`    | Emails inscrits à la newsletter                               |
| `commandes` / `commande_articles` | Prêtes pour un futur tunnel de commande — **vides**, rien ne les remplit actuellement |

Le catalogue produit **n'est pas en base** : il vit dans `includes/produits-data.php`
sous forme de tableau PHP (`$produits`, indexé par id entier). C'est un choix
délibéré de départ (site vitrine sans vrai back-office) — à migrer vers une vraie
table `produits` le jour où un admin ou un panier est nécessaire.

Chaque produit :
```php
$produits[5] = [
    'nom' => '...', 'prix' => 135, 'categorie' => 'homme',
    'image' => 'images/produits/xxx.jpg',   // optionnel
    'couleur' => '#e8e1d3',                 // optionnel, utilisé si pas d'image
    'tailles' => ['S', 'M', 'L', 'XL'],      // optionnel
    'description' => '...',
];
```
Si `image` est absent/vide, `includes/produit-card.php` (et `produit.php`) affichent
une pastille de couleur unie avec le nom du produit plutôt qu'une image cassée.

## Authentification & session

- `includes/session.php` : `session_start()`, plus les helpers `estConnecte()`,
  `utilisateurConnecte()` (lit `$_SESSION['user_id']`/`user_nom']`), `exigerConnexion()`
  (redirige vers `connexion.php` si non connecté).
- Connexion **uniquement** via Google Identity Services (bouton "Sign in with Google"
  sur `connexion.php`). Le token est vérifié côté serveur dans
  `auth/google-callback.php` via `https://oauth2.googleapis.com/tokeninfo` (pas de
  librairie officielle Google installée). L'utilisateur est créé ou retrouvé par
  `google_id`, puis la session est peuplée.
- **CSRF** : `csrfToken()` / `csrfChamp()` (génère le champ caché HTML) /
  `csrfVerifie($token)` dans `session.php`. Appliqué sur tous les formulaires POST
  qui modifient un état : contact, favoris-toggle, avis (dans `produit.php`),
  newsletter. **Si tu ajoutes un nouveau formulaire POST, pense à `csrfChamp()` +
  `csrfVerifie()`.**

## Emails (PHPMailer)

`includes/mail-config.php` (non versionné, à créer depuis `.example.php`) contient
les identifiants SMTP + adresses expéditeur/destinataire. Deux usages actuels :
- `contact.php` : envoie le message du formulaire de contact vers `to_email`.
- `newsletter.php` : envoie un email de bienvenue à l'adresse qui vient de s'inscrire.

Les deux vérifient `file_exists($configPath)` avant d'instancier PHPMailer, donc le
site ne casse pas si le fichier de config est absent (juste : pas d'email envoyé,
message d'erreur clair côté contact ; silencieux côté newsletter).

## Design system (css/style.css)

Tout tient dans un seul fichier, organisé approximativement par zone (header, hero,
sections homepage, pages compte, composants produit, footer...). Points clés :

**Variables CSS** (`:root`, redéfinies dans `[data-theme="dark"]`) :
`--color-bg`, `--color-text`, `--color-muted`, `--color-line`, `--color-accent`,
`--color-surface`, `--color-bg-alt`, `--color-header-scrolled` — celles-ci **doivent
inverser** entre thème clair et sombre.

`--color-ink` et `--color-paper` sont **volontairement fixes** (ne changent jamais
avec le thème) : utilisées par les bandeaux qui sont *toujours* sombres par design —
`.hero`, `.category-hero`, `.editorial-banner`, `.login-visual`, `.contact-visual`,
`.account-hero`, `.site-intro`, `.produit-cta`, `.newsletter-band`. **Ne jamais leur
appliquer `var(--color-text)`/`var(--color-bg)`**, ça casserait le mode sombre.

**Mode sombre** : toggle dans la navbar (`#themeToggle`), attribut `data-theme="dark"`
sur `<html>`, choix mémorisé dans `localStorage` (`lylium_theme`), sinon
`prefers-color-scheme`. Script anti-flash inline dans `includes/header.php` (doit
s'exécuter avant le premier paint, ne pas le déplacer dans `app.js`).

**`.grain-overlay`** : texture de bruit en SVG inline (data-URI), aucune image —
posée en `position:absolute; inset:0` sur les sections sombres pour donner de la
matière sans photo.

**Système `.reveal`** : classe posée sur n'importe quel élément pour un fondu +
translation au scroll, déclenché une fois par un `IntersectionObserver` dans `app.js`.
`--reveal-delay` (custom property inline, ex. `style="--reveal-delay:0.15s"`) permet
d'échelonner plusieurs éléments. Variante `.reveal-image` (léger zoom-out en plus).

**`.eyebrow`** : petit label tracké en majuscules au-dessus des titres de section,
utilisé pour la cohérence éditoriale sur tout le site.

**Intro plein écran** (`.site-intro`, page d'accueil uniquement) : rideau qui joue
une fois par session (`sessionStorage.lylium_intro_seen`), passable au clic/scroll/
touche, respecte `prefers-reduced-motion`. Logique dans `app.js`.

## JavaScript (js/app.js)

Pas de module bundler : une suite d'IIFE indépendantes, chacune avec un garde
`if (!element) return;` pour ne s'exécuter que sur les pages concernées :

1. Menu mobile (`#navToggle` / `#mainNav`)
2. Bascule thème (`#themeToggle`)
3. Auto-soumission des filtres de `collection.php` (`.collection-toolbar select`)
4. Système `.reveal` (IntersectionObserver)
5. Intro plein écran (`#siteIntro`)
6. Navbar transparente → opaque après le hero (`#hero`, page d'accueil)

## Conventions

- **Noms en français** partout : variables, fonctions, routes (`connexion.php`,
  `mes-commandes.php`...), à respecter pour rester cohérent.
- `htmlspecialchars()` systématique sur toute donnée affichée (produit, avis, champs
  de formulaire ré-affichés après erreur).
- `$navActive` (variable définie avant `include 'includes/navbar.php'`) pilote l'état
  actif des liens de nav : valeurs possibles `'homme'|'femme'|'enfant'|'collection'|'marque'`.
- `$accountActiveTab` (avant `include 'includes/account-hero.php'`) : `'presentation'|'favoris'|'commandes'`.
- Chemins d'images en absolu (`/images/...`) dans le PHP servi par Laragon (grâce à
  `<base href="/">` dans `header.php`) — **ne pas confondre avec `docs/`** où les
  chemins doivent être relatifs (voir plus bas).

## Le dossier docs/ (démo GitHub Pages)

`docs/index.html` est une **copie manuelle, figée** du rendu de `index.php` (HTML +
CSS + JS + les 2 photos produit + hero/ateliers/homme-hero), adaptée pour être servie
en pages statiques GitHub à `https://<user>.github.io/<repo>/` :
- Pas de PHP : aucune donnée dynamique, favoris/compte/commandes non fonctionnels.
- `collection.html`/`404.html` sont des pages d'excuse ("cette démo ne présente que
  l'accueil") plutôt que des vrais liens vers les autres pages.
- **Piège d'encodage/chemins à connaître** : les fichiers `docs/*.html` référencent
  des chemins relatifs à *leur propre* position, alors que `docs/css/style.css`
  référence (pour ses éventuels `url(...)`) des chemins relatifs à *son propre*
  dossier `css/` — d'où `../images/...` dans le CSS mais `images/...` dans le HTML.
  Ne jamais modifier ces fichiers via un pipeline PowerShell `Get-Content | Set-Content`
  sans forcer l'encodage UTF-8 des deux côtés (des accents ont déjà été corrompus
  une fois de cette façon).
- Si `index.php` évolue, **il faut répercuter manuellement** les changements dans
  `docs/index.html` (et retrigger un build Pages via l'API GitHub ou un nouveau commit).

## Dette technique & limites connues

- **Pas de panier ni de paiement.** Chaque fiche produit ne propose que "Ajouter aux
  favoris" et "Nous contacter". `mes-commandes.php` et les tables `commandes` /
  `commande_articles` existent mais resteront vides tant qu'aucun tunnel d'achat
  n'est branché (Shopify évoqué comme piste, non implémenté).
- **Catalogue en dur dans un fichier PHP**, pas en base — à migrer si un back-office
  devient nécessaire.
- **Pas de vraies photos** pour la majorité des produits (pastille de couleur à la
  place) : à remplacer au fur et à mesure que de vraies photos produit existent.
- **Pas d'hébergement de production** : le site tourne en local (Laragon/PHP built-in
  server) ; seule la page d'accueil a une vitrine statique publique (GitHub Pages).
- **Compte 100% Google Sign-In**, pas d'inscription email/mot de passe classique.
- Les alertes de succès/erreur (`.alert-success`/`.alert-error`) ont des couleurs
  fixes non adaptées au mode sombre (bas risque, juste inesthétique).
