CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    nom TEXT,
    google_id TEXT UNIQUE,
    apple_id TEXT UNIQUE,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS favoris (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    date_ajout TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, produit_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS avis (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    produit_id INTEGER NOT NULL,
    user_id INTEGER,
    nom TEXT NOT NULL,
    note INTEGER NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS newsletter_abonnes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    date_inscription TEXT DEFAULT CURRENT_TIMESTAMP
);

-- Historique des commandes : la table existe deja, prete a se remplir
-- une fois qu'un vrai tunnel de commande sera branche (hors perimetre actuel).
CREATE TABLE IF NOT EXISTS commandes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    statut TEXT NOT NULL DEFAULT 'en_attente',
    total INTEGER NOT NULL DEFAULT 0,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS commande_articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commande_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL DEFAULT 1,
    prix_unitaire INTEGER NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
);

-- Certificats d'authenticité (QR code numéroté par pièce). En l'absence de
-- tunnel de commande, la pièce est associée au compte qui la scanne et se
-- connecte en premier (plutôt qu'à un vrai user_id de commande) : user_id
-- reste NULL jusqu'à ce moment-là. Le code est un jeton opaque (pas le
-- numéro de série) pour qu'on ne puisse pas deviner les autres pièces.
CREATE TABLE IF NOT EXISTS certificats_authenticite (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    produit_id INTEGER NOT NULL,
    numero_serie INTEGER NOT NULL,
    edition_totale INTEGER NOT NULL,
    code TEXT NOT NULL UNIQUE,
    user_id INTEGER,
    date_reclamation TEXT,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);